<?php
// /portal/manatal_resume_proxy.php
// Usage: <iframe src="/portal/manatal_resume_proxy.php?candidate_id=112280387"></iframe>

declare(strict_types=1);
ini_set('display_errors','1'); ini_set('display_startup_errors','1'); error_reporting(E_ALL);
session_start();

// Optional: verify your session, portal login, permissions, etc.
// if (!isset($_SESSION['contactID'])) { http_response_code(403); exit('Forbidden'); }

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/db/token.php'; // provides $token = 'Token ...';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function bad_request($msg, $code = 400) {
  http_response_code($code);
  header('Content-Type: text/plain; charset=UTF-8');
  echo $msg;
  exit;
}


$candidateId = isset($_GET['candidate_id']) ? trim($_GET['candidate_id']) : '';
if ($candidateId === '' || !ctype_digit($candidateId)) {
  bad_request('Missing or invalid candidate_id');
}

$client = new Client([
  'timeout' => 30,
  'connect_timeout' => 10,
]);

// 1) Get the Manatal candidate to retrieve the resume URL

$resp = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$candidateId.'/resume/', [
  'headers' => [
    'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
    'accept' => 'application/json',
  ],
]);

echo $resp->getBody();
$data = json_decode((string)$resp->getBody(), true);
$resumeUrl = $data['resume_file'] ?? '';


// Clean any stray trailing characters (common paste artifact)
$resumeUrl = preg_replace('/[",\s]+$/', '', $resumeUrl);

// 2) OPTIONAL safety: only allow Manatal’s S3 host(s)
$allowedHosts = [
  'manatal-backend-assets.s3.amazonaws.com',
  // add any other official resume hosts Manatal might return
];
$parts = parse_url($resumeUrl);
if (empty($parts['scheme']) || empty($parts['host']) || !in_array($parts['host'], $allowedHosts, true)) {
  bad_request('Blocked resume host', 400);
}

// 3) Stream the PDF to the client
try {
  // Forward Range header to let the browser do partial requests (better UX for large PDFs)
  $forwardHeaders = [
    'accept' => 'application/pdf',
  ];
  if (!empty($_SERVER['HTTP_RANGE'])) {
    $forwardHeaders['Range'] = $_SERVER['HTTP_RANGE'];
  }

  $pdfResp = $client->request('GET', $resumeUrl, [
    'stream'  => true,
    'headers' => $forwardHeaders,
  ]);
} catch (RequestException $e) {
  $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 502;
  bad_request('Failed to fetch resume content', $status);
}

// Pass through relevant headers
$statusCode = $pdfResp->getStatusCode();
http_response_code($statusCode);

// Choose filename: prefer upstream Content-Disposition, else from path
$filename = 'resume.pdf';
$cd = $pdfResp->getHeaderLine('Content-Disposition');
if ($cd) {
  // Use upstream header as-is to preserve filename and inline/attachment directive
  header('Content-Disposition: ' . $cd);
} else {
  // Derive name from URL path if present
  if (!empty($parts['path'])) {
    $base = basename($parts['path']);
    if ($base) $filename = $base;
  }
  header('Content-Disposition: inline; filename="' . addslashes($filename) . '"');
}

// Content-Type & length/range info
$contentType = $pdfResp->getHeaderLine('Content-Type') ?: 'application/pdf';
header('Content-Type: ' . $contentType);

$hdrsToCopy = ['Content-Length','Content-Range','Accept-Ranges','Cache-Control','Last-Modified','ETag'];
foreach ($hdrsToCopy as $h) {
  $v = $pdfResp->getHeaderLine($h);
  if ($v !== '') header($h . ': ' . $v);
}
if ($pdfResp->hasHeader('Accept-Ranges') === false) {
  header('Accept-Ranges: bytes'); // helps some viewers
}

// Stream body
$body = $pdfResp->getBody();
while (!$body->eof()) {
  echo $body->read(8192);
  if (function_exists('fastcgi_finish_request')) { /* noop during stream */ }
  flush();
}
exit;

?>