<html>
<head>
</head>
<body>
<form name = "form1" method="post">
email: <input type="email" name = "email" id = "email" >
<p>
password: <input type = "password" name = "password" >
<input type="submit" value="Submit">
</form>
<?php 

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

$plaintext = $_REQUEST['password'];

$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";

// $key = 'YOUR_SALT_KEY'; // Previously generated safely, ie: openssl_random_pseudo_bytes 
 //$plaintext = "String to be encrypted"; 
 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
 
// Encrypted string 

$epass =  base64_encode($iv.$hmac.$ciphertext_raw);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


// Get unexpired token with selector
$query = "SELECT  *
          FROM ic_sales
          WHERE email = '". $_REQUEST['email'] ."'";
		  
$result = mysqli_query($link,$query );		  
	
$count = mysqli_num_rows($result); 

$query = "UPDATE ic_sales set password = '$epass' where email = '".$_REQUEST['email']."';";
$result = mysqli_query($link,$query ); 
}
 ?>
</body>
</html>