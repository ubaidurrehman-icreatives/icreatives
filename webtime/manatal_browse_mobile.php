<?php 
/*
' Option Explicit
'**********************************************************
' To make this script work you simply need to create a
' table named tblLoginInfo in your database with one
' column named username and another named password.  Put
' the values you want for username and password into a
' record in the table.  The advantages of this script are
' that it's more secure than if you hard-coded the
' username/password values directly in the script, and
' that you can change the username and password simply by
' changing the values in your login_table.
'
' NOTE: BE SURE TO EITHER MOVE THE INCLUDED SAMPLE
'       DATABASE TO A SECURE AREA OUTSIDE THE WEB SITE OR
'       USE A DIFFERENT SECURE DATABASE.  OTHERWISE ANYONE
'       CAN SIMPLY DOWNLOAD THE WHOLE DB AND RETREIVE YOUR
'       USERNAME AND PASSWORD FROM IT.
'**********************************************************
*/

// Contractor_ID = "000044V7" or lisa is 000045V7
// web login thingie E405H07-000028-2008 lisa E45V709-000022-2008


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>i creative staffing login screen</title>
    <link href="/webtime/css/mobile/styles.css" rel="stylesheet" type="text/css" />
     <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />

        
    <link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />


    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
    


<style type="text/css"> 
body {background:#ffffff;} 
html { background: none !important; }
</style>
<script>
// Force the iframe to start at the top before rendering
  // Scroll both the iframe and the parent window
  window.scrollTo(0, 0);
  if (window.parent && window.parent !== window) {
    window.parent.scrollTo(0, 0);
  }

</script>

</head>
        
<!-- insert stuff here -->

<!-- Insert Code Here -->   
                            

<?php 

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

If ( $_REQUEST["action"] <> "validate_login" || trim($_REQUEST["password"]) == "" ) {
	
	include "manatal_login2_mobile.php";

} Else {
	
	// find candidate ID in matches
	$query = "select * from ic_matches where candidate_email  = '".$_REQUEST["login"]."'";
	$resMySel =  mysqli_query($link,$query);
	$row = mysqli_fetch_array($resMySel) ;
	$candidate_id = $row['candidate'];
	// find manatal password
	require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$candidate_id.'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

$response->getBody();

	$responseStr = $response->getBody();
	$candidate = json_decode($responseStr, true);	
	$cand_pass= $candidate['custom_fields']['password'];
	

	
	If (!empty($cand_pass) && $cand_pass == $_REQUEST['password']) {

	?>
		<p>
		
		
		
		<form name="myForm" action="manatal_browse_1_mobile.php"  method="post">

		<?php include "global2.php"; ?>
		
		<input type="hidden" name="Contractor_ID" value="<?php echo $candidate_id; ?>" onsubmit='window.parent.scroll(0,0);>

		</form> 

				<script LANGUAGE="JavaScript">

					document.myForm.submit();

				</script>
	<?php } Else { 

		include "manatal_login2_mobile.php"; ?>
	<div class="clear"/>

		<P class="lable_class">Login Failed - Please verify username and password.</p>

	<?php }
} ?>

<!-- end insert stuff here -->

</body>
</html>


