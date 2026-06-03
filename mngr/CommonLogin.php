<%
Option Explicit
'---- CursorTypeEnum Values ----
Const adOpenForwardOnly = 0
Const adOpenKeyset = 1
Const adOpenDynamic = 2
Const adOpenStatic = 3

'---- LockTypeEnum Values ----
Const adLockReadOnly = 1
Const adLockPessimistic = 2
Const adLockOptimistic = 3
Const adLockBatchOptimistic = 4

'---- CommandTypeEnum Values ----
Const adCmdUnknown = &H0008
Const adCmdText = &H0001
Const adCmdTable = &H0002
Const adCmdStoredProc = &H0004
Const adCmdFile = &H0100
Const adCmdTableDirect = &H0200


' Database connection
dim uname
dim password
' uname = AESDecryptString(request.querystring("varib1"))
' uname = AESDecryptString("XXXXXXXXXXXXXXXXXXXXX")

uname = request.querystring("varib1")
password = request.querystring("varib2")

Response.Write "Logged in as: " & uname & "<br /> <br />"
' uname = "alexn"
' password = "AlexTest"
Dim cn

'Open database connection
Sub OpenDB()
	dim DSN
	set cn=Server.CreateObject("ADODB.Connection")
'	DSN="provider=sqloledb;Server=ic-eempact;address=brodyville.dynalias.com:19997;Database=EMPACT_001_PROD_PDI;UID=Webbie;PWD=unclemarc"
'	DSN="provider=MSDASQL;DRIVER={SQL Server};SERVER=u17899881.onlinehome-server.com,1433;Initial Catalog=EMPACT_001_PROD_PDI;UID=sa;PWD=ic2eempact!"
	DSN="provider=MSDASQL;DRIVER={SQL Server};SERVER=5de1f42.online-server.cloud,1433;Initial Catalog=EMPACT_001_PROD_PDI;UID=" & uname & ";PWD=" & password
'	DSN="provider=MSDASQL;DRIVER={ODBC Driver 11 for SQL Server};SERVER=5de1f42.online-server.cloud,1433;Initial Catalog=EMPACT_001_PROD_PDI;UID=" & uname & ";PWD=" & password



	cn.Open(DSN)
	' Response.Write "Database opened successful! <br>"
End Sub
'End of Open database connection


'Close database conection
Sub CloseDB()
	cn.Close
	Set cn= Nothing
End Sub
'End of Close database conection

' Open Recordset
Function OpenRS(RecordSet, SQL, ShowError)
  Dim ErrorMessage, Result
  Result = Empty
  Set RecordSet = Server.CreateObject("ADODB.Recordset")
  On Error Resume Next
  RecordSet.Open SQL, cn, adOpenForwardOnly, adLockReadOnly, adCmdText
  ErrorMessage = CCProcessError(cn)
  If NOT IsEmpty(ErrorMessage) Then
    If ShowError Then
      ' Result = "SQL: " & CommandObject.CommandText & "<br>" & "Error: " & ErrorMessage & "<br>"
	Result = "We are under maintainence. Please check back in 5 hours " & "<br>"
    Else
      Result = "Database error.<br>"
    End If
  End If
  On Error Goto 0
  OpenRS = Result
End Function
'End CCOpenRS

'CCProcessError 
Function CCProcessError(Connection)
  If Connection.Errors.Count > 0 Then
    If TypeName(Connection) = "Connection" Then
      CCProcessError = Connection.Errors(0).Description & " (" & Connection.Errors(0).Source & ")"
    Else
      CCProcessError = Connection.Errors.ToString
    End If
  ElseIf NOT (Err.Description = "") Then
    CCProcessError = Err.Description
  Else
    CCProcessError = Empty
  End If
end Function
'End CCProcessError

'Print 
Sub Print(Value)
  Response.Write CStr(Value)
End Sub
'End Print

'IIf @0-E12349E2
Function IIf(Expression, TrueResult, FalseResult)
  If CBool(Expression) Then
    If IsObject(TrueResult) Then _
      Set IIf = TrueResult _
    Else _
      IIf = TrueResult
  Else
    If IsObject(FalseResult) Then _
      Set IIf = FalseResult _
    Else _
      IIf = FalseResult
  End If
End Function
'End IIf

'Format  syntax: string = Format(expression, format)
Function Format(byVal expression, byVal strFormat) 
On Error Resume Next  
Select Case lcase(strFormat )  
	Case "general date"  
		Format = FormatDateTime(expression, 0)  
	Case "long date"  
		Format = FormatDateTime(expression, 1)  
	Case "short date"  
		Format = FormatDateTime(expression, 2)  
	Case "long time"  
		Format = FormatDateTime(expression, 3)  
	Case "short time"  
		Format = FormatDateTime(expression, 4)  
	Case "general number"  
		Format = Replace(expression, ",", "" )
	Case "currency"  
		Format = FormatCurrency(expression, 2)  
	Case "fixed"  
		Format = Replace( FormatNumber(expression,2, -1), ",", "" )  
	Case "standard"  
		Format = FormatNumber(expression, 2, -1)  
	Case "percent"  
		Format = FormatPercent(expression, 2)  
	Case "yes/no"  
		expression = cLng(expression)  
		If expression = 0 then 
			Format = "No"   
		else 
			Format = "Yes" 
		end if
	Case "true/false"  expression = cLng(expression)  
		If expression = 0 then   
			Format = "False"   
		else  
			Format = "True"  
		end if  
	Case "on/off"  expression = cLng(expression)  
		If expression = 0 then   
			Format = "Off"   
		else 
			Format = "On"  
		end if  
  Case Else  Format = expression  
  End Select  
On Error GoTo 0
End Function

'Short Time: 10:19
'Long Time: 10:19:23 AM 
'Short Date: 2/9/2009 
'Long Date: Monday, February 09, 2009 
'General Date: 2/9/2009 10:19:23 AM 
'General Number: 1741534.54205848 
'Currency: $1,741,534.54 
'Fixed: 1741534.54 
'Standard: 1,741,534.54 
'Percent: 174,153,454.21% 
'Yes/No: Yes 
'True/False: True 
'On/Off: On
'End Format

'Get Saturday is a start of week 
Function StartWeek(d)
	if weekday(d)<>7 then 
		StartWeek = (dateadd("d", - weekday(d), d))
	Else
		StartWeek = d
	End if
End Function

'Get Friday is a end of week 
Function EndWeek(d)
	if weekday(d)<>6 then 
		EndWeek = (dateadd("d", 6 - weekday(d), d))
	Else
		EndWeek = d
	End if
End Function

Function AESDecryptString(text)
		Dim objCrypt
		Set objCrypt = CreateObject("Chilkat_9_5_0.Crypt2")
		'Set objCrypt = CreateObject("Chilkat.Crypt2")
		With objCrypt
			.UnlockComponent("LONDONCrypt_SkO3CEBVVREY")
			.CryptAlgorithm = "rijndael"
			.CipherMode = "ECB"
			.EncodingMode = "base64"
			.SetEncodedKey "GpAeScw2LQWaYnRddbh4cPksce76gQ1z", "ascii"
			.Charset = "utf-8"
			AESDecryptString = .DecryptStringENC(text)
		End With
End Function
%>
