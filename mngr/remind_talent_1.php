<!-- #include file="CommonLogin.asp"-->
<!-- #include file="global.asp"-->
<!-- <FORM Method = Post Action = "RemindTalent2b.asp">  -->
FORM onsubmit='parent.scrollTo(0, 0);' Method='Post' framename = 'remindclients' Action='RemindTalent2b.asp?varib1=" & uname & "&varib2=" & password & "'>

<?php

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());
/*
strSQL = "SELECT 'TIMEACT' as '!TIMEACT' "
strSQL = strSQL & ", REPLACE(REPLACE(cast(em.Last_Name as varchar(20))+ ', ' +cast(em.First_Name as varchar(20))  +CASE WHEN Middle_Name is null THEN '' ELSE ' ' +cast(em.Middle_Name as varchar(20)) END,' ','_'),',','') as EMP "
' strSQL = strSQL & ", convert(varchar(12),(SELECT DATEADD(day, DATEDIFF(day,0 , GETDATE()) - (DATEDIFF(day,  6,  GETDATE()) % 7), 0)),101) as DATE "
strSQL = strSQL &" , REPLACE(cm.Customer_Name,' ','_') as JOB "
strSQL = strSQL &" , REPLACE(bc.Billing_Description,' ','_') as ITEM "
strSQL = strSQL &" , oa.AssignmentNumber as PROJ "
strSQL = strSQL &" , oa.Start_DateTime as STARTDATE "
strSQL = strSQL &" , oa.End_Estimate_Date as ESTEND "
strSQL = strSQL & ", oa.PurchaseOrder as PO "
strSQL = strSQL & ", em.internetSMTPemail as EMAIL "
strSQL = strSQL & ", om.Branch_ID as BRANCHID "

strSQL = strSQL & " from orderassignment oa "
strSQL = strSQL &" JOIN ordermaster om on oa.Order_ID = om.Order_ID "
strSQL = strSQL &" JOIN CustomerBillingProfile bp on om.billingprofilekey = bp.billingprofilekey "
strSQL = strSQL &" JOIN employeemaster em on em.employee_ID = oa.Employee_ID "
strSQL = strSQL &" JOIN employeePRmaster ep on ep.employee_ID = oa.Employee_ID "
strSQL = strSQL &" JOIN CustomerMaster cm on cm.Customer_ID = om.Customer_ID "
strSQL = strSQL &" JOIN JobBillCode bc on bc.Billing_Code = om.Billing_Code "
strSQL = strSQL &" JOIN SecurityFilterUserBranches bs on bs.BranchKey = om.Branch_ID "
strSQL = strSQL &" WHERE oa.IsBookedSoft = 0 AND Upper(om.userdefined1) <> 'Y' AND om.branch_id <> 'ASKANS' AND Upper(bp.Userdefined6) = 'Y' "

' strSQL = strSQL & "AND wt.SentDate IS NULL "
' AND oa.End_Estimate_Date > getdate()-(DATEPART(weekday, getdate()) + 6) AND oa.Start_DateTime < getdate() - DATEPART(weekday, getdate())"
strSQL = strSQL & "AND (oa.End_Actual_Date is null or oa.End_Actual_Date > (getdate() - 7)) AND oa.IsBookedSoft <> '1' AND oa.Start_DateTime < getdate() - DATEPART(weekday, getdate())"

strSQL = strSQL & " ORDER BY EMP, PROJ"
*/


' response.write(strSQL)

rsOrder.Open strSQL, cn


'--------------------------------------------
?>
<FONT SIZE = 1>
<p>&nbsp;</p>
<B><H3>Email Reminders for Talent that have not sent their eTimesheets for:</H3>
<B><H3>Week Ending: <?php response.write(DATE() - weekday(date()+1)) ?></H3></B>
<P>


   <?php	strRecCount = 0
 	DO WHILE NOT rsOrder.EOF
		ReDim Preserve RemindArray(strRecCount)
		RemindArray(strRecCount) = rsOrder("EMAIL")
		ReDim Preserve NameArray(strRecCount)
		NameArray(strRecCount) = rsOrder("EMP")
		

  rsOrder.MoveNext
  strRecCount = strRecCount + 1
  LOOP 
  rsOrder.Close
  



  strSQL = ""
  ?>
  		


<% ' For Each item In RemindArray
' Response.Write(item & "<br>")
' Next %>

<% ' For Each item In NameArray
' Response.Write(item & "<br>")
' Next %>


<p>&nbsp;</p>

		<% response.write("<input type='hidden' name='strRecCount' value='" & strRecCount & "'>")	%>	

<%
for I=0 to ubound(RemindArray)
 response.write "<input type=hidden name=RemindArray value='" & RemindArray(I) & "'>"
Next
for I=0 to ubound(NameArray)
 response.write "<input type=hidden name=NameArray value='" & NameArray(I) & "'>"
Next

?>

<input type="Submit" value="Submit" name="Submit"> 

</Form>


</FONT>