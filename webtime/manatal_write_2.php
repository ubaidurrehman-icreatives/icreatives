<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<HTML>
<head>
</head>
<body onload="top.scrollTo(0,0)">
<link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />
    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />
     <link href="/webtime/css/style.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />

<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<style>
.defaulttable {
  display: table;
}
.defaulttable thead {
  display: table-header-group;
}
.defaulttable tbody {
  display: table-row-group;
}
.defaulttable tfoot {
  display: table-footer-group;
}
.defaulttable tbody>tr:hover,
.defaulttable tbody>tr {
  display: table-row;
}
.defaulttable tbody>tr:hover>td,
.defaulttable tbody>tr>td {
  display: table-cell;
}
.defaulttable,
.defaulttable tbody,
.defaulttable tbody>tr:hover,
.defaulttable tbody>tr,
.defaulttable tbody>tr:hover>td,
.defaulttable tbody>tr>td,
.defaulttable tbody>tr:hover>th,
.defaulttable tbody>tr>th,
.defaulttable thead>tr:hover>td,
.defaulttable thead>tr>td,
.defaulttable thead>tr:hover>th,
.defaulttable thead>tr>th,
.defaulttable tfoot>tr:hover>td,
.defaulttable tfoot>tr>td,
.defaulttable tfoot>tr:hover>th,
.defaulttable tfoot>tr>th {
  background: transparent;
  border: 0px solid #000;
  border-spacing: 0px;
  border-collapse: separate;
  empty-cells: show;
  padding: 0px;
  margin: 0px;
  outline: 0px;
  font-size: 100%;
  vertical-align: middle;
  text-align: left;
  font-family: Lato;
  table-layout: auto;
  
}

.btn-section a, .btn-section input[type="submit"] {
  display: inline-block;
  padding: 10px  25px;
  color: #ffffff;
  text-transform: uppercase;
  background: #b22625;
  font-weight: 600;
  border: 1px solid transparent;
}
.btn-section a:hover, .btn-section input[type="submit"]:hover {
  color: #b22625;
  background: #ffffff;
  border: 1px solid #b22625;
}

.btn-submit input[type="submit"] {
  display: inline-block;
  padding: 13px 25px 27px 25px;
  color: #ffffff;
  font-family: arial;
  text-transform: uppercase;
  background: #b22625;
  font-weight: 600;
  border: 1px solid transparent;
}
.btn-submit input[type="submit"]:hover {
  color: #b22625;
  background: #ffffff;
  border: 1px solid #b22625;
}

</style>

<style>
input {
	
}
</style>
<?php
function is_Mobile(){
    if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
       $user_ag = $_SERVER['HTTP_USER_AGENT'];
       if(preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis',$user_ag)){
          return true;
       };
    };
    return false;
}
?>
<!--
  <input type="hidden" value="steven" name="ApproverName">
  <input type="hidden" name="ApproverEmail">
  <input type="hidden" value="ysmith@icreatives.com" name="Client_Email">
  <input type="hidden" value="Shea Meadow" name="ContactName">
  <input type="hidden" value="6074640155" name="Unique_ID">
-->
<div style="width:100%;">
<div style="text-align:center;  width=95%;">
  <H1 style = "font-family: Arial, Helvetica, sans-serif; align:center;" >TIMESHEET APPROVED</H1>
</div>
<div style="text-align:center;  width=95%;">
  <h3 style="text-align:center; font-family: Arial, Helvetica, sans-serif;">an approved timesheet has been emailed for your records<br><br></h3>
</div>	

<div style="text-align:center;"> <hr width="100%"></div>

<table width="500">
<div style="text-align:center; padding:10px 0 0 10px;  margin:0 auto; width:100%">
	<tr>
		<td>
		<div style="clear:left; text-align:right; padding-bottom:10px; ">
			<h2 style="text-align:right;">EVALUATE<br/><?php echo(StrToUpper(Str_Replace("_"," ",($sTalentName ?? ''))));?></h2>
		</div>
		</td>
		<td width="40">&nbsp;</td>
		<td>
		<div style="float:left; text-align:left;padding-bottom:10px;">
  	      		<h2 align="left">NEED MORE TALENT?</h2>	
		</div>
		</td>
	</tr>
	<tr>
	<td>
		<div style="clear:left; text-align:right;">
			<span style="text-indent: 0; word-spacing: 0; line-height: 150%; margin: 0">
			Please help us be all we can be and evaluate our talent, it is quick and easy and
   	     		confidential. Thank you.</span>
		</div>	
	</td>
	<td width="40">&nbsp;</td>
	<td>	
		<div style="float:left; text-align:left;">
 			<span style="text-indent: 0; word-spacing: 0; line-height: 150%; margin: 0">
    	    		Please click on the button below and we may have the perfect talent ready to start when you are.</span>	
		</div>
	</td>
	</tr>
	<tr>
	<td>

		<div style="clear:left; text-align:right;padding-top:10px;" class="btn-submit">
				<Form Method = 'Post' id = "PERCS" target="_self" name = "PERCS" action = '/webtime/manatal_percs_1.php/?varib=<?php echo($_REQUEST["Unique_ID"]);?>'>
				<input type="submit" onclick="return validateData();" value="&nbsp;&nbsp; &nbsp; evaluate &nbsp; &nbsp;&nbsp; " />
				<?php echo("<input type='hidden' name='Unique_ID' value='" . $_REQUEST["Unique_ID"] . "'>") ; ?>
				<?php echo("<input type='hidden' name='ContactName' value='" . $ContactName . "'>"); ?>
				<?php echo("<input type='hidden' name='ApproverEmail' value='" . ($_REQUEST["AltEtime"] ?? ''). "'>") ; ?>
				<?php echo("<input type='hidden' name='ApproverName' value='" . $_REQUEST["Signature"] . "'>") ;?>         
     			</form>
		</div>	
	</td>
	<td width="40">&nbsp;</td>
	<td>
		<div style="text-align:left;padding-top:10px;" class="btn-submit">
			<Form Method = 'Post'  id="Request"  target="_self" name = "Request" action = '/webtime/manatal_request_talent.php?sNextStep=<?php echo($_REQUEST["Unique_ID"]);?>'>
			<div class="btn-submit">
				<input type="submit" value="Request Talent"/>
				<?php echo "<input type='hidden' name='Unique_ID' value='" . $_REQUEST["Unique_ID"] . "'>"  ;?>
				<?php echo "<input type='hidden' name='ContactName' value='" . $ContactName . "'>"; ?>
				<?php echo "<input type='hidden' name='ApproverEmail' value='" . ($_REQUEST["AltEtime"] ?? '') . "'>" ;?>
				<?php echo "<input type='hidden' name='ApproverName' value='" . $_REQUEST["Signature"] . "'>" ;?>        
				</form>
			</div>
		</div>
	</td>
	</tr>
</div>

</body>
</html>