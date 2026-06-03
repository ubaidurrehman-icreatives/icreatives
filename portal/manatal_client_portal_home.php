<?php
session_start();
// echo "c name = " . $_SESSION['company_name']."<br>";
$company_id=$_REQUEST['company_id'];
//		$open_arr = $_SESSION['open_arr'];
//		var_dump($open_arr);
//		exit();
if(!isset($_SESSION['user']) && !isset($_SESSION['user'])) {

  header("Location: /portal/manatal_servicelogin.php/");
}

?>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/styles.css">

  <div class="container custom" >
   <!-- <div class="row justify-content-center vertical-center"> -->
  <div class="row justify-content-center" style="padding:100px 0 100px 0;">
<!--  <form class="form-login" id="login" action="/portal/customer/trms_authenticate.php" method="post" novalidate>
-->
  <form class="form-login" id="login" action="/client-portal-authenticate" method="post" novalidate>
	<div style="float:left; width:300px;  padding:10px 0 40px 0px; color:#B22625; font-size:60px;"><b>Client </b></div>
	<div style="clear:left; width:300px;  padding:10px 0 40px 0px; color:#B22625; font-size:60px;"><b>Login: </b></div>
        <!-- <h1 class="form-login-heading">Client Login</h1> -->
        <input type="hidden" name="orderID" value="<?php if(isset($_REQUEST['o'])) echo $_REQUEST['o'] ?>">
        <input type="hidden" name="company_id" value="<?php echo $company_id ?>">
		 <input type="hidden" name="company_name" value="<?php echo $_SESSION['company_name'] ?>">
        <div class="form-group">
          <input type="email" class="form-control novalidate" id="userID" name="user" placeholder="email" <?php
            /*
              if user is given, prefill and set to read only
              else if cookie exists, prefill to client_login
              otherwise, set to autofocus
            */
            if(isset($_SESSION['user']) && $_SESSION['user'] !== "") {
              echo 'value="'.$_SESSION['user'].'" readonly';
            } else if(isset($_COOKIE['client_login'])) {
              echo 'value="'.$_SESSION['user'].'"';
            } else {
              echo "autofocus";
            } ?>>
        </div>
        <div class="form-group">
          <input type="password" class="form-control novalidate" id="password" name="password" placeholder="password" 
         <?php // if user is given, set to autofocus
          if(isset($_SESSION['user']) || isset($_COOKIE['client_login'])) echo " autofocus" ?>>
        </div>
              <div class="form-group text-right">
          <button type="button" id="forgot" class="btn btn-link" onclick="window.location.assign('/manatal_create_client_password.php<?php
            if(isset($_SESSION['user']) && $_SESSION['user'] !== "") $_SESSION['user'] = $_SESSION['user']
          ?>')">Forgot Password</button>
          <input type="submit" id="login_button" class="btn btn-primary" value="Sign In">
        </div>
        <div class="error">
          <?php
          if(isset($_REQUEST['r'])) {
            if($_REQUEST['r'] == 'fields') {
              echo '<div class="alert alert-danger">Username or password not entered</div>';
            } else if($_REQUEST['r'] == 'cred') {
              echo '<div class="alert alert-danger">Username or password incorrect</div>';
            } else {
              echo '<div class="alert alert-danger">Something went wrong</div>';
            }
          }
          ?>
        </div>
      </form>
    </div>
  </div>
<script>
document.getElementById('login').addEventListener('submit', function(e) {
  var login = document.getElementById('login_button');
  login.disabled = true;
  login.value = "Please wait...";
}, false);

</script>
