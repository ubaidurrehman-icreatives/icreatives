<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
<link rel="stylesheet" href="/portal/styles.css">

<!-- Bootstrap JS -->
<script src="/portal/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>

<div class="container custom">
<?php  if($_REQUEST['r'] !== 'success') { ?>

  <div class="row justify-content-center">
    <form class="form" id="reset" action="/portal/customer/manatal_send_client_reset.php" method="post">
      <h1 class="form-heading">Create New Password</h1><hr/>
      <div class="form-group">
        <label for="email">Please enter your email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($_REQUEST['user'])) echo $_REQUEST['user'] ?>" placeholder="email" required>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col">
            <button type="button" class="btn btn-primary" onclick="document.location.assign('/portals<?php if(isset($_REQUEST['user'])) echo "/?user=".$_REQUEST['user'] ?>')">Back to Login</button>
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
            echo '<div class="alert alert-danger">Reset email successfully sent! Please check your inbox.</div>';
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

<script>

</script>
</html>
