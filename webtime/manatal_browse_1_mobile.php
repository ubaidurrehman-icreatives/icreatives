<?php session_start(); 

require_once __DIR__ . '/../db/db.php';
// echo $_POST['resource_id'] ."XXX". $_POST['user'] ."xxx".$_POST['contractor_id'] ;
$resource_id = $_SESSION['resource_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>i creative staffing login screen</title>

    <link href="/webtime/css/mobile/styles.css" rel="stylesheet" type="text/css" />
     <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />
        
    <link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">



<link href='http://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
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
    
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
</head>
<body>

<?php
$link = db();   

if (!isset($resource_id)) {
  header("Location: /portal/manatal_talent_portal_signin.php/?r=fields");
  return;
}


$Contractor_ID = $_SESSION['resource_id'];
$varib = 		 $_SESSION['resource_id'];

?>
<div id="content_timesheet" style="padding-top:50px; margin-left:10px; !important">
  <h1 class="center" style="font-size: 32px;
		color:#6C6A6B;font-weight:bolder; font-family:lato;"> Timesheets</h1>

<?php include "manatal_browse_2_mobile.php";?>


    
</div>

</body>
</html>