<%  Option Explicit %>

<!-- #include file="common.asp"-->	

<%
Server.ScriptTimeout = 1200
response.write "<H1>Sending Statements</H1>"
Dim I
Dim PDF
Dim DOC
Dim URL
Dim BPNum
Dim Paid
DIM Image
DIM Width
DIM Height
DIM Page
DIM FileName
DIM objConfig
Dim InvNum
Dim text
Dim AltEmail
Dim ToEmail
	Dim TifString
	Dim TifArray
Dim First_Name
Dim Last_Name
Dim UserEmail
Dim UserPhone


First_Name = Request.Form("First_Name")
Last_Name = Request.Form("Last_Name")
UserPhone = Request.Form("UserPhone")
UserEmail = Request.Form("UserEmail")


AltEmail = trim(request.form("AltEmail"))
' AltEmail = "Stevenc@icreatives.com"


Dim rsGenInv
Dim GenSQL
Dim rsGenTif
Dim TifSQL
Dim BillNumArray
Dim EmailArray
Dim Barray
Dim Earray
Dim Checked

BillNumArray = request("BillNumArray")
BArray = split(BillNumArray,",")
EmailArray = request("EmailArray")
EArray = split(EmailArray,",")

I = 0
for I=0 to ubound(BArray) 
checked = "Check" & I

  If request(checked) = "on" then

 response.write(" " & checked & " " & BArray(I) & " " & EArray(I) & "<BR />")


   Set Pdf = Server.CreateObject("Persits.Pdf")
   Set Doc = Pdf.CreateDocument

   URL = "HTTP://192.168.11.100/Mngr/statement.asp?StateNum="& ltrim(Barray(I))

 response.write(url)


   Doc.ImportFromUrl URL, "Scale = .80; hyperlinks=true; drawbackground=true"


'-----------------------------------------------------------------


Filename = Doc.Save( Server.MapPath(BArray(I) &".pdf"), False ) 


' response.write(filename)
 response.write(EArray(I))
' xxx()

	Dim objCDO
	Set objCDO = Server.CreateObject("cdo.message")

      	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
     	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "smtpout.secureserver.net"
	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendtls") = 1
	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = 587
	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendusername") = "exchange@icreatives.co"
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendpassword") ="Call1888icreate!"
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpusessl") = False
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 60
   	objCDO.Configuration.Fields.Update	
	
		objCDO.MimeFormatted =true
			if AltEmail <> "" then
				ToEmail = AltEmail
	'			' ToEmail = "stevenc@icreatives.com" ' delete this line when tested.

			Else
	'			' ToEmail = "stevenc@icreatives.com" ' delete this line when tested.
				ToEmail = replace(EArray(I),"!",",") ' fix the comma from statement1.asp
			End If
			objCDO.To = ToEmail
			objCDO.BCC = "statement@blindemail.com, andreaa@icreatives.com"	
			objCDO.From = "statement@icreatives.com"
			objCDO.Subject =   "++ Statement for " & right(BArray(I),5) &" from icreatives ++"

			' objCDO.TEXTBody = "your statement is attached"

Text = "Dear Valued Customer" & ","& vbCrLf & vbCrLf

Text = Text & "Attached please find a multi-page Acrobat formatted Statement." & vbCrLf & vbCrLf
Text = Text & "You may view the timesheet and invoice by clicking on the invoice number on the PDF" & vbCrLf & vbCrLf
Text = Text & "If there is anything else you may need, or have any questions about this invoice, please do not hesitate to contact me." & vbCrLf & vbCrLf
Text = Text & "Thank you so much for your business." & vbCrLf & vbCrLf 

Text = Text & "Sincerely," & vbCrLf & vbCrLf & vbCrLf & vbCrLf
Text = Text & First_Name & " " & Last_Name & vbCrLf & vbCrLf
Text = Text & "i creatives staffing" & vbCrLf
Text = Text & UserEmail & vbCrLf
Text = Text & UserPhone & vbCrLf & vbCrLf& vbCrLf & vbCrLf

		objCDO.TEXTBody = text			


		'Set objCDO.Configuration = objConfig 		
		objCDO.MimeFormatted = true

		'response.write msgBody

					' objcdo.ContentTransferEncoding = "base64"

					objCDO.AddAttachment(Server.MapPath(filename))
					' response.write("filename: " & filename)

	 	 objCDO.Send 

		If Err.Number = 0 Then
  			 Response.Write("<br>Mail sent to: " &  ToEmail & " " & UserEmail & " " & UserPhone )
		Else
			' response.write msgBody
  			Response.Write("Error sending mail. Code: " & Err.description)
  			Err.Clear
		End If

		Set objCDO=Nothing 
		Set objConfig=Nothing 
		
				'clean up time	


dim fs,f
set fs=Server.CreateObject("Scripting.FileSystemObject")
set f=fs.GetFile(Server.MapPath(filename))
f.Delete
set f=nothing
set fs=nothing


End If



Next


%>
