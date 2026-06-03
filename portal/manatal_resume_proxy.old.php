<?php
if (!isset($_GET['url'])) {
    http_response_code(400);
    exit('Missing resume URL.');
}

// $resumeUrl = urldecode($_GET['url']);
$resumeUrl = $_GET['url'];

// Sanity check: only allow URLs from Manatal
if (stripos($resumeUrl, 'https://media-assets.manatal.com/') !== 0) {
    http_response_code(403);
    exit('Invalid or disallowed URL.');
}

// Fetch the PDF into memory
$context = stream_context_create([
    'http' => [
        'follow_location' => true,
        'timeout' => 15
    ]
]);

$pdfContent = @file_get_contents($resumeUrl, false, $context);

if ($pdfContent === false) {
    http_response_code(502);
    exit('Could not fetch resume file.');
}

// Set headers to force inline display
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="resume.pdf"');
header('Content-Length: ' . strlen($pdfContent));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Output the PDF content
echo $pdfContent;
exit;
