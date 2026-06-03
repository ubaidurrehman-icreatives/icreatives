<!DOCTYPE html>
<?php session_start() ?>

<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
<link rel="stylesheet" href="/portal/styles.css">

<div class="container custom">
  <!-- <div class="row justify-content-center vertical-center"> -->
  <div class="row justify-content-center" style="padding:100px 0 100px 0;">
  <?php 
  
              /*
              if user is given, prefill and set to read only
              else if cookie exists, prefill to client_login
              otherwise, set to autofocus
            */
			/*
            if(isset($_COOKIE['client_login']) && $_COOKIE['client_login'] !== "") {
			// take to client dashboard
			header("Location: /client-portal-login/?o=&user=".$_COOKIE['client_login']);
			exit;
			} else {
			*/
	?>
<!-- Start Form -->
  <form class="form-login" id="servicelogin" action="/portal/customer/manatal_process_email2.php" method="post" novalidate>

<div style="float:left;" class="btnbg">
<div style="float:left; width:300px;  padding:0 0 40px 0px; color:#B22625; font-size:60px;"><b> Sign In: </b></div>

</div>
 <!--     <h1 class="form-login-heading">Login</h1>
      <p>Enter your account email</p>  -->
      <input type="hidden" name="orderID" value="<?php if(isset($_REQUEST['o'])) echo $_REQUEST['o'] ?>">
      <div class="form-group">
        <input type="text" inputmode="email" class="form-control" id="userID" name="user" placeholder="email" value="<?php if(!empty($_COOKIE['client_login'])) echo $_COOKIE['client_login'] ?>" autofocus>
      </div>
      <div class="message">
        <?php
        // display error messages
        if(isset($_REQUEST['r'])) {
          if($_REQUEST['r'] == 'fields') {
            echo '<div class="alert alert-danger">Email not entered</div>';
          } else if($_REQUEST['r'] == 'recognize') {
            echo '<div class="alert alert-danger">Email not recognized</div>';
          } else {
            echo '<div class="alert alert-danger">Something went wrong. Please enter your email again. If the problem persists, try a different browser or contact icreatives.</div>';
          }
        }
        ?>
      </div>
      <div class="text-right">
        <input type="submit" id="submit" class="btn btn-primary" value="next">
      </div>
    </form>
	<!-- end form -->
	<?php // } ?>
  </div>
</div>
<script>
document.getElementById('servicelogin').addEventListener('submit', function(e) {
  var submit = document.getElementById('submit');
  submit.disabled = true;
  submit.value = "Please wait...";
}, false);

var input = document.getElementById('userID');
var len = input.value.length;
input.focus();
input.setSelectionRange(len, len);
</script>
