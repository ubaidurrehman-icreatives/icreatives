<?php
include "db5.php";
include "global2.php";

$sNextStep = $_REQUEST["sNextStep"];

/*

' Disable Caching
Dim PStr
pStr = "private, no-cache, must-revalidate" 
Response.ExpiresAbsolute = #2000-01-01# 
Response.AddHeader "pragma", "no-cache" 
Response.AddHeader "cache-control", pStr 
*/

?> 



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Contact your nearest i creatives office in the LA, San Francisco, Miami, Fort Lauderdale, San Jose, New York City, New Jersey and Atlanta for your creative staffing needs, or for freelance marketing or creative design positions.">
<meta name="keywords" content="creative staffing, jobs in marketing, freelance employment, Graphic Designers, Web Designers">

<title>i creative staffing login screen</title>

    <link href="/webtime/css/style.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />
<link href='/wp-content/themes/vg-mirinae/css/theme1.css?ver=1.0.0' />
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<style>
.checkbox, .radio {
	width: 19px;
	height: 25px;
	padding: 0 5px 0 0;
	background: url(/webtime/images/checkbox.gif) no-repeat;
	display: block;
	clear: left;
	float: left;
}
.radio {
	background:url(/webtime/images/images/radio.png) no-repeat;
}
.select {
	position: absolute;
	height:23px;
	width:auto;
	padding: 0px 54px 0px 7px;
	color: #fff;
	font-family:'Lato', 'sans-serif';
	font-size:11px;
	background:url(/webtime/images/images/dropdown_img.png) no-repeat right;
	line-height:24px;
	overflow: hidden;
}
h1 {
  font-family:'Lato', 'sans-serif';
}
</style>
    <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />

    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>

    
<style type="text/css"> 
body {background:#ffffff;} 
</style>


<script type="text/javascript">

function submitform()
{

  		document.forms["webtime"].submit();	
}
</script>



</head>
<style>
p.normal {
  font-style: Arial, Helvetica, sans-serif;
}

</style>
<!-- <link href='/wp-content/themes/vg-mirinae/css/theme1.css?ver=1.0.0' /> -->
<body onload="top.scrollTo(0,0)" >

<div class="container" style="float: left; width:100%; padding-left:10px;padding-bottom:400px">

<div style="float:right; width:95%;">
   <div style="float:left; width:10px;"> &nbsp; </div>
   <div style="float:right; width:100%; margin-right:10px; padding-bottom:20px;">
      <div style="float:left; width:100%;">
         <div style="float:left;">
            <div style="float:left;"><H1 style="font-family: Arial, Helvetica, sans-serif;">&nbsp;REQUEST TALENT</H1> </div>
      </div>
   </div>

<div style="float:left;" >
<img src = "/webtime/images/redline.png" />
</div>
</div>
               
<!-- insert stuff below here -->

                         
<Form Method = 'Post' id = 'webtime' name = TalentRequest action = 'trms_request_talent_2.php?varib=<?php echo $sNextStep; ?>'>
		

<h1 style="font-family: Arial, Helvetica, sans-serif;"><br />
NEED MORE TALENT?<br /></h1>
<p>Just check what you need, and we will start filling your open
position instantly.&nbsp;</p><br /><br />
<table border="0" width="782" valign="top">
  <tr>
    <td width="150"></td>
    <td width="152"></td>
    <td width="157"></td>
    <td width="142"></td>
    <td width="133"></td>
    <td width="170"></td>
  </tr>
  <tr>
    <td width="150" valign="top">
      <b>
      marketing:</b><br />
      <input type="checkbox" name="Marketing Director" value="ON">director<br />
 <input type="checkbox" name="Marketing Manager" value="ON">manager<br />
      <input type="checkbox" name="Marketing coordinator" value="ON">coordinator<br />
      <br />
      <b>manager:</b><br />
      	<input type="checkbox" name="Project Manager" value="ON">project<br />
      	<input type="checkbox" name="Studio Manager" value="ON">studio&nbsp;<br />
	<input type="checkbox" name="Traffic Manager" value="ON">traffic<br />
 	<input type="checkbox" name="Traffic Coordinator" value="ON">traffic coord<br />
      	<input type="checkbox" name="Production Manager" value="ON">production<br />
 	<input type="checkbox" name="Account Manager" value="ON">account<br /><br />
      <b>media</b>:<br />
      	<input type="checkbox" name="Media Planner" value="ON">buyer<br />
 	<input type="checkbox" name="Media Buyer" value="ON">planner</td>
    	<td width="152" valign="top">
         <b>creative director:</b><br />
      	<input type="checkbox" name="Creative Director - Art " value="ON">art&nbsp;<br />
	<input type="checkbox" name="Creative Director - Copy " value="ON">copy <br />
      
      <br /><b>art director:</b><br />
      	<input type="checkbox" name="Art Director Web" value="ON">web<br />
	<input type="checkbox" name="Art Director Print" value="ON">print<br />
      <br />
      <b>designer:</b><br />
      	<input type="checkbox" name="Designer Web" value="ON">web<br />
 	<input type="checkbox" name="Designer Print" value="ON">print<br />
      	<input type="checkbox" name="Designer Packaging" value="ON">packaging<br />
	<input type="checkbox" name="Designer Mobile App" value="ON">mobile app<br />
      <b><br />
      	rich media:</b>&nbsp;<br />
      	<input type="checkbox" name="Flash Designer" value="ON">designer<br />
 	<input type="checkbox" name="Flash Action Script" value="ON">action-script<br />
      	<input type="checkbox" name="Flash Developer" value="ON">developer
    </td>
    <td width="157" valign="top">
      <b>copywriter:</b><br />
      	<input type="checkbox" name="Copywriter Web" value="ON">web<br />
	<input type="checkbox" name="Copywriter Print" value="ON">print<br />
	<input type="checkbox" name="Copywriter Blogger" value="ON">blogger<br />
	<input type="checkbox" name="Proofreader" value="ON">proofreader<br />
      	<input type="checkbox" name="Copy Editor" value="ON">copy editor<br />
      	<input type="checkbox" name="SEO Specialist" value="ON">seo specialist<br />
      <br />
      <b>video</b>:<br />
      	<input type="checkbox" name="Video Editor" value="ON">editor<br />
      	<input type="checkbox" name="Video Acfter Effects" value="ON">after effects<br />
 	<input type="checkbox" name="Video Avid Editor" value="ON">avid<br />
      	<input type="checkbox" name="Video Final Cut Pro" value="ON">final cut pro<br />
      	<input type="checkbox" name="Video Premeire" value="ON">premier<br />
      	<input type="checkbox" name="Presentation Specialist" value="ON">presentation specialist</td>
    <td width="142" valign="top"><b>photography</b>:<br />
      	<input type="checkbox" name="Photo Retoucher" value="ON">retoucher<br />
 	<input type="checkbox" name="Photographer" value="ON">photographer<br />  <br />
      	<b>production:</b><br />
      	<input type="checkbox" name="Web Production" value="ON"> web<br />
 	<input type="checkbox" name="Print Production" value="ON">print<br />
      	<input type="checkbox" name="Packaging Production" value="ON">packaging<br />
 	<input type="checkbox" name="Pop Production" value="ON">pop<br />
      <br />
      <b>account</b>:<br />
      	<input type="checkbox" name="Senior Account Manager" value="ON">senior exec<br />
      	<input type="checkbox" name="Account Services" value="ON">services<br />
 	<input type="checkbox" name="Account Manager1" value="ON">manager<br />
      	<input type="checkbox" name="Account Director" value="ON">director</p>
    </td>
    <td width="133" valign="top">
      <b>programming<br /> </b> microsoft:<br />
      	<input type="checkbox" name="Microsoft ASP" value="ON">asp&nbsp;<input type="checkbox" name="Microsoft SQL" value="ON">mssql<br />
      	<input type="checkbox" name="Microsoft Dot Net" value="ON">net&nbsp;
	<input type="checkbox" name="Microsoft IIS Webmaster" value="ON">iis&nbsp;
      	<input type="checkbox" name="Microsoft C#" value="ON">c#<br />
      
      	<b>linux:</b><br />
      	<input type="checkbox" name="PHP Programmer" value="ON">php<br /> <input type="checkbox" name="MySQL" value="ON">mysql<br />
      	<input type="checkbox" name="Linux Webmaster" value="ON">linux<br />
      
      <b>HTML:</b><br />
      	<input type="checkbox" name="HTML%" value="ON">HTML<br />
      	<input type="checkbox" name="HTML-5%" value="ON">HTML-5<br />
 	<input type="checkbox" name="CSS" value="ON">CSS<br />
      	<input type="checkbox" name="email(tables)" value="ON">email-blast<br />
      <b>CMS:</b><br />
      	<input type="checkbox" name="Drupal" value="ON">drupal<br />
 	<input type="checkbox" name="WordPress" value="ON">wordpress<br />
      	<input type="checkbox" name="Joomla" value="ON">joomla</td>

    <td width="170" valign="top"></td>
  </tr>
</table>
<b>other: </b> <input type="text" name="OtherJobTitle" size="61"><br /><br /><br />

<div style="clear:both; float:left;">
<b>description: </b> <br /><textarea name="comments" cols="61" rows="10">
</textarea><br /><br />

</div>

 <!-- this is how the buttons should be -->
               <div style="float:right; padding: 15px 30px 0 0px;">
                  <div style="padding-top:10px;"> 
                     <div class="blankbtn" style="text-align:right; "><a href="javascript:submitform();" class="blankbtntxt">
		            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          Submit</a>
                     </div>
                  </div>
               </div>    


<!-- <input type="Submit" value="Submit" name="Submit"> -->

</form>
         


<!-- insert stuff above here -->
 

<!--END formContainer--> 

</div>
</body>
</html>

