<!-- #include file="common2.asp"-->


<%



Dim sRegCode
 sRegCode = Mid(request.QueryString("sRegCode"),4,8)

	
'Create an ADO recordset object
Set rstLogin = Server.CreateObject("ADODB.Recordset")
	
OpenDB()

	Set rstLogin = Server.CreateObject("ADODB.Recordset")
	strSQL = "SELECT Last_Name, First_Name, WebRegistrationCode, employee_id, internetSMTPemail as vEmail, ModifyUser, Status  from EmployeeMaster WHERE Status = 1 AND " 
	strSQL = strSQL	& "employee_id = '" & sRegCode& "'" 



 response.write(strSQL)
rstLogin.Open strSQL, cn

	
If NOT rstLogin.EOF Then


	Set rsOrder = Server.CreateObject("ADODB.Recordset")
	OpenDB()

	StrSQL = "UPDATE EmployeeMaster SET InternetPassword = '0'  WHERE Employee_ID = '" & sRegcode &"'"
	rsOrder.Open strSQL, cn

	
 	
		%>
		<Font size = 3>
		&nbsp;Hello <% response.write(rstLogin("First_Name")) %>&nbsp;<% response.write(rstLogin("Last_Name")) %> 
		</Font>
		<%
	
End If


sBody = "Dear " & rstLogin("First_Name") & "," & vbCrlf & vbCrlf _
		& "You have requested to reset your password. " & vbCrlf & vbCrlf _
		& "Please click on this link to confirm. " _
		& "http://" & Request.ServerVariables("server_name") & "/index.php?pagename=createpassword&sregcode=64V" & rstLogin("WebRegistrationCode") &"4331-2120 ." & vbCrlf & vbCrlf _
		& "If you did not request a password change please ignore this link " & vbCrlf & vbCrlf _

		& "Thank you for letting us represent you." & vbCrlf & vbCrlf _	
				
		& "Sincerely,"& vbCrlf & vbCrlf _
		& "the i creatives family" & vbCrlf & vbCrlf
	
		
			
				sEmail = rstLogin("vEmail")	
				'	sEmail = "junk2@tempart.com"	

			Dim objCDO
			Set objCDO = Server.CreateObject("cdo.message")


		' objCDO.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1 'basic (clear-text) authentication
		' objCDO.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/sendusername") ="Form_Mail@icreatives.com"
		' objCDO.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/sendpassword") ="MyFormMail2"

	  	' objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
	   	' objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "localhost"
		' objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = 25
		' objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1
	   	' objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpusessl") = False
	   	' objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 60
	   	' objCDO.Configuration.Fields.Update


	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
   	' objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverpickupdirectory") = "d:\inetpub\mailroot\pickup"
      	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "smtp.1and1.com"
	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = 25
	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1	
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendusername") = "exchange@icreatives.comm"
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendpassword") ="Call1888icreate!"
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpusessl") = False
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 60
   	objCDO.Configuration.Fields.Update

		
	

			objCDO.To = sEmail
			objCDO.Bcc = "Password_Reset@BlindEmail.com, accounting_mail@icreatives.com"
			objCDO.From = "Password_Reset@icreatives.com"
			objCDO.Subject =  "Password Reset"			
			objCDO.TEXTBody = sBody
			objCDO.Send
			Set objCDO = Nothing			
		
rstLogin.Close



%>
<CENTER>
<H1>Mail Sent to <% =sEmail %> </H1>

</body>
</html>
