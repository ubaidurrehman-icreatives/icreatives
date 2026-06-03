<?php
// Debug: Show cookie values
// Debug: Show session and cookie details
echo "<pre>";
echo "Session Variables:\n";
print_r($_SESSION);
echo "Cookies:\n";
print_r($_COOKIE);
echo "</pre>";

echo "XXX".$_COOKIE['client_portal'];

echo "<P />Expiration Time: ".$cookie_expiration_time;
echo "<P />Persistant Time: ".$persisted_cookie_expiration_time;

?>