
<%
' dim varib
' InvNum = "IVC0000000" & request.QueryString("InvNum")

' IVC000000036211-2021.25-2012-10-20


'	strSQL = strSQL & " WHERE id.documentkey = 'IVC000000036211' "
'	strSQL = strSQL & " and inv.amount='2021.25'"
'	strSQL = strSQL & " and inv.bppostalcode = '33060' "
'	strSQL = strSQL & " and inv.Duedate = '2012-10-20' "





	Dim rsOrder 'Hold invoice recordset 
	Dim strSQL ' 
	Dim lsOrder
	Dim lsAssignment_ID
	Dim PassThru
	Dim Unique_ID
	Dim lsAccept
	Dim lsWeekEnd
	' Dim i
	Dim x
	Dim lshr
	Dim lsmin
	Dim lsampm	
	Dim lsInHr
	Dim rsGT 
	' Dim HOURS
 	rsGT = 0
	i = 1
	Unique_ID = request.QueryString("Unique_ID")	
	lsAccept = request.QueryString("Accept")	
		
' Now include email layout in StatementTimesheet.txt

%>
<!-- #include file="StatementTimesheet.txt"-->
<%

Function HrComp(lsInHr,lsOutHr)
If lsInHr >= 13 then
   lsInHr = int(lsInHr -12)
ElseIf lsInHr = 0 AND lsOutHr <> 0 then
	lsInHR = 12
Else
   lsInHr = int(lsInHr)
End If
HrComp = lsInHr
End Function


Function AmPmCompIn(lsHr) ' need two because out should default to PM for new record

If lsHr >= 12 then
        AmPmCompIn = "PM"
Else
        AmPmCompIn = "AM"
End If

End Function

Function AmPmCompOut(lsHr)
If lsHr >= 12 or lsHr = 0 then
        AmPmCompOut = "PM"
Else
        AmPmCompOut = "AM"
End If
End Function

Function MinComp(lsHr)
lsMin = (lsHr - int(LsHr)) * 60
Select Case lsMin

Case 0
MinComp = "00"
        
Case 15
MinComp = "15"

Case 30
MinComp = "30"

Case 45
MinComp = "45"

Case Else
MinComp = "00"

End Select
End Function

'Create an ADO recordset object
Set rsOrder = Server.CreateObject("ADODB.Recordset")

OpenDB()
' opencn cn
'- start





	strSQL = "SELECT 'TIMEACT' as '!TIMEACT' "
	strSQL = strSQL & ", REPLACE(REPLACE(cast(em.Last_Name as varchar(20))+ ', ' +cast(em.First_Name as varchar(20))  +CASE WHEN Middle_Name is null THEN '' ELSE ' '+cast(em.Middle_Name as varchar(20)) END,' ','_'),',','') as EMP "
'	strSQL = strSQL & ", convert(varchar(12),(SELECT DATEADD(day, DATEDIFF(day,0 , GETDATE()) - (DATEDIFF(day,  6,  GETDATE()) % 7), 0)),101) as DATE "
	strSQL = strSQL & ", REPLACE(cm.Customer_Name,' ','_') as JOB "
	strSQL = strSQL & ", REPLACE(em.PostalCode,' ','_') as ZIP "
	'	strSQL = strSQL & ", REPLACE(bc.Billing_Description,' ','_') as ITEM "
	strSQL = strSQL & ", '-' as NOTE, oa.Assignment_ID as PROJ "
	strSQL = strSQL & ", oa.Order_ID as xORDER "
	strSQL = strSQL & ", om.TakenContactKey as ORDERTAKENID "
	strSQL = strSQL & ", om.StartContactKey as REPORTID "
	strSQL = strSQL & ", om.SupervisorContactKey as SUPERVISORID "
	strSQL = strSQL & ", om.InvoiceField03Data as ATTACHTIME "
	strSQL = strSQL & ", '40' as DURATION "
	strSQL = strSQL & ", '1' as BILLINGSTATUS "
	strSQL = strSQL & ", 'CONTR' as PITEM "
	strSQL = strSQL & ", '0' as BITEM "
	strSQL = strSQL & ", id.OAPurchaseOrder as PO "
	strSQL = strSQL & ", om.BillingProfileKey as BILLINGPROFILE "
	strSQL = strSQL & ", bp.InvoiceField01Break as SHOWLAST " 

 	for i = 1 to 7
 		strSQL = strSql & ", wt.TimeInHr" & i & " as INHR" & i & " "
		strSQL = strSql & ", wt.TimeOutHr" & i & " as OUTHR" & i & " "
		strSQL = strSql & ", wt.Break" & i & " as BREAK" & i & " "
 	next
	strSQL = strSQL & ", wt.Hours as Hours "
	strSQL = strSQL & ", wt.WeekEnding as WeekEnding "
	strSQL = strSQL & ", wt.CustomerIpAddr as IP "
	strSQL = strSQL & ", wt.Signature as Signature "	
	strSQL = strSql & ", wt.Continuing as CONT " 
	strSQL = strSql & ", wt.SuperEmail as SUPER " 
	strSQL = strSql & ", wt.EmpEmail as AltEmpEmail " 
	strSQL = strSql & ", wt.AcctEmail as AcctEmail " 
	strSQL = strSql & ", wt.unique_id as unique_id "  
	strSQL = strSql & ", wt.approvedate as APPROVEDATE " 
	strSQL = strSQL & "from AR_INVOICE_DETAIL as id "
	strSQL = strSQL & "join ic_webtime wt on wt.assignment_id = id.orderKey and wt.weekending = id.weekending and id.employeekey = wt.employee_id "
	strSQL = strSQL & "join orderassignment oa on oa.assignment_id = id.assignmentkey "
	strSQL = strSQL & "join AR_INVOICE inv on inv.documentkey = id.documentkey "

	strSQL = strSQL & "JOIN ordermaster om on oa.Order_ID = om.Order_ID "
	strSQL = strSQL & "JOIN CustomerBillingProfile bp on om.billingprofilekey = bp.billingprofilekey "
	strSQL = strSQL & "JOIN employeemaster em on em.employee_ID = oa.Employee_ID "
	strSQL = strSQL & "JOIN CustomerMaster cm on cm.Customer_ID = om.Customer_ID "
'//	strSQL = strSQL & "JOIN JobBillCode bc on bc.Billing_Code = om.Billing_Code "
'//	strSQL = strSQL & " JOIN IC_WebTime wt on wt.AssignmentNumber = oa.AssignmentNumber "

	strSQL = strSQL & " WHERE inv.documentkey = '" &sInvNum& "' "
'//	strSQL = strSQL & " and inv.amount='2021.25'"
'//	strSQL = strSQL & " and inv.bppostalcode = '33060' "
'//	strSQL = strSQL & " and inv.Duedate = '2012-10-20' "
	strSQL = strSQL & " and wt.signature is not null "
	invSQL = invSQL & " and id.VoidDate IS NULL "
	strSQL = strSQL & " and wt.approvedate is not null ORDER BY id.weekending"
'//
'	 response.write(strSQL)
' xxx()
	rsOrder.Open strSQL, cn

' response.write("IP: " & rsOrder("IP"))
' response.write("SIG: " & rsOrder("Signature"))
' response.write("approve: " & rsOrder("ApproveDate"))
Dim xSignature


IF NOT rsOrder.EOF then
IP =  rsOrder("IP")
xSignature = rsOrder("Signature")
Dim WkEnd
Dim AssignNo
Dim sTalentName
Dim AttachTime

' Date = DATE()
' Dim xSCreen
' Dim IP
' Dim Signature
' response.write( rsOrder("PROJ") )
' dim count
Dim sAttach
count = 0
sAttach = 0

Do while NOT rsOrder.EOF


If rsOrder("ATTACHTIME") = "N" then
   sAttach = 1
End If


IF sAttach = 0  Then
   count = count + 1

WkEnd = rsOrder("WeekEnding")
' IP = rsOrder("IP")
' Signature = rsOrder("Signature")
HOURS = rsOrder("Hours")
AssignNo = rsOrder("PROJ")
	
xScreen = sScreen()


if count > 1 then ' add a page break after first page
   xScreen ="<BR style=|page-break-before: always|>" &  xScreen
end if
' xScreen = Replace(xScreen,"IPIP",IP)
' xScreen = Replace(xScreen,"APPROVED </h1>", "APPROVED</h1><br />" & AssignNo & "xxx")

			xScreen = Replace(xScreen,"IPIP",IP)

			xScreen = Replace(xScreen,"AAAA",xSignature)

xScreen = Replace(xScreen,"|","'")


 	if rsOrder("SHOWLAST") > 0 then 
		sTalentName = rsOrder("EMP")
 	else
		sTalentName = RIGHT(rsOrder("EMP"), LEN(rsOrder("EMP")) - InStr(rsOrder("EMP"),"_"))
	end if




		 	xScreen = Replace(xScreen,"XXXX",sTalentName)

			xScreen = Replace(xScreen,"YYYY","<b style='color:#a4100c'>Customer: </b>" & replace(rsOrder("JOB"),"_"," ") & "<BR>" & vbCrlf)

		   	if rsOrder("PO") <> "" then
	      	 		xScreen = Replace(xScreen,"ZZZZ","PO Number: " & rsOrder("PO"))
			else
	      	 		xScreen = Replace(xScreen,"ZZZZ","")
			end if 




'		response.write("<input type='hidden' name='EMP' value='" & rsOrder("EMP")& "'>")
'		response.write("<input type='hidden' name='DATE' value='" & rsOrder("DATE")& "'>")
'		response.write("<input type='hidden' name='JOB' value='" & rsOrder("JOB")& "'>")
'		response.write("<input type='hidden' name='ITEM' value='" & rsOrder("ITEM")& "'>")
'

response.write(xScreen)

 End If 

rsOrder.MoveNext

Loop

' Reset server objects
rsOrder.Close
Set rsOrder = Nothing
Else

' Response.write( "<BR style='page-break-before: always'><H1> this page is blank </H1>") 
 Response.write( "<BR style='page-break-before: always'><H1>  </H1>") 

End If
%>		

