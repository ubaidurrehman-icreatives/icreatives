<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Talent Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <?php require_once dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/portal/styles.css">

  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #fff;
      font-family: Arial, Helvetica, sans-serif;
    }

    .login-wrap {
      margin-top: 3vh; /* near top */
      padding: 20px;
      text-align: center;
    }

    .brand-title {
      color: #b22625;
      font-weight: 600;
      margin-bottom: 1.5rem;
      font-size: 4.0rem;
      line-height: 1.1;
    }

    .form-login {
      max-width: 400px;
      margin: 0 auto;
      text-align: left;
    }

    /* icreatives red buttons/links */
    .btn-primary { background-color: #b22625; border-color: #b22625; }
    .btn-primary:hover,
    .btn-primary:focus { background-color: #8e1e1e; border-color: #8e1e1e; }
    a, .btn-link { color: #b22625; }
    a:hover, .btn-link:hover { color: #8e1e1e; text-decoration: underline; }

    /* --- Remove blue flash/glow on buttons & inputs --- */
    .btn,
    a.btn,
    button.btn,
    input[type="submit"].btn { -webkit-tap-highlight-color: transparent; }

    .btn:focus,
    .btn:active,
    .btn:active:focus,
    .btn:focus-visible,
    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary:active:focus,
    .btn-primary:focus-visible,
    a.btn:focus,
    a.btn:active,
    a.btn:active:focus,
    a.btn:focus-visible,
    button.btn:focus,
    button.btn:active,
    button.btn:active:focus,
    button.btn:focus-visible,
    input[type="submit"].btn:focus,
    input[type="submit"].btn:active,
    input[type="submit"].btn:active:focus,
    input[type="submit"].btn:focus-visible {
      box-shadow: none !important;
      outline: none !important;
      outline-color: transparent !important;
    }

    /* iOS/Safari tap highlight (aggressive) */
    * { -webkit-tap-highlight-color: rgba(0,0,0,0); }

    /* Neutral, no-glow input focus */
    .form-control:focus,
    input:focus,
    select:focus,
    textarea:focus {
      border-color: #555 !important;
      box-shadow: none !important;
      outline: none !important;
    }

    /* Mobile tweaks only (desktop unchanged) */
    @media (max-width: 576px) {
      .brand-title { font-size: 2rem; }
      .btn { width: 100%; margin-bottom: 0.5rem; }
      .form-control { font-size: 1.05rem; }
    }
	#login_button {
  border-radius: 0 !important;
}

  </style>
</head>
<body>

  <div class="login-wrap" style="padding-top:150px;">
    <h1 class="brand-title">Talent Login</h1>

    <form class="form-login" id="login" action="/portal/manatal_auth_talent.php" method="post">
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
            // Prefill & lock if session user; do NOT autofocus (we want password focused)
            if (isset($_SESSION['user']) && $_SESSION['user'] !== "") {
              echo 'value="'.htmlspecialchars($_SESSION['user'], ENT_QUOTES, "UTF-8").'" readonly';
            }
          ?>>
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
		  required 
          autofocus>
      </div>

      <div class="form-group d-flex flex-column flex-sm-row justify-content-between align-items-stretch">
        <button
          type="button"
          id="forgot"
          class="btn btn-link p-0 order-2 order-sm-1"
          onclick="window.location.assign('/portal/manatal_create_new_talent_password.php')">
          Create/Change Password
        </button>
        <input type="submit" id="login_button" class="btn btn-primary btn-lg order-1 order-sm-2" value="Sign In">
      </div>

      <div class="error">
        <?php
          if (isset($_REQUEST['r'])) {
            $msg = 'Something went wrong, please contact your agent.';
            if ($_REQUEST['r'] === 'fields') {
              $msg = 'Username or password not entered correctly.';
            } else if ($_REQUEST['r'] === 'cred') {
              $msg = 'Username or password incorrect.';
            }
            echo '<div class="alert alert-danger mb-0" role="alert">'.htmlspecialchars($msg, ENT_QUOTES, "UTF-8").'</div>';
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
    });

    // On load: scroll parent to top if inside iframe; ensure password gets focus
    window.addEventListener('load', function () {
      if (window.parent && window.parent !== window) {
        try { window.parent.scrollTo(0, 0); } catch (e) {}
      } else {
        window.scrollTo(0, 0);
      }
      var pwd = document.getElementById('password');
      if (pwd) { try { pwd.focus(); } catch (e) {} }
    });
  </script>
</body>
</html>
