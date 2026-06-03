<?
		Set objMail = Server.CreateObject("CDO.Message") 
		Set objConfig = Server.CreateObject("CDO.Configuration") 

	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
'   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverpickupdirectory") = "d:\inetpub\mailroot\pickup"
      	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "smtp.1and1.com"
	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = 587
	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendusername") = "exchange@icreatives.com"
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/sendpassword") ="Call1888icreate!"
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpusessl") = False
   	objCDO.Configuration.Fields.Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 60
   	objCDO.Configuration.Fields.Update	
		
		Set objMail.Configuration = objConfig 		
		objMail.MimeFormatted = True	
			
		 	objMail.To = "Stevenc@icreatives.com"
			objMail.Bcc = "TimeSheet_gentle_Reminder@BlindEmail.com"
			sSubject =  "++ eTimesheet Reminder for Week Ending " & DATE() - weekday(date()+1) & " ++"			
			objMail.From = "ysmith@icreatives.com"
			objMail.Subject =  sSubject
			objMail.TEXTBody = "Hello steven"	
			objMail.Send
		Set objMail=Nothing 
		Set objConfig=Nothing 
		

?>

hello there		