<!-- #include file="common.asp"-->
<html>
<head>

<title>eTimesheet Password Reset</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="description" content="Contact your nearest i creatives office in the LA, San Francisco, Miami, Fort Lauderdale, San Jose, New York City, New Jersey and Atlanta for your creative staffing needs, or for freelance marketing or creative design positions.">
<meta name="keywords" content="creative staffing, jobs in marketing, freelance employment, Graphic Designers, Web Designers">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
</Head>
<Body>





<FORM Method = Post Action = "PassReset.asp">

<%
	Dim rsOrder 'Hold invoice recordset 
	Dim rsOrder2 'Hold invoice recordset 
	Dim strSQL ' 
	Dim strSQL2 ' 
	Dim lsBranch ' 
	Dim TS_Number
	Dim Contractor_ID
	Dim strEmployee_ID
	Dim strLastName
	Dim strFirstName
	
	Set rsOrder = Server.CreateObject("ADODB.Recordset")
	OpenDB()

			  
' strFirstName = ""
' strLastName = ""

strFirstName = request.form("FirstName")
strLastName = request.form("LastName")		  


%>

<p>&nbsp;</p>
<table border="0" width="55%">
  <tr>
    <td width="25%">first name</td>
    <td width="75%"><input type="text" name="FirstName" size="20"></td>
  </tr>
  <tr>
    <td width="25%">last name</td>
    <td width="75%"><input type="text" name="LastName" size="20"></td>
  </tr>
</table>
<input type="Submit" value="Submit" name="Submit"> 
<% 

If LEN(strFirstName) > 0 then 
		strSQL = "SELECT em.First_Name, em.Last_Name, em.WebRegistrationCode, em.Employee_ID, City FROM EmployeeMaster em" _
		& " WHERE em.Last_Name LIKE '" & strLastName & "%' and em.First_Name LIKE '" & strFirstName & "%' "
ELSE
		strSQL = "SELECT em.First_Name, em.Last_Name, em.WebRegistrationCode, em.Employee_ID, City FROM EmployeeMaster em" _
		& " WHERE em.Last_Name LIKE '" & strLastName & "%'"
END IF		  

' response.write(strSQL)

	
'		rsOrder.Close

If LEN(strLastName) > 0 then ' NOT ISNULL(strLastName) OR %>

<p>&nbsp;</p>
<table border="0" width="54%">
  <tr>
    <td width="29%">Name</td>
    <td width="22%">City</td>
    <td width="25%">Web ID</td>
  </tr>
  
	
<table>
	<%	rsOrder.Open strSQL, cn
	DO WHILE NOT rsOrder.EOF %>
  <tr>
    <td width="29%"><% =rsOrder("Last_Name") %>,<% =rsOrder("First_Name") %> </td>
    <td width="22%"><% =rsOrder("City") %></td>
    <td width="25%"><A Href='PassReset2.asp?Web_ID=<% = rsOrder("WebRegistrationCode") %>'><% =rsOrder("WebRegistrationCode") %></A></td>
  </tr>
  
<%  
  rsOrder.MoveNext
  LOOP 
  rsOrder.Close
%>  


</table>
<% END IF %>


</Form>

</Body>
</html>
