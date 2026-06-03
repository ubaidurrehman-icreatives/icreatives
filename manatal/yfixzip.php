<?php
// ----- Namespace imports MUST be at the top (before other code) -----
use Smalot\PdfParser\Parser;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

// ================== Runtime flags ==================
$DEBUG = isset($_GET['debug']) || isset($_POST['debug']) || isset($_GET['d']) || isset($_POST['d']);

// ================== Error reporting ==================
// Default: keep webhook responses clean (no HTML warnings).
ini_set('display_errors', $DEBUG ? '1' : '0');
ini_set('display_startup_errors', $DEBUG ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Throw mysqli errors as exceptions (lets us close cleanly in finally)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ================== Connection manager & helpers ==================
final class DB {
    /** @var mysqli|null */
    private static $link = null;

    public static function connect(string $host, string $user, string $pw, string $db): mysqli {
        if (self::$link instanceof mysqli) return self::$link;
        self::$link = new mysqli($host, $user, $pw, $db);
        self::$link->set_charset('utf8mb4');
        return self::$link;
    }
    public static function get(): mysqli {
        if (!(self::$link instanceof mysqli)) {
            throw new RuntimeException('DB not connected. Call DB::connect() first.');
        }
        return self::$link;
    }
    public static function close(): void {
        if (self::$link instanceof mysqli) {
            @self::$link->close();
            self::$link = null;
        }
    }
}
// Always close DB at end of request (even on die/exit/fatal)
register_shutdown_function(['DB','close']);

function safe_free_result($res): void { if ($res instanceof mysqli_result) { @$res->free(); } }
function safe_close_stmt($stmt): void { if ($stmt instanceof mysqli_stmt) { @$stmt->close(); } }


// ================== Main ==================
try {
    // Read the raw POST data (debug)
    echo $requestBody = file_get_contents('php://input');

    // ------------- Composer autoload + fallbacks -------------
    $loader = (function () {
        $candidates = [
            '/vendor/autoload.php',
            dirname(__DIR__) . '/../vendor/autoload.php',
            __DIR__ . '/../vendor/autoload.php',
            (isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '\\/'): __DIR__) . '/vendor/autoload.php',
        ];
        foreach ($candidates as $file) {
            if (is_file($file)) {
                return require $file;   // Composer\Autoload\ClassLoader
            }
        }
        http_response_code(500);
        throw new RuntimeException("Composer autoloader not found. Looked in:\n" . implode("\n", $candidates));
    })();

    // If pdfparser still isn't visible, register namespace mappings at runtime
    $base = dirname(__DIR__) . '/vendor/smalot/pdfparser/src';
    if (is_object($loader) && method_exists($loader, 'addPsr4')) {
        $loader->addPsr4('Smalot\\PdfParser\\', $base);
    }
    if (is_object($loader) && method_exists($loader, 'add')) { // PSR-0
        $loader->add('Smalot\\PdfParser\\', $base);
    }

    echo class_exists(\Smalot\PdfParser\Parser::class) ? " [parser OK] " : " [parser MISSING] ";

    // ---- minimal mbstring polyfills (temporary; use real mbstring if possible) ----
    if (!function_exists('mb_check_encoding')) {
        function mb_check_encoding($var = null, $encoding = null) {
            if ($var === null) return false;
            $encoding = $encoding ?: 'UTF-8';
            if (strtoupper($encoding) === 'UTF-8') return (bool) preg_match('//u', $var);
            if (function_exists('iconv')) return @iconv($encoding, 'UTF-8//IGNORE', $var) !== false;
            return true;
        }
    }
    if (!function_exists('mb_convert_encoding')) {
        function mb_convert_encoding($str, $to, $from = null) {
            $from = $from ?: 'UTF-8';
            if (function_exists('iconv')) {
                $out = @iconv($from, $to . '//IGNORE', $str);
                return $out === false ? $str : $out;
            }
            return $str;
        }
    }
    if (!function_exists('mb_detect_encoding')) {
        function mb_detect_encoding($str, $enc = null, $strict = false) {
            return preg_match('//u', $str) ? 'UTF-8' : 'ISO-8859-1';
        }
    }
    if (!function_exists('mb_strlen'))  { function mb_strlen($str, $enc = 'UTF-8') { return strlen($str); } }
    if (!function_exists('mb_substr'))  { function mb_substr($str, $start, $length = null, $enc = 'UTF-8') { return $length === null ? substr($str, $start) : substr($str, $start, $length); } }
    if (!function_exists('mb_strpos'))  { function mb_strpos($haystack, $needle, $offset = 0, $enc = 'UTF-8') { return strpos($haystack, $needle, $offset); } }

    echo 'mbstring: '.(extension_loaded('mbstring') ? 'ON' : 'OFF');

    // ================== Helpers (no DB opened yet) ==================
    function makePdfParser() /* : Parser */ {
        if (class_exists(\Smalot\PdfParser\Config::class)) {
            $config = new \Smalot\PdfParser\Config();
            try { return new \Smalot\PdfParser\Parser([], $config);
            } catch (\TypeError|\ArgumentCountError $e) {
                try { return new \Smalot\PdfParser\Parser($config);
                } catch (\Throwable $e2) { return new \Smalot\PdfParser\Parser(); }
            }
        }
        return new \Smalot\PdfParser\Parser();
    }

    echo "hello";

    $data = json_decode($requestBody, true);

    $manatalid   = $data['custom_fields']['manatalid'] ?? null;
    $applicantId = $data['id'] ?? null;
    $oldZip      = $data['custom_fields']['postalcode'] ?? null;
	
	// --- Resolve applicantId: JSON first, then test overrides ---
	$applicantId = $data['id'] ?? null;

	// Allow manual test overrides (query string or POST). Only used if JSON is missing.
	if (!$applicantId) {
		$override = $_GET['id'] ?? $_GET['test_id'] ?? $_POST['id'] ?? $_POST['test_id'] ?? null;
		if ($override !== null) {
			// keep digits only (Manatal IDs are numeric)
			$override = preg_replace('/\D+/', '', (string)$override);
			if ($override !== '') {
				$applicantId = $override;
			}
		}
	}

	// If still no id, exit webhook early with a harmless 204 No Content
	if (!$applicantId) {
		http_response_code(204);      // or use 400 if you want sender to treat as error
		echo "No id in payload—ignored.";
		// DB::close() will run via shutdown function even if we haven't connected.
		return; // or exit;  (both ok)
	}

    // DB credentials (lazy connect later)
    $db     = "dbs14831214";
    $dbuser = "dbu415258";
    $pw     = 'pZCD@4ZCSgA$$E!';
    $host   = "db5018755071.hosting-data.io";

    // ZIP extraction helpers
    function normalize_for_zip(string $s): string {
        $s = str_replace(["\r", "\n", "\t"], ' ', $s);
        $s = str_replace(["\xE2\x80\x8B", "\xE2\x80\x8C", "\xE2\x80\x8D", "\xEF\xBB\xBF"], '', $s);
        $s = str_replace(["\xC2\xA0", "\xE2\x80\xAF", "\xE2\x80\x87"], ' ', $s);
        $s = preg_replace('/\s+/', ' ', $s);
        $s = str_replace(["\xEF\xBC\x90","\xEF\xBC\x91","\xEF\xBC\x92","\xEF\xBC\x93","\xEF\xBC\x94","\xEF\xBC\x95","\xEF\xBC\x96","\xEF\xBC\x97","\xEF\xBC\x98","\xEF\xBC\x99"], ['0','1','2','3','4','5','6','7','8','9'], $s);
        $s = str_replace(["\xD9\xA0","\xD9\xA1","\xD9\xA2","\xD9\xA3","\xD9\xA4","\xD9\xA5","\xD9\xA6","\xD9\xA7","\xD9\xA8","\xD9\xA9"], ['0','1','2','3','4','5','6','7','8','9'], $s);
        $s = str_replace(["\xDB\xB0","\xDB\xB1","\xDB\xB2","\xDB\xB3","\xDB\xB4","\xDB\xB5","\xDB\xB6","\xDB\xB7","\xDB\xB8","\xDB\xB9"], ['0','1','2','3','4','5','6','7','8','9'], $s);
        return $s;
    }
    function extractZipCode(string $text): ?string {
        $s = normalize_for_zip($text);
        $stateMap = [
            'Alabama'=>'AL','Alaska'=>'AK','Arizona'=>'AZ','Arkansas'=>'AR','California'=>'CA','Colorado'=>'CO',
            'Connecticut'=>'CT','Delaware'=>'DE','District of Columbia'=>'DC','Washington DC'=>'DC','D.C.'=>'DC','DC'=>'DC',
            'Florida'=>'FL','Georgia'=>'GA','Hawaii'=>'HI','Idaho'=>'ID','Illinois'=>'IL','Indiana'=>'IN','Iowa'=>'IA',
            'Kansas'=>'KS','Kentucky'=>'KY','Louisiana'=>'LA','Maine'=>'ME','Maryland'=>'MD','Massachusetts'=>'MA',
            'Michigan'=>'MI','Minnesota'=>'MN','Mississippi'=>'MS','Missouri'=>'MO','Montana'=>'MT','Nebraska'=>'NE',
            'Nevada'=>'NV','New Hampshire'=>'NH','New Jersey'=>'NJ','New Mexico'=>'NM','New York'=>'NY',
            'North Carolina'=>'NC','North Dakota'=>'ND','Ohio'=>'OH','Oklahoma'=>'OK','Oregon'=>'OR','Pennsylvania'=>'PA',
            'Rhode Island'=>'RI','South Carolina'=>'SC','South Dakota'=>'SD','Tennessee'=>'TN','Texas'=>'TX','Utah'=>'UT',
            'Vermont'=>'VT','Virginia'=>'VA','Washington'=>'WA','West Virginia'=>'WV','Wisconsin'=>'WI','Wyoming'=>'WY',
            'Calif'=>'CA','Penna'=>'PA'
        ];
        $tokens = array_unique(array_merge(array_keys($stateMap), array_values($stateMap)));
        usort($tokens, fn($a,$b) => strlen($b) - strlen($a));
        $states = implode('|', array_map(fn($t)=>preg_quote($t,'/'), $tokens));

        if (preg_match('/(?<![A-Za-z])(?:'.$states.')(?![A-Za-z])[^0-9]{0,12}(\d{5}(?:-\d{4})?)/i', $s, $m)) return $m[1];
        if (preg_match('/(?<!\d)\d{5}(?:-\d{4})?(?!\d)/', $s, $m)) return $m[0];
        if (preg_match('/(?<!\d)(\d{3})[^\d]{0,3}(\d{2})(?:[^\d]?(\d{4}))?(?!\d)/', $s, $m)) return empty($m[3]) ? $m[1].$m[2] : $m[1].$m[2].'-'.$m[3];
        return null;
    }

    // Demo (optional)
    // $txt = ' 5515 Penfield Ave Apt 201, Woodland Hills, CA 91364 ♦ (818) 587-6522 ♦ maryamahangari86@gmail.com ...';
    // echo extractZipCode($txt); // => 91364

    function expandCityAbbreviations($city) {
        $map = ['Ft.'=>'Fort','St.'=>'Saint','Mt.'=>'Mount','Ft '=>'Fort ','St '=>'Saint ','Mt '=>'Mount '];
        foreach ($map as $abbr => $full) { $city = preg_replace('/\b' . preg_quote($abbr, '/') . '\b/i', $full, $city); }
        return $city;
    }

    function getZipCodeFromDatabase($city, $state, $mysqli) {
        $state = strtoupper($state);
        $city  = trim(preg_replace('/\s+/', ' ', expandCityAbbreviations($city)));
        $words = explode(' ', $city);

        while (!empty($words)) {
            $currentCity = implode(' ', $words);
            $query = "SELECT zipCode,city,stateISO FROM ic_cities_zipcodes
                      WHERE city = '" . $mysqli->real_escape_string($currentCity) . "'
                        AND stateISO = '" . $mysqli->real_escape_string($state) . "'
                      LIMIT 1";
            $result = null;
            try {
                $result = $mysqli->query($query);
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo $row['city']. " ". $row['stateISO']." - ";
                    return $row['zipCode'];
                }
            } finally { safe_free_result($result); }
            array_shift($words);
        }
        return null;
    }

    function extractTextFromPDFMemory(string $binaryPdf): string {
        $parser = makePdfParser();
        if (method_exists($parser, 'parseContent')) {
            $pdf = $parser->parseContent($binaryPdf);
        } else {
            $tmp = tempnam(sys_get_temp_dir(), 'pdf_');
            file_put_contents($tmp, $binaryPdf);
            try { $pdf = $parser->parseFile($tmp); }
            finally { @unlink($tmp); }
        }
        return $pdf->getText();
    }

    function parseWordFromMemory($docxContent) {
        $zip = new ZipArchive;
        $tempFile = tempnam(sys_get_temp_dir(), 'docx');
        file_put_contents($tempFile, $docxContent);

        if ($zip->open($tempFile) !== true) { @unlink($tempFile); throw new RuntimeException("Failed to open DOCX as ZIP."); }
        $xmlContent = $zip->getFromName('word/document.xml');
        $zip->close(); @unlink($tempFile);
        if ($xmlContent === false) { throw new RuntimeException("Missing word/document.xml in DOCX."); }

        $xml = new DOMDocument; $xml->loadXML($xmlContent);
        $text = '';
        foreach ($xml->getElementsByTagName('t') as $t) { $text .= $t->nodeValue . ' '; }
        return trim(preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $text));
    }

    function fetchFileContentFromUrl($url) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true]);
        $data = curl_exec($ch);
        if (curl_errno($ch)) { $err = curl_error($ch); curl_close($ch); throw new RuntimeException("Error fetching file: $err"); }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200) throw new RuntimeException("Failed to fetch file. HTTP $httpCode");
        return $data;
    }

    // ================== Main script execution (no DB yet) ==================
    $client = new \GuzzleHttp\Client(['timeout'=>30,'connect_timeout'=>30]);

    if (!$applicantId) throw new RuntimeException("Missing applicantId in payload.");

    $response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
        'headers' => [
            'Authorization' => $token,
            'accept'        => 'application/json',
        ],
    ]);
    $data = json_decode($response->getBody(), true);

    $manatalid   = $data['custom_fields']['manatalid'] ?? null;
    $applicantId = $data['id'] ?? null;
    $oldZip      = $data['custom_fields']['postalcode'] ?? null;
    $url         = $data['resume'] ?? null;

    if (!$applicantId || !$url) throw new RuntimeException("Missing applicantId or resume URL.");

    $fileContent = fetchFileContentFromUrl($url);
    $urlPath     = parse_url($url, PHP_URL_PATH);
    $fileExtension = pathinfo($urlPath, PATHINFO_EXTENSION);

    if ($fileExtension === 'pdf') {
        $text = extractTextFromPDFMemory($fileContent);
    } elseif ($fileExtension === 'docx') {
        $text = parseWordFromMemory($fileContent);
    } else {
        throw new RuntimeException("Unsupported file format.");
    }

    echo "the zipcode is: ";
    echo $zipCode = extractZipCode($text);
    if (!$zipCode) {
        // Now we need DB for lookup — connect here (lazy connect)
        $mysqli   = DB::connect($host, $dbuser, $pw, $db);
        $cityState= extractCityState($text, $mysqli);
        if ($cityState) $zipCode = $cityState['zipCode'];
    }

    echo "manatalid ". ($manatalid ?? 'NULL');
    echo " applicantId ". $applicantId;
    echo " zipCode ". ($zipCode ?? 'NULL');
	echo "<br>Text = ".$text."<br>";
    // Patch candidate if needed
    if (((isset($manatalid) && $manatalid !== $applicantId) || (isset($zipCode) && $zipCode !== $oldZip)) || !isset($manatalid) || !isset($oldZip)) {
        $data['custom_fields']['postalcode'] = $zipCode;
        $data['custom_fields']['manatalid']  = $data['id'];
        $icowner = $data['custom_fields']['icowner'] ?? ($data['owner'] ?? null);
        $customFields = json_encode($data['custom_fields']);

        $client2 = new \GuzzleHttp\Client(['timeout'=>30,'connect_timeout'=>10]);
        $resp = $client2->request('PATCH', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
            'body' => '{"custom_fields":' . $customFields . ',"owner":'.($icowner ?? 'null').',"zipcode":"' . ($zipCode ?? '') . '"}',
            'headers' => [
                'Authorization' => $token,
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ],
        ]);
        $resp->getBody();
        // echo (string)$resp->getBody();
        echo $zipCode;
    } elseif ( isset($data['custom_fields']['icowner']) && ($data['custom_fields']['icowner'] ?? null) != ($data['owner'] ?? null) ) {
        $icowner = $data['custom_fields']['icowner'] ?? $data['owner'];
        $client3 = new \GuzzleHttp\Client(['timeout'=>30,'connect_timeout'=>10]);
        $resp = $client3->request('PATCH', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
            'body' => '{"owner":'.($icowner ?? 'null').'}',
            'headers' => [
                'Authorization' => $token,
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ],
        ]);
        // echo (string)$resp->getBody();
    } else {
        echo "No ZIP code found in the resume.\n";
    }

    // ================== DB updates for merged candidates ==================
    if ((isset($data['custom_fields']['manatalid']) && $data['custom_fields']['manatalid'] !== $data['id']) || !isset($data['custom_fields']['manatalid'])) {
        echo "XXX";
        $new_id = $data['id'];
        $old_id = $data['custom_fields']['manatalid'] ?? null;

        if ($old_id) {
            // Ensure DB is connected
            $mysqli = DB::connect($host, $dbuser, $pw, $db);

            $query = "UPDATE ic_matches SET candidate = ? WHERE candidate = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss', $new_id, $old_id);
            $stmt->execute();
            safe_close_stmt($stmt);

            $query = "UPDATE ic_timesheets SET Employee_Id = ? WHERE Employee_Id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss', $new_id, $old_id);
            $stmt->execute();
            safe_close_stmt($stmt);

            $query = "UPDATE ic_candidate_self_eval SET id = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ss', $new_id, $old_id);
            $stmt->execute();
            safe_close_stmt($stmt);
        }
    }

} catch (Throwable $e) {
    // Log server-side; keep output minimal
    error_log("fixzip.php fatal: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo "\nInternal error.\n";
} finally {
    // Explicit close (also covered by shutdown function)
    DB::close();
}

// ================== Functions that may use DB (defined last) ==================
function extractCityState($text, $mysqli)
{
    $stateMapping = [
        'Alabama' => 'AL', 'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR',
        'California' => 'CA', 'Colorado' => 'CO', 'Connecticut' => 'CT', 'DC' => 'DC', 'Delaware' => 'DE',
        'Florida' => 'FL', 'Georgia' => 'GA', 'Hawaii' => 'HI', 'Idaho' => 'ID',
        'Illinois' => 'IL', 'Indiana' => 'IN', 'Iowa' => 'IA', 'Kansas' => 'KS',
        'Kentucky' => 'KY', 'Louisiana' => 'LA', 'Maine' => 'ME', 'Maryland' => 'MD',
        'Massachusetts' => 'MA', 'Michigan' => 'MI', 'Minnesota' => 'MN', 'Mississippi' => 'MS',
        'Missouri' => 'MO', 'Montana' => 'MT', 'Nebraska' => 'NE', 'Nevada' => 'NV',
        'New Hampshire' => 'NH', 'New Jersey' => 'NJ', 'New Mexico' => 'NM', 'New York' => 'NY',
        'North Carolina' => 'NC', 'North Dakota' => 'ND', 'Ohio' => 'OH', 'Oklahoma' => 'OK',
        'Oregon' => 'OR', 'Pennsylvania' => 'PA', 'Rhode Island' => 'RI', 'South Carolina' => 'SC',
        'South Dakota' => 'SD', 'Tennessee' => 'TN', 'Texas' => 'TX', 'Utah' => 'UT',
        'Vermont' => 'VT', 'Virginia' => 'VA', 'Washington' => 'WA', 'West Virginia' => 'WV',
        'Wisconsin' => 'WI', 'Wyoming' => 'WY'
    ];

    $pattern = '/\b([A-Z][a-zA-Z]*(?:\s+[A-Z][a-zA-Z]*){0,3})\s*,?\s+(?i)([A-Z]{2}|' . implode('|', array_keys($stateMapping)) . ')\b/';
    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        if (isset($match[1], $match[2])) {
            $city = $match[1];
            $state = $match[2];

            if (strlen($state) === 2) {
                $state = strtoupper($state);
            } else {
                if (array_key_exists(ucwords(strtolower($state)), $stateMapping)) {
                    $state = $stateMapping[ucwords(strtolower($state))];
                }
            }

            $zipCode = getZipCodeFromDatabase($city, $state, $mysqli);
            if ($zipCode) {
                return ['city' => $city, 'state' => $state, 'zipCode' => $zipCode];
            }
        }
    }
    return null;
}
?>