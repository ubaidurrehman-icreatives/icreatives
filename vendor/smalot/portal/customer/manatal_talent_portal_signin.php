<?php session_start();
?>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


  <div class="container custom" >
   <!-- <div class="row justify-content-center vertical-center">
   	  <div style="float:left; width:100%; font-align:center; padding:10px 0 0px 0px; color:#B22625;  font-size:30px;"><b>Passwords have been recently been reset <br>for dormant accounts</b></div>
 -->
  <div class="row justify-content-center" style="padding:50px 0 100px 0;">
  

      <form class="form-login" id="login" action="/portal/customer/manatal_auth_talent.php" method="post">
	<div style="float:left; width:300px;  padding:10px 0 40px 0px; color:#B22625; font-size:60px;"><b>Talent </b></div>
	<div style="clear:left; width:300px;  padding:10px 0 40px 0px; color:#B22625; font-size:60px;"><b>Login: </b></div>
 
        <!-- <h1 class="form-login-heading">Talent Login</h1> -->
        <div class="form-group">
          <input type="text" class="form-control" id="userID" name="user" placeholder="email" <?php
            /*
              if user is given, prefill and set to read only
              otherwise, set to autofocus
            */
            echo isset($_REQUEST['user']) && $_REQUEST['user'] !== "" ? 'value="'.$_REQUEST['user'].'" readonly' : "autofocus" ?>>
        </div>
        <div class="form-group" style="width:35%;">
          <input type="password" class="form-control" id="password" name="password" placeholder="password" <?php
          // if user is given, set to autofocus
          if(isset($_REQUEST['user'])) echo "autofocus" ?>>
        </div>
		
		<div class="form-group text-right">
		<!--  onclick="window.location.assign('/forgot-talent-portal-Password -->
          <button type="button" id="forgot" class="btn btn-link" onclick="window.location.assign('/portal/customer/manatal_create_new_talent_password.php<?php
            if(isset($_REQUEST['user']) && $_REQUEST['user'] !== "") echo "/?user=".$_REQUEST['user']
          ?>')">Create/Change Password</button>

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
