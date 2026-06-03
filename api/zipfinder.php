<?php
// Read the raw POST data
$requestBody = file_get_contents('php://input');

// Decode the JSON string into an associative array
$data = json_decode($requestBody, true);
$applicantId = $data['id'];

// $applicantId = 101208954;
// $applicantId = 101621949;
// $applicantId = 101621287;
// $applicantId = 101690881;
$applicantId = 102175646;
// $applicantId = 67046859;
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2', 'ck3b2t') or die("Error: " . mysqli_error());

use Smalot\PdfParser\Parser;
use Smalot\PdfParser\Config;

// Function to extract ZIP code from text
function extractZipCode($text)
{
    $pattern = '/\b\d{5}(?:-\d{4})?\b/'; // Match 5-digit or 9-digit ZIP codes
    preg_match($pattern, $text, $matches);
    return $matches[0] ?? null;
}

// Updated function to extract city and state from text (search all matches)
function extractCityState($text, $link)
{
    $stateMapping = [
        'Alabama' => 'AL', 'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR',
        'California' => 'CA', 'Colorado' => 'CO', 'Connecticut' => 'CT', 'Delaware' => 'DE',
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
            $zipCode = getZipCodeFromDatabase($city, $state, $link);
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
function getZipCodeFromDatabase($city, $state, $link)
{
	$state = strtoupper($state);
	
	$city = preg_replace('/\s+/', ' ', $city);  // Replaces all whitespace (newlines, tabs, etc.) with a single space
	$city = trim($city);  // Remove any leading or trailing whitespace
	$city = expandCityAbbreviations($city);
    $words = explode(' ', $city);


    while (!empty($words)) {
        $currentCity = implode(' ', $words);

        $query = "SELECT zipCode,city,stateISO FROM ic_cities_zipcodes WHERE city = '" . $link->real_escape_string($currentCity) . "' AND stateISO = '" . $link->real_escape_string($state) . "' LIMIT 1";
        $result = $link->query($query);
		// echo $row['city']. " ". $row['stateISO']." - ";
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $row['city']. " ". $row['stateISO']." - ";
            return $row['zipCode'];
        }

        array_shift($words); // Remove the first word and try again
    }

    return null;
}

// Function to extract text from a PDF file in memory
function extractTextFromPDFMemory($fileContent)
{
    // Configure the parser to handle more complex PDFs
    $config = new Config();
    $config->setHorizontalOffset('');       // May help with layout handling
    // $config->setIgnoreCircularReferences(true); // Avoids issues with circular references in the PDF structure

    $parser = new Parser([], $config);

    // Parse the PDF content directly
    $pdf = $parser->parseContent($fileContent);
//  echo $pdf->getText();
    // Return extracted text
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

// Function to add ZIP code to Manatal API
function addZipToManatal($applicantId, $zipCode, $data)
{
    if (!isset($data['custom_fields']['postalcode']) || empty($data['custom_fields']['postalcode'])) {
        $data['custom_fields']['postalcode'] = $zipCode;
        $customFields = json_encode($data['custom_fields']);

        $client = new \GuzzleHttp\Client();
        $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
            'body' => '{"custom_fields":' . $customFields . ',"zipcode":"' . $zipCode . '"}',
            'headers' => [
                'Authorization' => $token,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);
    }
}

// Main script execution
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/' . $applicantId . '/', [
    'headers' => [
        'Authorization' => $token,
        'accept' => 'application/json',
    ],
]);

$data = json_decode($response->getBody(), true);
$url = $data['resume'];

$fileContent = fetchFileContentFromUrl($url);
$urlPath = parse_url($url, PHP_URL_PATH);
echo $fileExtension = pathinfo($urlPath, PATHINFO_EXTENSION);

if ($fileExtension === 'pdf') {
    echo $text = extractTextFromPDFMemory($fileContent);
} else if ($fileExtension === 'docx') {
	$text = parseWordFromMemory($fileContent);
} else {
    die("Unsupported file format.");
}

$zipCode = extractZipCode($text);
if (!$zipCode) {
    $cityState = extractCityState($text, $link);
    if ($cityState) {
        $zipCode = $cityState['zipCode'];
    }
}

if ($zipCode) {
    addZipToManatal($applicantId, $zipCode, $data);
    echo $zipCode;
} else {
    echo "No ZIP code found in the resume.\n";
}
