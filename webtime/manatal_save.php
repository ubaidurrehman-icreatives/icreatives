<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>icreatives staffing – login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Contact your nearest icreatives office..." />
  <meta name="keywords" content="creative staffing, jobs in marketing, ..." />

  <!-- Core CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <!-- Site CSS -->
  <link rel="stylesheet" href="/webtime/css/css.css">
  <link rel="stylesheet" href="/webtime/css/style.css">
      <!-- JS: jQuery first, then plugins, then your script -->
  <script src="/webtime/js/jquery.min.js"></script> <!-- move jQuery out of /css if possible -->
  <script src="/webtime/css/custom-form-elements.js"></script>
  <script src="/webtime/css/js.js"></script>

<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
  <style>


    .checkbox, .radio {
      width: 19px; height: 25px; padding: 0 5px 0 0;
      background: url(/webtime/css/checkbox.gif) no-repeat;
      display: block; clear: left; float: left;
    }
    .radio { background: url(/webtime/css/radio.png) no-repeat; }

    .select {
      position: absolute; height: 23px; width: auto;
      padding: 0 54px 0 7px; color: #fff; font: 11px arial, sans-serif;
      background: url(/webtime/css/dropdown_img.png) no-repeat right; /* FIXED */
      line-height: 24px; overflow: hidden;
    }

    /* table reset */
    .defaulttable { display: table; }
    .defaulttable thead { display: table-header-group; }
    .defaulttable tbody { display: table-row-group; }
    .defaulttable tfoot { display: table-footer-group; }
    .defaulttable tbody>tr,
    .defaulttable tbody>tr:hover { display: table-row; }
    .defaulttable tbody>tr>td,
    .defaulttable tbody>tr:hover>td { display: table-cell; }
    .defaulttable,
    .defaulttable * {
      background: transparent; border: 0; border-spacing: 0; border-collapse: separate;
      empty-cells: show; padding: 0; margin: 0; outline: 0;
      font-size: 100%; vertical-align: middle; text-align: left; font-family: Arial;
      table-layout: auto;
    }
  </style>

  <script>
    // Ensure jQuery is available
    (function ($) {
      $(function () {
        $(window).scrollTop(0);
      });
    })(window.jQuery || {});
  </script>
</head>
<body>
<script>
(function () {
  // Await ACK from parent after asking it to scroll
  function scrollParentThen(fn) {
    return new Promise(function (resolve) {
      function onAck(e) {
        if (e && e.data && e.data.type === 'SCROLL_TOP_ACK') {
          window.removeEventListener('message', onAck);
          // give the parent one paint before we navigate
          requestAnimationFrame(function(){ requestAnimationFrame(resolve); });
        }
      }
      window.addEventListener('message', onAck);
      // Ask parent to scroll
      try { window.parent.postMessage({ type: 'SCROLL_TOP_REQ' }, '*'); } catch(_) {}
      // Safety timeout: proceed after 150ms if no ACK (prevents deadlocks)
      setTimeout(function () {
        window.removeEventListener('message', onAck);
        resolve();
      }, 150);
    }).then(function () {
      if (typeof fn === 'function') fn();
    });
  }

  // Intercept all form submits and re-submit after parent scrolls
  document.addEventListener('DOMContentLoaded', function () {
    Array.prototype.forEach.call(document.forms, function (form) {
      form.addEventListener('submit', function (ev) {
        ev.preventDefault(); // stop immediate navigation
        scrollParentThen(function () {
          form.submit();      // native submit after parent is at top & painted
        });
      }, { once: true });
    });
  });
})();
</script>

<style>
/* Default (Desktop) */
.mobile-spacer{
  clear: left;
  height: 50px;
}

/* Tablet */
@media (max-width: 1024px) and (min-width: 769px){
  .mobile-spacer{
    height: 80px;
  }
}

/* Mobile */
@media (max-width: 768px){
  .mobile-spacer{
    height: 220px;
  }
}
</style>

<div class="mobile-spacer"></div>
  <div class="container" style="max-width: 807px; padding-bottom: 400px; padding-top:100px;">
    <div style="width: 660px;"></div>
    <div style="clear: both; height: 0;"></div>

    <div style="padding-left: 30px">
	
      <?php
        // Use require_once and handle errors explicitly

          include "manatal_save_1.php";

      ?>
    </div>
  </div>

</body>

</html>
