<!DOCTYPE html>
<?php session_start() ?>

<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

<script>
(function () {
  function isMobilePhone() {
    const ua = navigator.userAgent || navigator.vendor || window.opera;
    if (/Windows Phone/i.test(ua)) return true;
    if (/Android/i.test(ua) && /Mobile/i.test(ua)) return true;
    if (/iPhone|iPod/i.test(ua)) return true;
    if (/iPad/i.test(ua)) return false;
    if (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1) return false;
    const physicalCSSWidth = Math.min(screen.width, screen.height) / (window.devicePixelRatio || 1);
    return physicalCSSWidth <= 480;
  }
  // Set/update a cookie for PHP to read on next request
  document.cookie = "is_phone=" + (isMobilePhone() ? "1" : "0") + "; path=/; SameSite=Lax";
})();
</script>

</head>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
<link rel="stylesheet" href="/portal/styles.css">

<!-- Mobile tweaks + remove blue focus/flash (keeps desktop layout intact) -->
<style>
  /* --- Kill blue flash/glow on buttons & inputs --- */
  .btn, a.btn, button.btn, input[type="submit"].btn { -webkit-tap-highlight-color: transparent; }
  .btn:focus, .btn:active, .btn:active:focus, .btn:focus-visible,
  .btn-primary:focus, .btn-primary:active, .btn-primary:active:focus, .btn-primary:focus-visible,
  a.btn:focus, a.btn:active, a.btn:active:focus, a.btn:focus-visible,
  button.btn:focus, button.btn:active, button.btn:active:focus, button.btn:focus-visible,
  input[type="submit"].btn:focus, input[type="submit"].btn:active, input[type="submit"].btn:active:focus, input[type="submit"].btn:focus-visible {
    box-shadow: none !important;
    outline: none !important;
    outline-color: transparent !important;
  }
  * { -webkit-tap-highlight-color: rgba(0,0,0,0); }

  /* Neutral input focus (no glow/blue) */
  .form-control:focus,
  input:focus, select:focus, textarea:focus {
    border-color: #555 !important;
    box-shadow: none !important;
    outline: none !important;
  }

  /* --- Keep desktop layout; only adjust on phones --- */
  @media (max-width: 576px) {
    /* reduce vertical spacing */
    .custom .row {
      padding: 24px 0 48px 0 !important;
    }

    /* make "Sign In:" heading wrap properly */
    .btnbg > div {
      width: 100% !important;
      font-size: 2rem !important;
      padding: 0 0 16px 0 !important;
      text-align: center !important;
      float: none !important;
    }

    /* make form full-width with comfortable margins */
    .form-login {
      max-width: 100% !important;
      width: 100% !important;
      padding: 0 16px !important;
      margin: 0 auto !important;
    }

    /* make input (email) full width */
    .form-control {
      width: 100% !important;
      font-size: 1.1rem;
      min-width: 0; /* prevents shrinking */
      box-sizing: border-box;
    }

    /* buttons full width and nicely stacked */
    .text-right { text-align: left !important; }
    .btn {
      width: 100%;
      font-size: 1.1rem;
    }
  }
</style>


<div class="container custom" style="padding-top:100px;">
  <!-- <div class="row justify-content-center vertical-center"> -->
  <div class="row justify-content-center" style="padding:100px 0 100px 0;">
  <?php 
              /*
              if user is given, prefill and set to read only
              else if cookie exists, prefill to client_login
              otherwise, set to autofocus
            */
			/*
            if(isset($_COOKIE['client_login']) && $_COOKIE['client_login'] !== "") {
			// take to client dashboard
			header("Location: /client-portal-login/?o=&user=".$_COOKIE['client_login']);
			exit;
			} else {
			*/
	?>
<!-- Start Form -->
  <form class="form-login" id="servicelogin" action="/portal/manatal_process_email2.php" method="post" novalidate>

    <div style="float:left;" class="btnbg">
      <div style="float:left; width:300px;  padding:0 0 40px 0px; color:#B22625; font-size:60px;"><b> Sign In: </b></div>
    </div>

    <input type="hidden" name="orderID" value="<?php if(isset($_REQUEST['o'])) echo htmlspecialchars($_REQUEST['o'], ENT_QUOTES, 'UTF-8'); ?>">

    <div class="form-group">
      <input
        type="email"
        inputmode="email"
        class="form-control"
        id="userID"
        name="user"
        placeholder="email"
        value="<?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') : ''; ?>"
        autofocus>
    </div>

    <div class="message">
      <?php
        // display error messages
        if(isset($_REQUEST['r'])) {
          if($_REQUEST['r'] == 'fields') {
            echo '<div class="alert alert-danger">Email not entered</div>';
          } else if($_REQUEST['r'] == 'recognize') {
            echo '<div class="alert alert-danger">Email not recognized</div>';
          } else {
            echo '<div class="alert alert-danger">Something went wrong. Please try again. If the problem persists, request a new password or contact icreatives.</div>';
          }
        }
      ?>
    </div>

    <div class="text-right">
      <input type="submit" id="submit" class="btn btn-primary" value="next">
    </div>
  </form>
	<!-- end form -->
	<?php // } ?>
  </div>
</div>

<script>
document.getElementById('servicelogin').addEventListener('submit', function(e) {
  var submit = document.getElementById('submit');
  submit.disabled = true;
  submit.value = "Please wait...";
}, false);

// Focus email and place caret at end if prefilled
var input = document.getElementById('userID');
if (input) {
  var len = input.value.length;
  input.focus();
  try { input.setSelectionRange(len, len); } catch(e) {}
}
</script>
