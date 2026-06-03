<?php
 session_start();

 $contactID = $_SESSION['contactID'];
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

/*


$query = "SELECT contact_email from ic_password_reset_tickets where token = '".$_REQUEST['validator']."'";

$result = mysqli_query($link,$query);

if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_array($result);
	$user = $row['contact_email'];
	$query = "SELECT email from ic_candidate_open_jobs where email = '".$user."'";

*/

$query = "SELECT candidate_email from ic_matches where candidate_email = '".$user."';";
$result = mysqli_query($link,$query);
if (mysqli_num_rows($result) > 0) {
?>


<div class="container custom">
<?php  if($_REQUEST['r'] !== 'success') { ?>

  <div class="row justify-content-center">
    <form class="form" id="reset" action="/portal/customer/manatal_send_talent_reset.php" method="post">
      <h1 class="form-heading">Create New Password</h1><hr/>
      <div class="form-group">
        <label for="email">Please enter your email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($user)) echo $user ?>" placeholder="email" required>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col">
            <button type="button" class="btn btn-primary" onclick="document.location.assign('/portal/manatal_talent_portal_signin.php<?php if(isset($_REQUEST['user'])) echo "/?user=".$_REQUEST['user'] ?>')">Back to Login</button>
          </div>
          <div class="col text-right">
            <button type="submit" class="btn btn-primary">Create Password</button>
          </div>
        </div>
      </div>
<?php } ?> 
      <div id="message">
        <?php
        if(isset($_REQUEST['r'])) {
          if($_REQUEST['r'] == 'success') {
            echo '<div class="alert alert-danger">Reset email successfully sent! Please check your inbox and spam folder.</div>';
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
	
echo "<center><h4>Hmmm, there seems to be a problem. <p><p>
Are you trying to enter hours?<p>
Please call us at 1.888.i.create or 954.468.5550</h4></center>";

}
// }
?>
</body>
</html>