<?php 
session_start();
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$sScreen = Str_Replace("|","'",$_REQUEST["sScreen"]);
// $eScreen = Str_Replace("|","'",$_REQUEST["eScreen"]);
// $InvCount = $_REQUEST["invcount"];
// $Contract = $_REQUEST["CONTRACT"];
// $InvMethod = $_REQUEST["INVMETHOD"];
$sNextStep = $_REQUEST["snextstep"];
// $StrWeekEnd = date('m/d/Y',StrToTime($_REQUEST["WKEND"]));
$REPORTTO = $_REQUEST["REPORTTO"] ?? '';
$REPORTTOCC = $_REQUEST["REPORTTOCC"] ?? '';
$Contractor_ID = $_REQUEST['Contractor_ID'];
// $PoNumber = str_replace(" ","-",$_REQUEST["PO"]);
// $PoNumber = filter_var($PoNumber, FILTER_SANITIZE_STRING);

require_once __DIR__ . '/../db/db.php';
$link = db();   
global $link;

$sNextStep = $_REQUEST["snextstep"];
// Disable Caching
/*
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Contact your nearest i creatives office in the LA, San Francisco, Miami, Fort Lauderdale, San Jose, New York City, New Jersey and Atlanta for your creative staffing needs, or for freelance marketing or creative design positions.">
<meta name="keywords" content="creative staffing, jobs in marketing, freelance employment, Graphic Designers, Web Designers">

<title>i creative staffing login screen</title>
    <link href="/webtime/CSS/style.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapimeis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />

<link rel='stylesheet' id='bootstrap-css-css'  href='/wp-content/themes/vg-mirinae/css/bootstrap.min.css?ver=3.3.5' type='text/css' media='all' />
<link rel='stylesheet' id='mirinae-css-css'  href='/wp-content/themes/vg-mirinae/css/theme1.css?ver=1.0.0' type='text/css' media='all' />
<link href="/webtime/css/mobile/styles.css" rel="stylesheet" type="text/css" />



<link href='http://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />

    <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />
   <link rel="stylesheet" type="text/css" href="/webtime/css/style.css" />

    <script type="text/javascript" src="/webtime/CSS//js.js"></script>
    <script type="text/javascript" src="/webtime/CSS/jquery.js"></script>
    <script type="text/javascript" src="/webtime/CSS/custom-form-elements.js"></script>
	
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

<style>

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
	background:url(/webtime/images/dropdown_img.png) no-repeat right;
	line-height:24px;
	overflow: hidden;
}
</style>
    
<script type="text/javascript">

function SubmitPERCS()
{
	
  		document.forms["PERCS"].submit();
	
}

function SubmitRequest()
{
	
  		document.forms["Request"].submit();
	
}


</script>			


</head>

<body>

<script>
try { window.parent.scrollTo(0, 0); } catch(e) {}
</script>

<style>
.mobile-spacer{
  clear:left;
  height:50px;
}

/* Mobile devices */
@media (max-width: 768px){
  .mobile-spacer{
    height:500px;
  }
}
</style>

<div class="container" style="clear:left; float: right; width:100%; padding-left:0px; padding-bottom:500px">
	<div style="float:left; width:100%x; padding:0px 0 0 0px;">
<div class="mobile-spacer"></div>
		<div style="float:left; padding:30px 0 0 0px;">                                  
		<!-- insert stuff below here -->
                        
		<?php 
		//echo __DIR__ . '/manatal_write_1.php';
		include(__DIR__ . '/manatal_write_1.php'); ?>
 
		<!-- insert stuff above here -->
		</div>
	</div>

</div><!--END formContainer--> 

	<script>
(function () {
  // IMPORTANT: keep this exactly the same as the parent expects
  const MSG_SCROLL = 'IC_SCROLL_IFRAME_TOP';

  function tellParentScrollTop() {
    try { window.parent.postMessage({ type: MSG_SCROLL }, '*'); } catch (_) {}
  }

 function scrollChildToTop() {
  try { if ('scrollRestoration' in history) history.scrollRestoration = 'manual'; } catch(_) {}

  // Scroll all plausible scrollers
  try { window.scrollTo(0,0); } catch(_) {}
  try { document.documentElement.scrollTop = 0; } catch(_) {}
  try { document.body.scrollTop = 0; } catch(_) {}

  // If your portal uses an inner scroll container, target it explicitly:
  const scroller =
    document.querySelector('.your-scroll-container') ||
    document.querySelector('#content_timesheet') ||
    null;

  if (scroller) scroller.scrollTop = 0;

  setTimeout(() => { try { window.scrollTo(0,0); } catch(_) {} }, 0);

  try { window.parent.postMessage({ type: 'IC_SCROLL_IFRAME_TOP' }, '*'); } catch(_) {}
}
  // 1) When this iframe page loads (normal navigation)
  window.addEventListener('load', scrollChildToTop);

  // 2) When page is restored from bfcache (iOS Safari loves this)
  window.addEventListener('pageshow', function (e) {
    // e.persisted true = bfcache restore
    scrollChildToTop();
  });

  // 3) BEFORE navigation starts, try to move to top
  // Capture phase catches dynamically injected forms/links too.
  document.addEventListener('submit', function () {
    // do not preventDefault; just trigger scroll immediately
    scrollChildToTop();
  }, true);

  document.addEventListener('click', function (e) {
    const a = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!a) return;

    // Only for real navigations
    const href = a.getAttribute('href') || '';
    if (!href || href[0] === '#' || href.startsWith('javascript:')) return;
    if (a.target && a.target.toLowerCase() === '_blank') return;

    scrollChildToTop();
  }, true);

  // 4) Last-chance: when leaving the page
  window.addEventListener('beforeunload', function () {
    tellParentScrollTop();
  });
})();
</script>
</body>
</html>


