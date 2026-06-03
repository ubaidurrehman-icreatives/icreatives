<?php

if(!isset($_REQUEST['selector']) || !isset($_REQUEST['validator'])) { ?>
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/styles.css">
  <div class="container custom">
    <h1>Error</h1>
    <p>There was an error processing your request. Your password reset ticket may have expired. You can request a new reset ticket <a href="/forgot-password">here</a></p>
  </div>
  <?php
  return;
}


$selector = filter_input(INPUT_GET, 'selector');
$validator = filter_input(INPUT_GET, 'validator');

$user = $_REQUEST['user'];
$link = mysqli_connect('localhost', 'TempBack', 'XE5Vx@54Pu1IRQXa','tempback') or die("Error: " . mysqli_error());

// Check for tokens

if ( ctype_xdigit( $selector ) && ctype_xdigit( $validator ) ) :

	// Get unexpired token with selector
	$query = "SELECT ID, contact_email from ic_password_reset_tickets where selector = '". $selector ."' AND expires_at >= NOW() AND closed = 0";
	$result = mysqli_query($link,$query);

  // Invalid selector
  if(mysqli_num_rows($result) == 0) {?>
    <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
    <link rel="stylesheet" href="/portal/styles.css">
    <div class="container custom">
      <h1>Error</h1>
      <p>There was an error processing your request. Your password reset ticket may have expired. You can request a new reset ticket after re-entering your email address <a href="/login.php">here</a></p>
    </div>
    <?php
    return;
  }
 	$row = mysqli_fetch_array($result);
	$email = $row['contact_email'];
?>
<link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
<link rel="stylesheet" href="/portal/styles.css">

  <div class="container custom">
    <div class="row justify-content-center">
      <form action="/portal/customer/manatal_talent_process_portal_new.php" class="form-login" id="form" method="post">
        <h1 class="form-login-heading">Reset Password</h1>
        <p>Please enter a password 6 characters or more.</p>
        <p>Your password must contain a combination of<br>UPPER CASE, lower case, and either $pecial or num3ric characters.</p>
        <input type="hidden" name="selector" value="<?php echo $selector; ?>">
        <input type="hidden" name="validator" value="<?php echo $validator; ?>">
		<input type="hidden" name="email" value="<?php echo $validator; ?>">
        <div class="form-group">
          <input type="password" class="form-control" id="pass" name="password" placeholder="New password" pattern="(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}" required autofocus>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="validate" name="confirm" placeholder="Validate password" pattern="(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}" required>
          <div class="invalid-feedback">
          </div>
        </div>
        <div class="form-group text-right">
          <input type="submit" id="reset" class="btn btn-primary" value="Submit">
          <p><a href="/login.php">Login here</a></p>
        </div>
        <div id="message">
          <?php
          // display error messages
          if(isset($_REQUEST['r'])) {
            if($_REQUEST['r'] == 'f') {
              echo '<div class="alert alert-danger">A field was not entered</div>';
            } else if($_REQUEST['r'] == 'm') {
              echo '<div class="alert alert-danger">Passwords do not match</div>';
            } else if($_REQUEST['r'] == 'r') {
              echo '<div class="alert alert-danger">Password must be at least 6 characters long</div>';
            } else {
              echo '<div class="alert alert-danger">Something went wrong. Please try again. If the problem persists, try a different browser or contact icreatives.</div>';
            }
          }
          ?>
        </div>
      </form>
    </div>
  </div>
  <script src="/portal/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js"></script>
  <script>
  var pass = document.getElementById('pass');
  var validator = document.getElementById('validate');

  Array.prototype.forEach.call(document.getElementsByTagName('input'), function(e) {
    if(e === validator) {
      e.addEventListener('input', function() {
        e.classList.remove("clean");
        e.classList.add("dirty");
        if(e.value === pass.value) {
          e.setCustomValidity("");
        } else {
          e.setCustomValidity("Passwords do not match");
        }
      });
    } else {
      e.addEventListener('input', function() {
        e.classList.remove("clean");
        e.classList.add("dirty");
        if(e.value === validator.value) {
          validator.setCustomValidity("");
        } else {
          validator.setCustomValidity("Passwords do not match");
        }
      });
    }
  });

  document.getElementById('form').addEventListener('submit', function(e) {
    if(pass.value != validator.value) {
      e.preventDefault();
      pass.setCustomValidity('Passwords do not match');
    } else {
      var reset = document.getElementById('reset');
      reset.disabled = true;
      reset.value = "Please wait...";
    }
  }, false);
  </script>
<?php endif; ?>
