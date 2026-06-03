<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION['recruiter_id'])) {
  session_regenerate_id();
  header("Location: /client-login");
  return;
}
?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS, custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/styles.css">

  <div class="custom">
    <form class="form-inline my-5" action="/portal/customer/manatal_auth_email.php?first=1" method="post">
      <div class="form-group">
        <label for="ContactEmail_Search" class="col-sm-4 col-form-label">Contact Email/ID</label>
        <div class="input-group col-sm-8">
          <input type="text" class="form-control" name="identifier" id="Contact_Search" autofocus>
          <div class="input-group-append">
            <input class="btn btn-outline-secondary" type="submit" value="Find">
          </div>
        </div>
      </div>
    </form>
  </div>
