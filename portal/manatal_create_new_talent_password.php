<?php
 session_start();

 // require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();   
?>
 <html>
<head>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">
  <style>
  /* used only in rate-candidate for similar width buttons */
  .custom button.similar {
    width: 200px;
  }
  </style>
  <style>
        #resumeContainer {
            width: 100%;
            height: 875px; /* Set the desired height */
            overflow: hidden;
        }

        #resumeFrame {
            width: 100%;
            height: 100%;
            border: none; /* Remove iframe border */
        }
    </style>


</head>
<body>
<?php
// show errors ASAP (helps while fixing 500s)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$user = $_SESSION['user'] ?? $_REQUEST['user'];
$query = "SELECT candidate_email,candidate from ic_matches where candidate_email = '".$user."';";
$result = mysqli_query($link,$query);
if (mysqli_num_rows($result) > 0) {
?>


<div class="container custom">


<?php  if(!isset($_REQUEST['r']) || $_REQUEST['r'] !== 'success') { ?>
<div style="padding-top: 50px;"> </div>
  <div class="row justify-content-center">
    <form class="form" id="reset" action="/portal/manatal_send_talent_reset.php" method="post">
      <h1 class="form-heading">Create New Password</h1><hr/>
      <div class="form-group">
        <label for="email">Please enter your email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($user)) echo $user ?>" placeholder="email" required>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col">
			<button type="button" class="btn btn-primary"
				onclick="window.location.href='/portal/manatal_servicelogin.php'">
				Back to Login
			</button>          </div>
          <div class="col text-right" style="width:275px;">
            <button type="submit" class="btn btn-primary">Create Password</button>
          </div>
        </div>
      </div>
<?php } ?> 
      <div id="message">
        <?php
        if(isset($_REQUEST['r'])) {
          if($_REQUEST['r'] == 'success') {
			echo '<div style="padding-top: 50px;"> </div>';
            echo '<div><h1><span style="color:#b22625;">Reset email successfully sent! <br>Please check your inbox and spam folder.</span></h1></div>';
          } else if($_REQUEST['r'] == 'not found') {
            echo '<div class="alert alert-danger">Email not found</div>';
          } else {
            echo '<div class="alert alert-danger">Something went wrong. Reset email was not sent.</div>';
          }
        }
        ?>
      </div>
    </form>
  </div>
</div>
<?php
} else {
	
echo "
<div style='padding-top: 50px;'> </div>
<center><h4>Hmmm, there seems to be a problem. <p><p>
Are you trying to enter hours?<p>
Please call us at 1.888.i.create or 954.468.5550</h4></center>";

}
// }
?>
</body>
</html>
