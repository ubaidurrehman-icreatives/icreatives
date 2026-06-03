<!DOCTYPE html>
<html>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min1.css">
  <link rel="stylesheet" href="login_styles.css">

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="../bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

  <script>
  $(document).ready(function() {
    $('#login').submit(function(e) {
      e.preventDefault();
      var userID = $("#userID").val();
      var password = $('#password').val();
      $.ajax({  // get html for candidates
        type: 'POST',
        url: 'authenticate.php',
        data: {user: userID, password: password},
        dataType: 'text',
        success: function(response) {
          if(response=="success") {
            $("#login_button").html('Signing In...');
            window.location.assign('dash.php');
          } else {
            $("#error").fadeIn(1000, function(){
              $("#error").html('<div class="alert alert-danger">'+response+'!</div>');
              $("#login_button").html('Sign In');
            });
          }
        }
      });
    });
  });
  </script>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center vertical-center">
      <form class="form-login" id="login" method="post">
        <h2 class="form-login-heading">Recruiter Log In</h2><hr />
        <div class="form-group">
          <input type="text" class="form-control" id="userID" name="userID" placeholder="username">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="password" name="password" placeholder="password">
        </div>
        <button type="submit" id="login_button" class="btn btn-primary mb-1">Sign In</button>
        <div id="error"></div>
      </form>
    </div>
  </div>
</body>
</html>
