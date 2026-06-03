<?php
// /portal/manatal_resume_proxy.php
// Usage: <iframe src="/portal/manatal_resume_proxy.php?candidate_id=112280387"></iframe>

declare(strict_types=1);
ini_set('display_errors','1'); ini_set('display_startup_errors','1'); error_reporting(E_ALL);
session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/db/token.php'; // must define $token = 'Token ...';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function bad_request($msg, $code = 400) {
  http_response_code($code);
  header('Content-Type: text/plain; charset=UTF-8');
  echo $msg;
  exit;
}

// --- validate input ---
$candidateId = isset($_GET['candidate_id']) ? trim($_GET['candidate_id']) : '';
if ($candidateId === '' || !ctype_digit($candidateId)) {
  bad_request('Missing or invalid candidate_id', 400);
}

$client = new Client([
  'timeout'         => 30,
  'connect_timeout' => 10,
]);

// --- 1) Get resume URL from Manatal ---
try {
  $resp = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$candidateId.'/resume/', [
    'headers' => [
      'Authorization' => $token,              // use token from token.php
      'accept'        => 'application/json',
    ],
  ]);
} catch (RequestException $e) {
  $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 502;
  bad_request('Failed to fetch resume metadata', $status);
}

$data = json_decode((string)$resp->getBody(), true);
$resumeUrl = $data['resume_file'] ?? '';
if (!$resumeUrl) {
  bad_request('No resume file found for candidate', 404);
}

// Clean paste artifacts
$resumeUrl = preg_replace('/[",\s]+$/', '', $resumeUrl);

// --- 2) Safety: allow only known hosts (optional but recommended) ---
$allowedHosts = [
  'manatal-backend-assets.s3.amazonaws.com',
];
$parts = parse_url($resumeUrl);
if (empty($parts['scheme']) || empty($parts['host']) || !in_array($parts['host'], $allowedHosts, true)) {
  bad_request('Blocked resume host', 400);
}

// --- 3) Decide how to serve: PDF → stream; DOC/DOCX/RTF → Office viewer ---
$path = $parts['path'] ?? '';
$ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

// If extension isn’t reliable, you can HEAD the URL to check Content-Type.
// (We’ll first branch on extension; if unknown we default to stream as-is.)
$wordLikeExt = in_array($ext, ['doc', 'docx', 'rtf'], true);

if ($wordLikeExt) {
  // Render via Office Online viewer so it displays in the iframe
 // $viewer = 'https://docs.google.com/gview?embedded=1&url=' . rawurlencode($resumeUrl);

  $viewer = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($resumeUrl);
  header('Location: ' . $viewer, true, 302);
  exit;
}

// If it’s clearly a PDF (by extension), stream it. Otherwise, we can
// peek at Content-Type and decide; if it’s application/pdf, stream,
// else fall back to Office viewer if it’s a Word MIME type.
if ($ext !== 'pdf') {
  // HEAD to detect content type when extension is missing/unknown
  try {
    $head = $client->request('HEAD', $resumeUrl, [
      'http_errors' => false,
      'headers' => [
        'accept' => '*/*',
      ],
    ]);
    $ctype = strtolower($head->getHeaderLine('Content-Type'));
    if (strpos($ctype, 'application/pdf') !== false) {
      // ok, stream below as PDF
    } elseif (
      strpos($ctype, 'application/msword') !== false ||
      strpos($ctype, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') !== false ||
      strpos($ctype, 'application/rtf') !== false ||
      strpos($ctype, 'text/rtf') !== false
    ) {
      $viewer = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($resumeUrl);
      header('Location: ' . $viewer, true, 302);
      exit;
    }
    // Otherwise fall through and stream with whatever type the origin returns.
    // (Browser may download if not renderable in an iframe.)
  } catch (RequestException $e) {
    // If HEAD fails, we’ll try to stream and let the downstream Content-Type decide.
  }
}

// --- 4) Stream the upstream response (PDF or otherwise) ---
try {
  $forwardHeaders = [];
  // Forward Range to support partial content for large PDFs
  if (!empty($_SERVER['HTTP_RANGE'])) {
    $forwardHeaders['Range'] = $_SERVER['HTTP_RANGE'];
  }

  $upstream = $client->request('GET', $resumeUrl, [
    'stream'  => true,
    'headers' => $forwardHeaders,
  ]);
} catch (RequestException $e) {
  $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 502;
  bad_request('Failed to fetch resume content', $status);
}

// --- 5) Mirror relevant headers and body ---
http_response_code($upstream->getStatusCode());

// Prefer upstream Content-Disposition if present, otherwise inline with filename
$cd = $upstream->getHeaderLine('Content-Disposition');
if ($cd) {
  header('Content-Disposition: ' . $cd);
} else {
  $filename = 'resume';
  if (!empty($parts['path'])) {
    $base = basename($parts['path']);
    if ($base) $filename = $base;
  }
  header('Content-Disposition: inline; filename="' . addslashes($filename) . '"');
}

// Content-Type from upstream (fallbacks to application/octet-stream)
$contentType = $upstream->getHeaderLine('Content-Type') ?: 'application/octet-stream';
header('Content-Type: ' . $contentType);

// Pass through length/range/cache headers
foreach (['Content-Length','Content-Range','Accept-Ranges','Cache-Control','Last-Modified','ETag'] as $h) {
  $v = $upstream->getHeaderLine($h);
  if ($v !== '') header($h . ': ' . $v);
}
if ($upstream->hasHeader('Accept-Ranges') === false) {
  header('Accept-Ranges: bytes');
}

// Stream body to client
$body = $upstream->getBody();
while (!$body->eof()) {
  echo $body->read(8192);
  flush();
}
exit;
