 <?php
 
 $link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

if (!$link) {
    die('Connection failed: ' . mysqli_connect_error());
}

 
 function encrypt_string($plaintext) {

$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";

// $key = 'YOUR_SALT_KEY'; // Previously generated safely, ie: openssl_random_pseudo_bytes 
 //$plaintext = "String to be encrypted"; 
 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
 
// Encrypted string 
return base64_encode($iv.$hmac.$ciphertext_raw);
}
?>
<form method="post">
 Enter Email: <input type = "email" name = "email">
 Enter Password: <input type = "text" name = "pass">
 <input type = "submit" >
 </form>
 <?php
 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$password = encrypt_string($_REQUEST['pass']);
	$query = "UPDATE ic_sales SET password = '". $password ."' WHERE email = '". $_REQUEST['email']. "'";
	echo $query;
	// exit();
	$result = mysqli_query($link,$query);
    if (!$result) {
        die('Query failed: ' . mysqli_error($link));
    }
}
	?>
	
	DONE
	