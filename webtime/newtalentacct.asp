<% 
' Disable Caching
Dim PStr
pStr = "private, no-cache, must-revalidate" 
Response.ExpiresAbsolute = #2000-01-01# 
Response.AddHeader "pragma", "no-cache" 
Response.AddHeader "cache-control", pStr 
%> 



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Contact your nearest i creatives office in the LA, San Francisco, Miami, Fort Lauderdale, San Jose, New York City, New Jersey and Atlanta for your creative staffing needs, or for freelance marketing or creative design positions.">
<meta name="keywords" content="creative staffing, jobs in marketing, freelance employment, Graphic Designers, Web Designers">

<title>i creative staffing login screen</title>

    <link href="http://<%Response.Write(Request.ServerVariables("server_name"))%>/webtime/css/style.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />

<style>
.checkbox, .radio {
	width: 19px;
	height: 25px;
	padding: 0 5px 0 0;
	background: url(http://<%Response.Write(Request.ServerVariables("server_name"))%>/wp-content/themes/twentyten/checkbox.gif) no-repeat;
	display: block;
	clear: left;
	float: left;
}
.radio {
	background:url(http://<%Response.Write(Request.ServerVariables("server_name"))%>/webtime/images/radio.png) no-repeat;
}
.select {
	position: absolute;
	height:23px;
	width:auto;
	padding: 0px 54px 0px 7px;
	color: #fff;
	font-family:'Lato', 'sans-serif';
	font-size:11px;
	background:url(http://<%Response.Write(Request.ServerVariables("server_name"))%>/webtime/dropdown_img.png) no-repeat right;
	line-height:24px;
	overflow: hidden;
}


html
{
  padding: 0px;
  margin: 0px;
  background: #FFFFFF;
    


}

input {
	background-color:#FFFFFF;
	color:gray;
	font-size:11px;
	font-family:'Lato','sans-serif';
	border:0;
	border-top:solid 1px #666768;
	border-left:solid 1px #666768;

	height:18px;

}

</style>
    <link rel="stylesheet" type="text/css" href="<%Response.Write(Request.ServerVariables("server_name"))%>/webtime/css/css.css" />

    <script type="text/javascript" src="http://<%Response.Write(Request.ServerVariables("server_name"))%>/webtime/css/js.js"></script>
    <script type="text/javascript" src="http://<%Response.Write(Request.ServerVariables("server_name"))%>/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="http://<%Response.Write(Request.ServerVariables("server_name"))%>/webtime/css/custom-form-elements.js"></script>
    


</head>
<body>

   <div style="clear:left; float:left; width:784px;">
      <div style="float:left;">
         <div style="float:left;" class="largetxt1"><span class="redarrowclass1"> &nbsp;&nbsp;[</span> Time Sheets </div>
         <div style="float:left; padding:3px 2px 0 3px;" class="redarrowclass1"> >>> </div>
      </div>
      <div style="clear:left; height:50px;"> &nbsp;</div>
      <div style="float:left;" class="btnbg">
      <div style="float:left; width:300px;  padding:16px 0 0 60px;"> <a href="#" class="whitetxt1"> Create Password</a> </div>
   </div>
   <div style="clear:left; height:50px;"> &nbsp;</div>                                  

<!-- insert stuff below here -->

                         
<!-- #include file="NewTalentAcct_2.asp"-->             


<!-- insert stuff above here -->
 


</body>
</html>


