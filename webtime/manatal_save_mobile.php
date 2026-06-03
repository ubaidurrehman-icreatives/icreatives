<?php
/* 
' Disable Caching
Dim PStr
pStr = "private, no-cache, must-revalidate" 
Response.ExpiresAbsolute = #2000-01-01# 
'Response.AddHeader "pragma", "no-cache" 
'Response.AddHeader "cache-control", pStr 
*/

?> 



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="description" content="Contact your nearest i creatives office in the LA, San Francisco, Miami, Fort Lauderdale, San Jose, New York City, New Jersey and Atlanta for your creative staffing needs, or for freelance marketing or creative design positions.">
<meta name="keywords" content="creative staffing, jobs in marketing, freelance employment, Graphic Designers, Web Designers">

<title>i creative staffing login screen</title>

   <!-- <link href="http://<?php // echo($_REQUEST["SERVER_NAME"]) ?>/wp-content/plugins/wptouch/themes/foundation/default/styles.css" rel="stylesheet" type="text/css" /> -->
   <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />       
   <link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />

<style>
.checkbox, .radio {
	width: 19px;
	height: 25px;
	padding: 0 5px 0 0;
	background: url(/webtime/css/checkbox.gif) no-repeat;
	display: block;
	clear: left;
	float: left;
}
.radio {
	background:url(/webtime/css/radio.png) no-repeat;
}
.select {
	position: absolute;
	height:23px;
	width:auto;
	padding: 0px 54px 0px 7px;
	color: #fff;
	font-family:'Lato', 'sans-serif';
	font-size:11px;
	background:url(/webtime/css/dropdown_img.png) no-repeat right;
	line-height:24px;
	overflow: hidden;
}
</style>
    <link rel="stylesheet" type="text/css" href="/webtime/css.css" />

    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />
    
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

<script>
(function () {
  // Send message to parent as soon as user submits any form
  // Capture=true catches submits even if the form is dynamically injected
  document.addEventListener('submit', function (e) {
    try {
      window.parent.postMessage({ type: 'IC_SCROLL_IFRAME_TOP' }, '*');
    } catch (_) {}
    // Do NOT preventDefault; let the submit proceed immediately
  }, true);

  // Also scroll parent to top when this page loads (helpful after navigation)
  window.addEventListener('DOMContentLoaded', function () {
    try {
      window.parent.postMessage({ type: 'IC_SCROLL_IFRAME_TOP' }, '*');
    } catch (_) {}
    window.scrollTo(0,0);
  });
})();
</script>

</head>
<body>
<!--
<div class="container" style="float: left; padding-left: 0px;padding-bottom:400px"> -->

<a id="top"></a>

               
<!-- insert stuff below here -->
 <div id="content_timesheet" style="padding-bottom:500px;"> 
                         
         <?php include "manatal_save_1_mobile.php" ; ?>             
</div>

<!-- insert stuff above here -->
 
<script>
    // Automatically jump to the top anchor as soon as the iframe loads
   //  window.location.hash = "#top";
</script>
<!--END formContainer--> 


</body>
</html>


