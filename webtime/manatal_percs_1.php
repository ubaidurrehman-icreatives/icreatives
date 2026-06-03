<html>
<head>
<base target="_self">

<link rel='stylesheet' id='bootstrap-css-css'  href='/wp-content/themes/vg-mirinae/css/bootstrap.min.css?ver=3.3.5' type='text/css' media='all' />


<link href="/webtime/css/style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />


<link href='http://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />

    <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />
   <link rel="stylesheet" type="text/css" href="/webtime/css/style.css" />

    <script type="text/javascript" src="/webtime/CSS//js.js"></script>
    <script type="text/javascript" src="/webtime/CSS/jquery.js"></script>
    <script type="text/javascript" src="/webtime/CSS/custom-form-elements.js"></script>
   <?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>


<STYLE>

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
  font-family: arial;
  table-layout: auto;
  
}
</STYLE>


<script language="JavaScript">

function validateFields()
{
		
		// is the Quality radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Quality.length;  x++)
  			{
		    if (document.forms[0].Quality[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Quality of Work\" options.");

		    return (false);
		  	}
		  	
		  	
		// is the Dependability radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Dependability.length;  x++)
  			{
		    if (document.forms[0].Dependability[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Dependability\" options.");

		    return (false);
		  	}
		  	
		// is the Attendance radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Attendance.length;  x++)
  			{
		    if (document.forms[0].Attendance[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Attendance\" options.");

		    return (false);
		  	}
		  	
		  	
		// is the Cooperation- Teamwork radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Teamwork.length;  x++)
  			{
		    if (document.forms[0].Teamwork[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Cooperation- Teamwork\" options.");

		    return (false);
		  	}

		// is the Quantity- Productivity radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Quantity.length;  x++)
  			{
		    if (document.forms[0].Quantity[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Quantity - Productivity\" options.");

		    return (false);
		  	}


		// is the Initiative radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Initiative.length;  x++)
  			{
		    if (document.forms[0].Initiative[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Initiative\" options.");

		    return (false);
		  	}
		  	
		// is the Personality radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Personality.length;  x++)
  			{
		    if (document.forms[0].Personality[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Personality\" options.");

		    return (false);
		  	}

		// is the Match radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Match.length;  x++)
  			{
		    if (document.forms[0].Match[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"employee’s qualifications match\" options.");

		    return (false);
		  	}
		  	
		// is the DESIGN radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Design.length;  x++)
  			{
		    if (document.forms[0].Design[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"employee’s design abilities\" options.");

		    return (false);
		  	}
		  	
		// is the Copy radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Copy.length;  x++)
  			{
		    if (document.forms[0].Copy[x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"employee’s copy writing abilities\" options.");

		    return (false);
		  	}

		onsubmit="window.parent.scroll(0,0)";
		return true;
		
}		
</script>		
		

 <?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

<style>



input{background-color:#ffffff; color:#000000; font-size:11px; font-family:'Lato', 'sans-serif'; border:0px; border-top:solid 1px #666768; border-left:solid 1px #666768; padding-left:10px; line-height:14px; height:16px; }
select{background-color:#ffffff; color:#000000; font-weight:normal; font-size:12px; font-family:'Lato', 'sans-serif'; border:0px; border-top:solid 1px #666768; border-left:solid 1px #666768;  line-height:15px; height:18px;}

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
  font-size: 20px;
  vertical-align: middle;
  text-align: left;
  font-family: Lato;
  table-layout: auto;
height:20px;
}
.defaulttable input {
  font-size: 15px;
  
}
.defaulttable option {
  font-size: 15px;
height:20px;

  
}

</style>
</head>	

<?php 
require_once __DIR__ . '/../db/db.php';
$link = db();  
// $employee = str_replace("%EF%BB%BF","",$_REQUEST['employee']);
$employee = preg_replace("/[^0-9]/", "", $_REQUEST['employee']);

	$strSQL = "SELECT wt.Unique_ID as UNIQUEID,
			 wt.employee_id as EMPID,
			 wt.Assignment_ID as ASSIGNID,	
			 wt.BillingProfile as PROFILEID,
			 wt.SuperEmail as SUPEREMAIL,
			 wt.SentDate as SENDDATE,						
			 wt.Assignment_ID as ORDERID,				
			 wt.company_id as CUSTID, 
			 wt.PERCdate as PERCDATE,
			 wt.WeekEnding as WEEK,			 
			 wt.title as TITLE,				
			 wt.Primary_Contact_Email AS P_EMAIL,
			 wt.Second_Contact_Email AS  S_EMAIL,
			 wt.PERCdate AS PERC,
			 wt.first_name as EmpNAME,		
			 wt.last_name as EmpLAST,		
			 wt.Signature as SIGNED,
			 wt.company_name as CUSTOMER
			 from ic_timesheets wt 
			 JOIN ic_matches m ON wt.employee_id = m.candidate AND wt.AssignmentNumber = m.job ";
			 if(isset($_REQUEST["hash"]) && $_REQUEST["hash"] !== "") {
				$strSQL = $strSQL ." Where Employee_ID = '" . $employee . "' AND m.hash = '".$_REQUEST["hash"]."'" ;
			 }else{
				$strSQL = $strSQL ." Where Unique_ID = '" . $_REQUEST['Unique_ID'] . "'" ;
			 }
			 $strSQL = $strSQL . " ORDER BY AssignmentNumber DESC";
			 //
	// echo $strSQL;	 
	// https://www.icreatives.com/PERCS/?varib=20230401040437204405012

		$result = mysqli_query($link,$strSQL);
		$rowcount=mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);

If ($rowcount > 0 && $row["PERCDATE"]== "0000-00-00 00:00:00" || (isset($_REQUEST["hash"]) && $_REQUEST["hash"] !== "") ) {    	
	echo '<form name="PRECS" method="post" action="/webtime/manatal_percs_2.php#top" target="_self" onsubmit="return validateFields()">';
	echo "<input type='hidden' name='CUSTOMER' value='" . $row["CUSTOMER"] . "'>" ;
	// echo "<input type='hidden' name='CONTACTFIRST' value='" . $row["CONTACTFIRST"] . "'>" ;
	// echo "<input type='hidden' name='CONTACTLAST' value='" . $row["CONTACTLAST"] . "'>" ;
	echo "<input type='hidden' name='P_EMAIL' value='" . $row["P_EMAIL"] . "'>" ;
	echo "<input type='hidden' name='S_EMAIL' value='" . $row["S_EMAIL"] . "'>" ;
	echo "<input type='hidden' name='EMPID' value='" . $row["EMPID"] . "'>" ;
	echo "<input type='hidden' name='EmpFIRST' value='" . $row["EmpNAME"] . "'>" ;
	echo "<input type='hidden' name='EmpLAST' value='" . $row["EmpLAST"] . "'>" ;
	echo "<input type='hidden' name='ASSIGNID' value='" . $row["ASSIGNID"] . "'>" ;
	echo "<input type='hidden' name='TITLE' value='" . $row["TITLE"] . "'>" ;
	// echo "<input type='hidden' name='TAKENID' value='" . $row["TAKENID"] . "'>" ;
	// echo "<input type='hidden' name='SUPERID' value='" . $row["SUPERID"] . "'>" ;
	// echo "<input type='hidden' name='SUPEREMAIL' value='" . $row["SUPEREMAIL"] . "'>" ;
	// echo "<input type='hidden' name='PERC' value='" . $row["PERC"] . "'>" ;
	// echo "<input type='hidden' name='PERCDATE' value='" . $row["PERCDATE"] . "'>" ;	
	// echo "<input type='hidden' name='ALTETIME' value='" . $row["ALTETIME"] . "'>" ;
	// echo "<input type='hidden' name='DIVISIONID' value='" . $row["DIVISIONID"] . "'>" ;	
	echo "<input type='hidden' name='ORDERID' value='" . $row["ORDERID"] . "'>" ;		
	echo "<input type='hidden' name='SENTDATE' value='" . $row["SENDDATE"] . "'>" ;	
	echo "<input type='hidden' name='SIGNED' value='" . $row["SIGNED"] . "'>" ;		
	echo "<input type='hidden' name='Production' value='000'>" ;	
	echo "<input type='hidden' name='Unique_ID' value='" . $row['UNIQUEID'] . "'>" ;	
				
	?>

<p>&nbsp;</p>
<div style="float:left; width:500px; padding:0px 0 0 0px; color:#B22625; font-size:35px;"><b>Assignment Merit Evaluation:</b> </div>
<table class="defaulttable" border="0" width="100%">
  <tr>
    <td width="507">An integral part of our Program for Employee Relations and Customer Service
      (PERCS) is the Assignment Merit Evaluation. This evaluation is used to monitor the performance of our
      talent on assignment for you. In addition, this evaluation is used to consider
      talent for compensation increases, training and targeting areas for improvement. Please take a moment to fill out this evaluation. Your candor and honesty will provide us with the continuing guidance that we need to increase the level and quality of service that our company offers.  Many thanks for your time and help.
<br> <br>
      <span align="center">Please rate the employee in each of the categories.<br> <br></span></td>
  </tr>
</table>
<table border="0" width="517">
  <tr>
    <td width="177">Employee Name:&nbsp;<?php echo $row["EmpNAME"]; ?><br>
      Job Title:&nbsp;<?php echo $row["TITLE"] ?></td>
    <td width="153"></td>
    <td width="167"><!-- Start Date:&nbsp;<?php echo $row["START"] ?> --></td>
  </tr>
  <tr>
    <td width="177"></td>
    <td width="153"></td>
    <td width="167"></td>
  </tr>
</table>
<table border="0" width="519" height="106">
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="1"><b><span style="color:#000000;">Quality of work</span></b></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Quality"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Quality"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Quality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal"><i>virtually
            no errors</i></span></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Quality"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Quality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal"><i>minimal
            errors</i></span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Quality"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Quality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><i style="mso-bidi-font-style:normal"><span style="font-size:8.0pt;font-family:&quot;Times New Roman&quot;;mso-fareast-font-family:
&quot;Times New Roman&quot;;mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA">passable</span></i></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Quality"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Quality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><i style="mso-bidi-font-style:normal"><span style="font-size:8.0pt;font-family:&quot;Times New Roman&quot;;mso-fareast-font-family:
&quot;Times New Roman&quot;;mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA">careless</span></i></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="1"><b><span style="color:#000000;">Dependability</span></b></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Dependability"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Dependability"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Dependability"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><i style="mso-bidi-font-style:normal"><span style="font-size:8.0pt;font-family:&quot;Times New Roman&quot;;mso-fareast-font-family:
&quot;Times New Roman&quot;;mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA">needs no supervision</span></i></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Dependability"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Dependability"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><i style="mso-bidi-font-style:normal"><span style="font-size:8.0pt;font-family:&quot;Times New Roman&quot;;mso-fareast-font-family:
&quot;Times New Roman&quot;;mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA">minimal supervisions</span></i></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Dependability"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Dependability"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">needs average supervision</span></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Dependability"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Dependability"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">needs constant supervision</span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="21"><b><span style="color:#000000;">Attendance</span></b></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Attendance"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Attendance"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Attendance"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><i style="mso-bidi-font-style:normal"><span style="font-size:8.0pt;font-family:&quot;Times New Roman&quot;;mso-fareast-font-family:
&quot;Times New Roman&quot;;mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA">never absent or tardy</span></i></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Attendance"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Attendance"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">seldom absent or tardy</span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Attendance"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Attendance"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><i style="mso-bidi-font-style:normal"><span style="font-size:8.0pt;font-family:&quot;Times New Roman&quot;;mso-fareast-font-family:
&quot;Times New Roman&quot;;mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA">marginal attendance</span></i></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Attendance"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Attendance"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">unacceptable attendance</span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="21"><b><span style="color:#000000;">Cooperation/<br>
      Teamwork</span></b></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Teamwork"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Teamwork"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Teamwork"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">highly cooperative excellent teamwork</span></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Teamwork"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Teamwork"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">good participation with others</span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Teamwork"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Teamwork"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">satisfactory participation</span></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Teamwork"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Teamwork"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">fails to recognize cooperative role</span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="21"><b><span style="color:#000000;">Quantity/<br>
      Productivity</span></b></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Quantity"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Quantity"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Quantity"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">always exceeds production requirements</span></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Quantity"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Quantity"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">meets or exceeds production requirements</span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Quantity"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Quantity"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">meets only minimal production requirements</span></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Quantity"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Quantity"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">below requirements<br>
            </span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="21"><b><span style="color:#000000;">Initiative</span></b></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Initiative"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Initiative"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Initiative"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">actively seeks new responsibilities and tasks</span></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Initiative"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Initiative"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">generally assumes new responsibilities and tasks</span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Initiative"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Initiative"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">resistant to new responsibilities and tasks</span></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Initiative"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Initiative"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">refuses new responsibilities and tasks<br>
            </span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="21"><b><span style="color:#000000;">Personality</span></b></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Personality"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Personality"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Personality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">exceptional well-liked</span></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Personality"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Personality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">seldom has disputes with others</span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Personality"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Personality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">frequent misunderstandings with fellow workers</span></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Personality"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Personality"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">disliked by fellow workers<br>
            </span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="21"><b><span style="color:#000000;">Design Ability</span><br>
      </b><input type="radio" value="000" name="Design"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">not
      applicable&nbsp;</span></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Design"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Design"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Design"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">exceptional&nbsp;</span></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Design"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Design"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">&nbsp;good</span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Design"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Design"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">average</span></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Design"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Design"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">poor<br>
            </span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
  <tr>
    <td width="132" height="21"><b><span style="color:#000000;">Copywriting Ability</span><br>
      </b><input type="radio" value="000" name="Copy"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">not
      applicable&nbsp;</span></td>
    <td width="95" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="33%" align="center"><font size="1">100</font></td>
          <td width="33%" align="center"><font size="1">95</font></td>
          <td width="34%" align="center"><font size="1">90</font></td>
        </tr>
        <tr>
          <td width="33%" align="center"><input type="radio" value="100" name="Copy"></td>
          <td width="33%" align="center"><input type="radio" value="095" name="Copy"></td>
          <td width="34%" align="center"><input type="radio" value="090" name="Copy"></td>
        </tr>
        <tr>
          <td width="100%" colspan="3" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">exceptional</span></td>
        </tr>
      </table>
    </td>
    <td width="85" align="center" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">85</font></td>
          <td width="50%" align="center"><font size="1">80</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="085" name="Copy"></td>
          <td width="50%" align="center"><input type="radio" value="080" name="Copy"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">good</span></td>
        </tr>
      </table>
    </td>
    <td width="93" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">75</font></td>
          <td width="50%" align="center"><font size="1">70</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="075" name="Copy"></td>
          <td width="50%" align="center"><input type="radio" value="070" name="Copy"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">average</span></td>
        </tr>
      </table>
    </td>
    <td width="81" valign="top" height="1">
      <table border="0" width="100%">
        <tr>
          <td width="50%" align="center"><font size="1">65</font></td>
          <td width="50%" align="center"><font size="1">60</font></td>
        </tr>
        <tr>
          <td width="50%" align="center"><input type="radio" value="065" name="Copy"></td>
          <td width="50%" align="center"><input type="radio" value="060" name="Copy"></td>
        </tr>
        <tr>
          <td width="100%" colspan="2" align="center"><span style="font-size: 8.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA; mso-bidi-font-style: normal">poor
            </span></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="132" height="21"></td>
    <td width="95" height="21"></td>
    <td width="85" height="21"></td>
    <td width="93" height="21"></td>
    <td width="81" height="21"></td>
  </tr>
</table>
<p><b style="mso-bidi-font-weight:normal"><span style="color:#000000;">Does this employee's qualifications <br>match your job requirements?</span></b><b style="mso-bidi-font-weight: normal; font-size: 10.0pt; font-family: Times New Roman; mso-fareast-font-family: Times New Roman; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA">&nbsp;
</b><input type="radio" value="1" name="Match">Yes <input type="radio" value="0" name="Match">
No<p><br>
<table border="0" width="84%">
  <tr>
    <td width="21%">Please feel free to comment on any employee strengths.</td>
    <td width="79%"><textarea rows="6" name="Strength" cols="58"></textarea></td>
  </tr>
  <tr>
    <td width="21%">Please feel free to comment on any employee weaknesses.</td>
    <td width="79%"><textarea rows="6" name="Weakness" cols="58"></textarea></td>
  </tr>
</table><P>

<center>
<?php include "global2.php"; ?>
<div style="clear:left; float:left;" class="btn-submit"><input type="submit" target="_self" value="&nbsp;&nbsp;&nbsp;&nbsp;Send&nbsp;&nbsp;&nbsp;&nbsp;" /></div>

</center>

</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Force all forms to submit inside the iframe
  for (const f of document.forms) f.setAttribute('target','_self');

  // Force all anchors to open in iframe unless they already have a target
  for (const a of document.querySelectorAll('a[href]')) {
    if (!a.hasAttribute('target')) a.setAttribute('target','_self');
  }
});
</script>

<?php }Else{ ?>

<P>The talent has already been evaluated for week ending <?php echo $row["WEEK"] ?>

<?php } ?>


</HTML>

