<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$mailserver = "{imap.ionos.com:993/imap/ssl}INBOX";
$mailuser   = "text@icreativesstaffing.com";
$mailpass   = "CallowayCab!";

$mbox = imap_open($mailserver, $mailuser, $mailpass);

if (!$mbox) {
    echo "IMAP failed:<br>";
    echo imap_last_error();
    echo "<pre>";
    print_r(imap_errors());
    echo "</pre>";
    exit;
}

echo "Connected successfully.";
imap_close($mbox);