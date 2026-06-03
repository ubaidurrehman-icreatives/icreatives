<?php 
session_start();


 $contactID = $_SESSION['contactID'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
 ?>
 <html>
<head>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
  </head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">
  
<div style="padding-top: 40px;"> </div>
  <div class="container custom" >
<?php
require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();   

if(!isset($_REQUEST['selector']) || !isset($_REQUEST['validator'])) { ?>
    <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
    <link rel="stylesheet" href="/portal/styles.css">
    <div class="container custom">
        <h1>Whoops</h1>
        <p>There was an error processing your request. Your password reset ticket may have expired. Re-enter your email in the login page <a href="https://www.icreatives.com/port/">here</a> or at the link below to request a new ticket.</p>
        <p><a href="https://www.icreatives.com/port/">https://www.icreatives.com/port/</a></p>
    </div>
    <?php
    return;
}

// Check for tokens
$selector = filter_input(INPUT_GET, 'selector');
$validator = filter_input(INPUT_GET, 'validator');
#arr_selector = array($selector);
if ( ctype_xdigit( $selector ) && ctype_xdigit( $validator ) ) :
    // Get unexpired token with selector
    $query = "SELECT * FROM ic_password_reset_tickets WHERE selector = '". $selector. "' AND expires_at >= '". date('Y-m-d H:i:s') ."' AND closed = 0";	


	$result = mysqli_query($link,$query );
	
	$count = mysqli_num_rows($result);

    // Invalid selector
    if($count == 0) { ?>
        <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
        <link rel="stylesheet" href="/portal/styles.css">
        <div class="container custom">
            <h1>Woops</h1>
            <p>There was an error processing your request. Your password reset ticket may have expired. Re-enter your email in the login page <a href="/portals">here</a> or at the link below to request a new ticket.</p>
            <p><a href="/portals/">https://www.icreatives.com/portals</a></p>
        </div>
        <?php
        return;
    }
    ?>
    <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
    <link rel="stylesheet" href="/portal/styles.css">

    <div class="container custom">
        <div class="row justify-content-center">
            <form action="/portal/manatal_client_process_create.php" class="form-login" id="form" method="post">
                <h1 class="form-login-heading">Create Password</h1>
                <p>Please enter a password 6 characters or more.</p>
                <p>Your password must contain a combination of<br>UPPER CASE, lower case, and either $pecial or num3ric characters.</p>
                <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                <input type="hidden" name="validator" value="<?php echo $validator; ?>">
                <div class="form-group">
                    <input type="password" class="form-control" id="pass" name="password" placeholder="New password" pattern="(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}" required autofocus>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="validate" name="confirm" placeholder="Validate password" pattern="(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}" required>
                </div>
                <div class="form-group">
                    <button type="button" id="submit" class="submit btn btn-primary">Submit</button>
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

                <!-- Terms and Conditions Dialogue Box -->
                <div id="agreeDialogue" class="modal">
                    <form id="agree" action="">
                        <!-- Change width attribute to change width of terms and conditions -->
                        <div class="container custom modal-content" style="width: 20% !important">
                            <div class="row">
                                <div class="col-10">
                                    <span class="float-left"><h1>Terms and Conditions</h1></span>
                                </div>
                                <div class="col-2">
                                    <span class="hide_agree close float-md-right" id="close_agree_dialogue">&times;</span>
                                </div>
                            </div>
                            <div class="form-group" style="height: 250px; overflow: scroll">
                              <span>
                                <?php
                                include './portal/t_and_c.txt';
                                ?>
                              </span>
                            </div>
                            <div class="form-group">
                                <button type="button" id="disagree" class="hide_agree btn btn-secondary" id="share_submit">Disagree</button>
                                <button type="submit" class="btn btn-primary" id="share_submit">Agree</button>
								<button type="button" id="disagree" class="hide_agree btn btn-secondary" id="share_submit">Go Back</button>
 
                            </div>
                        </div>
                    </form>
                </div>
            </form>
        </div>
    </div>
    <script src="/portal/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js"></script>
    <script>
    var pass = document.getElementById('pass');
    var validator = document.getElementById('validate');
    var agree = document.getElementById('agreeDialogue');

    // When the user clicks submit button, open the modal display
    Array.prototype.forEach.call(document.getElementsByClassName('submit'), function(e) {
      e.addEventListener('click', function() {
        agree.style.display = "block";
      }, false);
    });

    Array.prototype.forEach.call(document.getElementsByClassName('hide_agree'), function(e) {
      e.addEventListener('click', function() {
        agree.style.display = "none";
      }, false);
    });

    window.onclick = function(e) {
      if (e.target == agree) {
        agree.style.display = "none";
      }
    }

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
        agree.style.display = "none";
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
</body>
</html>
