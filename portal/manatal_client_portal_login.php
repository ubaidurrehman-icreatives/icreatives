<?php
session_start();

$company_id = $_REQUEST['company_id'] ?? '';

if (!isset($_SESSION['user'])) {
  header("Location: /portal/manatal_servicelogin.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Client Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <?php require_once dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/portal/styles.css">

  <style>
    body {
      margin: 0;
      padding: 0;
      background: #fff;
      font-family: Arial, Helvetica, sans-serif;
    }

    .login-wrap {
      margin-top: 2vh;
      padding: 20px;
      text-align: center;
    }

    /* Bigger headline */
    .brand-title {
      color: #b22625;
      font-weight: 600;
      margin-bottom: 2rem;
      font-size: 4.0rem;  /* increased from 2.6rem */
      line-height: 1.1;
      letter-spacing: -0.5px;
    }

    .form-login {
      max-width: 420px;
      margin: 0 auto;
      text-align: left;
    }

    /* ---- icreatives Red Theme ---- */
    .btn-primary {
      background-color: #b22625;
      border-color: #b22625;
    }
    .btn-primary:hover,
    .btn-primary:focus {
      background-color: #8e1e1e;
      border-color: #8e1e1e;
    }
    a, .btn-link {
      color: #b22625;
    }
    a:hover, .btn-link:hover {
      color: #8e1e1e;
      text-decoration: underline;
    }

    /* Neutral dark-grey border when focused */
    .form-control:focus {
      border-color: #555555;
      box-shadow: none;
      outline: none;
    }

    @media (max-width: 576px) {
      .brand-title {
        font-size: 2.4rem;   /* still large but fits on small screens */
      }
      .btn {
        width: 100%;
        margin-bottom: .5rem;
      }
    }
	#login_button {
  border-radius: 0 !important;
}

  </style>
</head>
<body>

  <div class="login-wrap" style="padding-top: 150px;">
    <h1 class="brand-title">Client Login</h1>

    <form class="form-login" id="login" action="/portal/manatal_client_portal_authenticate.php" method="post" novalidate>
      <input type="hidden" name="orderID" value="<?php echo isset($_REQUEST['o']) ? htmlspecialchars($_REQUEST['o'], ENT_QUOTES, 'UTF-8') : ''; ?>">
      <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company_id, ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="company_name" value="<?php echo htmlspecialchars($_SESSION['company_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

      <div class="form-group">
        <label for="userID" class="sr-only">Email</label>
        <input
          type="email"
          class="form-control form-control-lg"
          id="userID"
          name="user"
          placeholder="Email"
          inputmode="email"
          autocomplete="username"
          spellcheck="false"
          <?php
            if (!empty($_SESSION['user'])) {
              echo 'value="'.htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8').'" readonly';
            } elseif (!empty($_COOKIE['client_login'])) {
              echo 'value="'.htmlspecialchars($_COOKIE['client_login'], ENT_QUOTES, 'UTF-8').'"';
            }
          ?>
        >
      </div>

      <div class="form-group">
        <label for="password" class="sr-only">Password</label>
        <input
          type="password"
          class="form-control form-control-lg"
          id="password"
          name="password"
          placeholder="Password"
          autocomplete="current-password"
          autofocus>
      </div>

      <div class="form-group d-flex flex-column flex-sm-row justify-content-between align-items-stretch">
        <button
          type="button"
          id="forgot"
          class="btn btn-link p-0 order-2 order-sm-1"
          onclick="window.location.assign('/portal/manatal_send_client_reset.php')">
          Create/Reset Password
        </button>
        <input type="submit" id="login_button" class="btn btn-primary btn-lg order-1 order-sm-2" value="Sign In">
      </div>

      <div class="error">
        <?php
          if (isset($_REQUEST['r'])) {
            $msg = 'Something went wrong';
            if ($_REQUEST['r'] === 'fields') {
              $msg = 'Username or password not entered';
            } elseif ($_REQUEST['r'] === 'cred') {
              $msg = 'Username or password incorrect';
            }
            echo '<div class="alert alert-danger mb-0" role="alert">'.htmlspecialchars($msg, ENT_QUOTES, 'UTF-8').'</div>';
          }
        ?>
      </div>
    </form>
  </div>

  <script>
    // Prevent double submits
    document.getElementById('login').addEventListener('submit', function () {
      var btn = document.getElementById('login_button');
      btn.disabled = true;
      btn.value = 'Please wait…';
    }, false);

    // Scroll parent (iframe container) to top and focus password on load
    window.addEventListener('load', function () {
      if (window.parent && window.parent !== window) {
        try { window.parent.scrollTo(0, 0); } catch (e) {}
      } else {
        window.scrollTo(0, 0);
      }
      document.getElementById('password').focus();
    });
  </script>
</body>
</html>
