<?php
// Read the raw POST data
echo $requestBody = file_get_contents('php://input');

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$mysqli = db(); 

// echo "XXX".$requestBody."XXX";

// Decode the JSON string into an associative array
$data = json_decode($requestBody, true);

$manatalid = $data['custom_fields']['manatalid'];
$applicantId = $data['id'];
$oldZip = $data['custom_fields']['postalcode'];

// show errors ASAP (helps while fixing 500s)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// ------------- Composer autoload + fallbacks -------------
$loader = (function () {
    $candidates = [
        __DIR__ . '/../vendor/autoload.php',
        dirname(__DIR__) . '/vendor/autoload.php',
        __DIR__ . '/vendor/autoload.php',
        (isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '\\/'): __DIR__) . '/vendor/autoload.php',
    ];
    foreach ($candidates as $file) {
        if (is_file($file)) {
            return require $file;   // Composer\Autoload\ClassLoader
        }
    }
    http_response_code(500);
    die("Composer autoloader not found. Looked in:\n" . implode("\n", $candidates));
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
        if (strtoupper($encoding) === 'UTF-8') {
            return (bool) preg_match('//u', $var);
        }
        if (function_exists('iconv')) {
            return @iconv($encoding, 'UTF-8//IGNORE', $var) !== false;
        }
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
if (!function_exists('mb_strlen')) {
    function mb_strlen($str, $enc = 'UTF-8') { return strlen($str); }
}
if (!function_exists('mb_substr')) {
    function mb_substr($str, $start, $length = null, $enc = 'UTF-8') {
        return $length === null ? substr($str, $start) : substr($str, $start, $length);
    }
}
if (!function_exists('mb_strpos')) {
    function mb_strpos($haystack, $needle, $offset = 0, $enc = 'UTF-8') {
        return strpos($haystack, $needle, $offset);
    }
}

echo 'mbstring: '.(extension_loaded('mbstring') ? 'ON' : 'OFF');



use Smalot\PdfParser\Parser;
// ⚠️ Do NOT "use" Config here; some versions don't have it and you don't need the alias.

// --------- makePdfParser WITHOUT a return type (avoid parse-time autoload) ---------
// --------- makePdfParser WITHOUT a return type (leave exactly like this) ---------
function makePdfParser() /* : Parser */
{
    // If the Config class exists, try both constructor orders.
    if (class_exists(\Smalot\PdfParser\Config::class)) {
        $config = new \Smalot\PdfParser\Config();

        // 1) Newer sig: (array $settings = [], Config $config = null)
        try {
            return new \Smalot\PdfParser\Parser([], $config);
        } catch (\TypeError|\ArgumentCountError $e) {
            // 2) Older sig fallback: (Config $config = null, array $settings = [])
            try {
                return new \Smalot\PdfParser\Parser($config);
            } catch (\Throwable $e2) {
                // Last resort: default parser (no custom config)
                return new \Smalot\PdfParser\Parser();
            }
        }
    }

    // No Config class in this version — just use defaults
    return new \Smalot\PdfParser\Parser();
}




// echo "XXX".$requestBody."XXX";

// Decode the JSON string into an associative array


// echo "XXX".$data['id']."XXX";

/*
$manatalid = $data['custom_fields']['manatalid'];
// $applicantId = $data['id'];
$oldZip = $data['custom_fields']['postalcode'];
// print_r($data);
*/
// $applicantId = 112280387;


// Function to extract ZIP code from text


function normalize_for_zip(string $s): string {
    // unify whitespace
    $s = str_replace(["\r", "\n", "\t"], ' ', $s);

    // strip zero-width chars (ZWSP/ZWNJ/ZWJ/BOM)
    $s = str_replace(
        ["\xE2\x80\x8B", "\xE2\x80\x8C", "\xE2\x80\x8D", "\xEF\xBB\xBF"], // UTF-8 bytes
        '',
        $s
    );

    // NBSP / narrow NBSP / figure space -> plain space
    $s = str_replace(["\xC2\xA0", "\xE2\x80\xAF", "\xE2\x80\x87"], ' ', $s);

    // collapse spaces (no /u flag)
    $s = preg_replace('/\s+/', ' ', $s);

    // Full-width digits → ASCII
    $s = str_replace(
        ["\xEF\xBC\x90","\xEF\xBC\x91","\xEF\xBC\x92","\xEF\xBC\x93","\xEF\xBC\x94","\xEF\xBC\x95","\xEF\xBC\x96","\xEF\xBC\x97","\xEF\xBC\x98","\xEF\xBC\x99"],
        ['0','1','2','3','4','5','6','7','8','9'],
        $s
    );

    // Arabic-Indic digits → ASCII
    $s = str_replace(
        ["\xD9\xA0","\xD9\xA1","\xD9\xA2","\xD9\xA3","\xD9\xA4","\xD9\xA5","\xD9\xA6","\xD9\xA7","\xD9\xA8","\xD9\xA9"],
        ['0','1','2','3','4','5','6','7','8','9'],
        $s
    );

    // Extended Arabic-Indic digits → ASCII
    $s = str_replace(
        ["\xDB\xB0","\xDB\xB1","\xDB\xB2","\xDB\xB3","\xDB\xB4","\xDB\xB5","\xDB\xB6","\xDB\xB7","\xDB\xB8","\xDB\xB9"],
        ['0','1','2','3','4','5','6','7','8','9'],
        $s
    );

    return $s;
}
function extractZipCode(string $text): ?string {
    $s = normalize_for_zip($text);

    // Accept full state names and abbreviations
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
    usort($tokens, fn($a,$b) => strlen($b) - strlen($a)); // longest first
    $states = implode('|', array_map(fn($t)=>preg_quote($t,'/'), $tokens));

    // 1) Prefer a ZIP that follows a state token (glued or spaced). No \b after state!
    if (preg_match('/(?<![A-Za-z])(?:'.$states.')(?![A-Za-z])[^0-9]{0,12}(\d{5}(?:-\d{4})?)/i', $s, $m)) {
        return $m[1];
    }

    // 2) Plain ZIP anywhere (5 or 9 digits)
    if (preg_match('/(?<!\d)\d{5}(?:-\d{4})?(?!\d)/', $s, $m)) {
        return $m[0];
    }

    // 3) Broken ZIP like "913 64" or "913​64" (PDF inserted a tiny separator)
    if (preg_match('/(?<!\d)(\d{3})[^\d]{0,3}(\d{2})(?:[^\d]?(\d{4}))?(?!\d)/', $s, $m)) {
        return empty($m[3]) ? $m[1].$m[2] : $m[1].$m[2].'-'.$m[3];
    }

    return null;
}

// --- demo with your text ---
// $txt = ' 5515 Penfield Ave Apt 201, Woodland Hills, CA 91364 ♦ (818) 587-6522 ♦ maryamahangari86@gmail.com ...';
// echo extractZipCode($txt); // => 91364

// Updated function to extract city and state from text (search all matches)
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

    // Updated regex pattern
    $pattern = '/\b([A-Z][a-zA-Z]*(?:\s+[A-Z][a-zA-Z]*){0,3})\s*,?\s+(?i)([A-Z]{2}|' . implode('|', array_keys($stateMapping)) . ')\b/';

    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        if (isset($match[1], $match[2])) {
            $city = $match[1];  // Preserve the city name exactly as it appears
            $state = $match[2];

            // Normalize state abbreviation to uppercase
            if (strlen($state) === 2) {
                $state = strtoupper($state);
            } else {
                // Convert full state name to abbreviation if necessary
                if (array_key_exists(ucwords(strtolower($state)), $stateMapping)) {
                    $state = $stateMapping[ucwords(strtolower($state))];
                }
            }

            // Check the database for a valid zip code
            $zipCode = getZipCodeFromDatabase($city, $state, $mysqli);
            if ($zipCode) {
                return ['city' => $city, 'state' => $state, 'zipCode' => $zipCode];
            }
        }
    }

    // echo "XXX";  // Debug statement to confirm no match
    return null;  // Return null if no valid match is found
}

function expandCityAbbreviations($city)
{
    // Define abbreviations specific to cities
    $cityAbbreviationMap = [
        'Ft.' => 'Fort',
        'St.' => 'Saint',
        'Mt.' => 'Mount',
		'Ft ' => 'Fort ',
        'St ' => 'Saint ',
        'Mt ' => 'Mount ',
    ];

    // Use regex to replace abbreviations only when they appear as full words at the start or inside the city name
    foreach ($cityAbbreviationMap as $abbr => $full) {
        $city = preg_replace('/\b' . preg_quote($abbr, '/') . '\b/i', $full, $city);
    }

    return $city;
}


// Updated function to look up ZIP code in the database
function getZipCodeFromDatabase($city, $state, $mysqli)
{
	$state = strtoupper($state);
	
	$city = preg_replace('/\s+/', ' ', $city);  // Replaces all whitespace (newlines, tabs, etc.) with a single space
	$city = trim($city);  // Remove any leading or trailing whitespace
	$city = expandCityAbbreviations($city);
    $words = explode(' ', $city);


    while (!empty($words)) {
        $currentCity = implode(' ', $words);

        $query = "SELECT zipCode,city,stateISO FROM ic_cities_zipcodes WHERE city = '" . $mysqli->real_escape_string($currentCity) . "' AND stateISO = '" . $mysqli->real_escape_string($state) . "' LIMIT 1";
        $result = $mysqli->query($query);
		// echo $row['city']. " ". $row['stateISO']." - ";
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $row['city']. " ". $row['stateISO']." - ";
			$result->free();
            return $row['zipCode'];
        }

        array_shift($words); // Remove the first word and try again
    }
	$result->free();
    return null;
}

// Function to extract text from a PDF file in memory

function extractTextFromPDFMemory(string $binaryPdf): string
{
    $parser = makePdfParser();

    // Prefer in-memory parsing if available; otherwise fall back to a temp file.
    if (method_exists($parser, 'parseContent')) {
        $pdf = $parser->parseContent($binaryPdf);
    } else {
        $tmp = tempnam(sys_get_temp_dir(), 'pdf_');
        file_put_contents($tmp, $binaryPdf);
        try {
            $pdf = $parser->parseFile($tmp);
        } finally {
            @unlink($tmp);
        }
    }
    return $pdf->getText();
}

function parseWordFromMemory($docxContent) {
    // Initialize a ZipArchive instance
    $zip = new ZipArchive;

    // Create a temporary file in memory
    $tempFile = tempnam(sys_get_temp_dir(), 'docx');

    // Write the .docx content into the temporary file
    file_put_contents($tempFile, $docxContent);

    // Open the temporary file as a ZIP archive
    if ($zip->open($tempFile) !== true) {
        unlink($tempFile); // Ensure cleanup
        throw new Exception("Failed to open the .docx content as a ZIP archive.");
    }

    // Locate and extract the 'word/document.xml' file
    $xmlContent = $zip->getFromName('word/document.xml');
    $zip->close(); // Close the archive
    unlink($tempFile); // Clean up the temporary file

    if ($xmlContent === false) {
        throw new Exception("Failed to find 'word/document.xml' in the .docx content.");
    }

    // Use a simple XML parser to extract text content
    $xml = new DOMDocument;
    $xml->loadXML($xmlContent);

    $text = '';
    foreach ($xml->getElementsByTagName('t') as $textNode) {
        $text .= $textNode->nodeValue . ' ';
    }

    // Optionally clean up the text to remove unwanted characters
    $text = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $text);

    return trim($text);
}


// Function to fetch file content from a URL
function fetchFileContentFromUrl($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);

    if (curl_errno($ch)) {
        die("Error fetching file: " . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        die("Failed to fetch file. HTTP status code: $httpCode");
    }

    return $data;
}

// Main script execution

$client = new \GuzzleHttp\Client([
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 30
]);

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

if (!isset($applicantId) || empty($applicantId) ) {
	$applicantId = $_REQUEST['id'];
	// // $applicantId = 112280387;
}
try {
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
		'headers' => [
        'Authorization' => $token,
        'accept' => 'application/json',
		],
	]);
	$response->getBody();	
	$data = json_decode($response->getBody(), true);

} catch (ConnectException | RequestException | \Exception $e) {
	// echo "<script>alert('The server (applicant id) is extremely busy, please retry');</script>";
    $apiError = true;
	exit();
}

$manatalid = $data['custom_fields']['manatalid'];
$applicantId = $data['id'];
$oldZip = $data['custom_fields']['postalcode'];
$url = $data['resume'];

$fileContent = fetchFileContentFromUrl($url);
$urlPath = parse_url($url, PHP_URL_PATH);
echo $fileExtension = pathinfo($urlPath, PATHINFO_EXTENSION);

if ($fileExtension === 'pdf') {
    $text = extractTextFromPDFMemory($fileContent);
} else if ($fileExtension === 'docx') {
	$text = parseWordFromMemory($fileContent);
} else {
    die("Unsupported file format.");
}
echo "the zipcode is: ";
echo $zipCode = extractZipCode($text);
if (!$zipCode) {
    $cityState = extractCityState($text, $mysqli);
    if ($cityState) {
        $zipCode = $cityState['zipCode'];
    }
}
// echo $zipCode."aaa".$oldZip."bbb".$applicantId."ccc".$manatalid;

echo "manatalid ". $manatalid ;
echo " applicantId ". $applicantId;
echo " zipCode ". $zipCode;
echo " zipCode ". $zipCode;

	if ( ((isset($manatalid) &&  $manatalid !== $applicantId) || (isset($zipCode) && $zipCode !== $oldZip)) || empty($manatalid) || empty($oldZip) ) {
		$data['custom_fields']['postalcode'] = $zipCode;
        $data['custom_fields']['manatalid'] = $data['id'];
        $icowner = $data['custom_fields']['icowner'] ?? $data['owner'];

        $customFields = json_encode($data['custom_fields']);


		$client = new \GuzzleHttp\Client([
		'timeout'         => 30,  // wait up to 10 seconds per attempt
		'connect_timeout' => 10
		]);

	try {
        $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
            'body' => '{"custom_fields":' . $customFields . ',"owner":'.$icowner.',"zipcode":"' . $zipCode . '"}',
            'headers' => [
                'Authorization' => $token,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);		
		echo $client->getBody();		
		// addZipToManatal($applicantId, $zipCode, $data);
		echo $zipCode; 

		} catch (ConnectException | RequestException | \Exception $e) {
			// echo "<script>alert('The server (patch applicant id) is extremely busy, please retry');</script>";
			$apiError = true;
			exit();
		}


	} elseif ( isset($data['custom_fields']['icowner']) && $data['custom_fields']['icowner'] != $data['owner'] ) {
        $icowner = $data['custom_fields']['icowner'] ?? $data['owner'];
        $client = new \GuzzleHttp\Client([
		'timeout'         => 30,  // wait up to 10 seconds per attempt
		'connect_timeout' => 10
		]);



	try {
        $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
            'body' => '{"owner":'.$icowner.'}',
            'headers' => [
                'Authorization' => $token,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);		
		echo $client->getBody();
		} catch (ConnectException | RequestException | \Exception $e) {
			// echo "<script>alert('The server (second patch) is extremely busy, please retry');</script>";
			$apiError = true;
			exit();
		}
	} else {	
    echo "No ZIP code found in the resume.\n";
}

// check if merged, (when custom field manatalid <> candidate id),  then fix ic_matches, ic_timesheets and ic_candidate_self_eval
// maybe using email address would be better?
 if ( (isset($data['custom_fields']['manatalid']) && $data['custom_fields']['manatalid'] !== $data['id']) || empty($data['custom_fields']['manatalid']) ) {
		echo "XXX";
		echo $new_id = $data['id'];
		echo $old_id = $data['custom_fields']['manatalid'];
		
	    $query = "UPDATE ic_matches 
			SET candidate = '$new_id' WHERE candidate = '".$old_id."'";
			$result = $mysqli->query($query);
		 
		$query = "UPDATE ic_timesheets 
			SET Employee_Id = '$new_id' WHERE Employee_Id = '".$old_id."'";
			$result = $mysqli->query($query);
		
		$query = "UPDATE ic_candidate_self_eval 
			SET id = '$new_id' WHERE id = '".$old_id."'";
			$result = $mysqli->query($query);
		$result->free();
}
mysqli_close($mysqli);


?>