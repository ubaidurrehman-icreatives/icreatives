<!DOCTYPE html>
<?php
session_start();
$_SESSION['user'] = $_REQUEST['user'];
// echo $_SESSION['portal_url'];

?>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
<link rel="stylesheet" href="/portal/styles.css">

<div class="container custom" >
  <div class="row my-5">
    <div class="col">
      <h1>Welcome, <?php echo $_SESSION['first_name'] ?>!</h1>
      <input type="hidden" name="user" value="<?php echo $_REQUEST['user']; ?>"></button>
      <p><strong>Looks like you are new to this system.</strong></p>
      <p>
        Before you get started, we need to do just a bit of setup.<br>
        We sent you an email to create a password, which should arrive in your inbox shortly.
      </p>
      <p><strong>Please check your inbox</strong> (or spam/junk folders)</p>
      <p>If the emailed link cannot be found, please contact your icreatives representative.</p>
      <p>Also please ask your IT department to <strong>place "*.icreatives.com" on their email server white list</strong></p>
    </div>
  </div>
</div>

<?php include './portal/customer/manatal_email_new_contact.php'; ?>



