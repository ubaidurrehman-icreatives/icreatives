<?php
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.manatal.com/open/v3/contacts/', [
  'body' => '{"custom_fields":{"test":true},"full_name":"test name","display_name":"test name","organization":2684158}',
  'headers' => [
    'Authorization' => 'Token 71f589faea3a21564cd8e2ed4c6d81739cb36796',
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
]);


echo $response->getBody();

?>
