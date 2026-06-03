<?php	
$sScreen = "<!DOCTYPE html PUBLIC |-//W3C//DTD XHTML 1.0 Transitional//EN| |http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd|>" ;
$sScreen = $sScreen . "<html xmlns=|http://www.w3.org/1999/xhtml|> ";
$sScreen = $sScreen . "<head> ";
$sScreen = $sScreen . "<meta http-equiv=|Content-Type| content=|text/html; charset=utf-8| /> ";
$sScreen = $sScreen . "<title>ICreatives</title> ";
$sScreen = $sScreen . "</head> "  . "\r\n"; 

$sScreen = $sScreen . "<body> "  . "\r\n"; 

$sScreen = $sScreen . "<table width=|790| border=|0| align=|center| cellpadding=|0| cellspacing=|0| style=|font-family:Arial, Helvetica, sans-serif; font-size:12px; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0 border-spacing: 0; |> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td width=|570| align=|left| valign=|top| > ";
      $sScreen = $sScreen . "<!-- Content -->    "  . "\r\n"; 
      $sScreen = $sScreen . "<table width=|570| border=|0| cellpadding=|0| cellspacing=|0| style=|border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0;|> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-top.gif| width=|15| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-top.gif| width=|540| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-top.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-left.gif| width=|15| height=|665| border=|0| /></td> ";
                $sScreen = $sScreen . "<td align=|center| valign=|top| style=|padding:10px 0;| background=|https://www.icreatives.com/webtime/email/images/assets/background.gif|> ";
                $sScreen = $sScreen . "<table width=|500| border=|0| align=|center| style=|border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0;|> ";
  $sScreen = $sScreen . "<tr> ";
     $sScreen = $sScreen . "<td width=|260|  height=|170| align=|left| valign=|top|><a href=|https://www.icreatives.com| border=0><img src=|https://www.icreatives.com/webtime/email/images/assets/logo.gif| width=|110| height=|123| border=|0| alt=|i creatives logo| style=|margin:15px 0px| /></td> ";
//   $sScreen = $sScreen . "<td width=|260|  height=|170| align=|left| valign=|top|><a href=|https://www.icreatives.com| border=0><img src=|cid:logo.gif| width=|110| height=|123| border=|0| alt=|i creatives logo| style=|margin:15px 0px| /></a></td> ";
    $sScreen = $sScreen . "<td width=|230| align=|right| valign=|top| > ";
        $sScreen = $sScreen . "<h1  style=|color:#a4100c; margin:0; padding:2px; font-size:30px|>TimeSheet</h1> ";
        $sScreen = $sScreen . "<b>Week Ending:</b> " . date('m/d/Y',StrToTime($_REQUEST["WKEND"])) . " ";
    $sScreen = $sScreen . "</td> ";
  $sScreen = $sScreen . "</tr> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td align=|left|><b style=|color:#a4100c|>Contractor: </b>" . $sTalentFirst . "</td> ";
   
	if (!empty($_REQUEST["PoNumber"])) {
   		$sScreen = $sScreen . "<td align=|right|><b>PO Number:  " . $_REQUEST["PoNumber"] . "</b></td> ";
	}
    
    $sScreen = $sScreen . "</tr> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td height=|40px| align=|left| valign=|top|><b style=|color:#a4100c|>Customer: </b> " . str_replace("_"," ",$_REQUEST["JOB"]) . "</td> ";
    $sScreen = $sScreen . "<td>&nbsp;</td> ";
  $sScreen = $sScreen . "</tr> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td height=|220| colspan=|2| valign=|top|> ";
    $sScreen = $sScreen . "<table width=|500| border=|0| cellpadding=|4| style=|padding:4px 0px| style=|border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0;|> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td width=|136| align=|left|><b>Day</b></td> ";
    $sScreen = $sScreen . "<td width=|90| align=|left|><b>In</b></td> ";
    $sScreen = $sScreen . "<td width=|90| align=|left|><b>Out</b></td> ";
    $sScreen = $sScreen . "<td width=|90| align=|left|><b>Break</b></td> ";
    $sScreen = $sScreen . "<td width=|42| align=|right|><b>Total</b></td> ";
  $sScreen = $sScreen . "</tr> ";
  $sScreen = $sScreen . "<tr><td colspan=|5| style=|border-top:solid 1px #ccc; height:5px|></td> </tr> "  . "\r\n"; 

for ($x = 1; $x <= 7; $x++) {
 $sScreen = $sScreen . "<tr>  "   ;
    $sScreen = $sScreen . "<td align=|left| ><b>". jddayofweek($x-2,1) . "</b></td>  "   ;
		$sScreen = $sScreen . "<td align=|left|><span style=|color:#666|>" . ($_REQUEST["TimeInHr" . $x])  . ":" . (($_REQUEST["TimeInMin" . $x]==0)?"00":$_REQUEST["TimeInMin" . $x]). " " . $_REQUEST["TimeInAmPm" . $x]  .  "</span></td>  " ;  	    	
		$sScreen = $sScreen . "<td align=|left|><span style=|color:#666|>" . ($_REQUEST["TimeOutHr" . $x]) . ":" . (($_REQUEST["TimeOutMin". $x]==0)?"00":$_REQUEST["TimeOutMin". $x]). " " . $_REQUEST["TimeOutAmPm". $x]  .  "</span></td>  " ;		
    $sScreen = $sScreen . "<td align=|left|><span style=|color:#666|>" . $_REQUEST["BreakHr" . $x] . ":" .     (($_REQUEST["BreakMin"  . $x]==0)?"00":$_REQUEST["BreakMin"  . $x]) . "</span></td>  "  ; 
    $sScreen = $sScreen . "<td align=|right|><b>" .  TotalHrs($x)  . "</b></td>  "   ;
 $sScreen = $sScreen . " </tr>  "   ;
}


    $sScreen = $sScreen . "<tr><td colspan=|5| style=|border-bottom:solid 1px #ccc; height:5px|></td> </tr> "  . "\r\n"; 
  $sScreen = $sScreen . "<tr> ";
  
  
  
    $sScreen = $sScreen . "<td colspan=|2| align=|left|><b style=|color:#a4100c|>Assignment is: </b> ";

	if ($_REQUEST["Continuing"] == "1") { 
		$sScreen = $sScreen . "Continuing";
	} Else {
		$sScreen = $sScreen . "Over";
	}

	 $sScreen = $sScreen . "</td> ";
    $sScreen = $sScreen . "<td colspan=|3| align=|right|><b style=|color:#a4100c|>Total: </b><b> " . GrandTotal(). "</b></td> ";
    $sScreen = $sScreen . "</tr> ";
    $sScreen = $sScreen . "</table> ";

    
    $sScreen = $sScreen . "</td> ";
    $sScreen = $sScreen . "</tr> ";

  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td height=|50px| colspan=|2| align=|left| valign=|bottom|><h2 style=|font-size:14px;  margin:0 ; padding:0|>WAITING FOR APPROVAL</h2></td> ";
  $sScreen = $sScreen . "</tr> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td colspan=|2| align=|left| valign=|top|> ";
    $sScreen = $sScreen . "<table border=|0| width=|400| cellpadding=|0| cellspacing=|0| style=|font-family:Arial, Helvetica, sans-serif; font-size:12px; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0 border-spacing: 0;|>";
      $sScreen = $sScreen . "<tr> ";
        $sScreen = $sScreen . "<td width=|195| align=|center| background=|/webtime/email/images/assets/click-here.gif| height=|38| bgcolor=|#9C0000|> ";
          $sScreen = $sScreen . "<a href=|". "https://www.icreatives.com" ."/webtime/manatal_approve.php?&varib=1" . $MyNewRandomNum . "-" . $_REQUEST["xORDER"] . "| style=|color:#FFFFFF|><b>CLICK HERE TO APPROVE</B></a> ";
        $sScreen = $sScreen . "<td width=|5|></td> ";
        $sScreen = $sScreen . "<td width=|195| align=|center| background=|/webtime/email/images/assets/click-here.gif| height=|38| bgcolor=|#9C0000|> ";
// $_SERVER['HTTP_HOST']
          $sScreen = $sScreen . "<a href=|". "https://www.icreatives.com" ."/webtime/manatal_approve.php&varib=0" . $MyNewRandomNum . "-" . $_REQUEST["xORDER"] . "| style=|color:#FFFFFF|><B>CLICK HERE TO DECLINE</B></a> ";
          $sScreen = $sScreen . "</b></font></td> ";
        $sScreen = $sScreen . "<td width=|5|></td> ";
      $sScreen = $sScreen . "</tr> ";
    $sScreen = $sScreen . "</table>     "       ;   
          
     $sScreen = $sScreen . "</td> ";
    $sScreen = $sScreen . "</tr> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td colspan=|2| align=|left| valign=|bottom| height=|20px|><b>" . $_REQUEST["xORDER"] ."</b></td> ";
  $sScreen = $sScreen . "</tr> ";
                $sScreen = $sScreen . "</table> ";
			    
                $sScreen = $sScreen . "</td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-right.gif| width=|15| height=|665| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-bottom.gif| height=|15| width=|540| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
      $sScreen = $sScreen . "</table> ";

    $sScreen = $sScreen . "</td> ";
    $sScreen = $sScreen . "<td width=|220| valign=|top|> ";
    	
      $sScreen = $sScreen . "<!-- CTA1 -->     "  . "\r\n"; 
      $sScreen = $sScreen . "<table width=|220| border=|0| cellpadding=|0| cellspacing=|0| style=|border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0;|> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td align=|left| valign=|top|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-top.gif| width=|15| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-top.gif| width=|190| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td align=|right| valign=|top|  style=|background:url(/email/images/images/sides/side-right.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-top.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-left.gif| width=|15| height=|85| border=|0| /></td> ";
                $sScreen = $sScreen . "<td  valign=|top|  style=|font-size:11px; color:#666;padding:5px;|> ";
                 
                   $sScreen = $sScreen . "<b style=|color:#a4100c; font-size:12px|>QUESTIONS ABOUT THIS TIMESHEET?</b> ";
                   $sScreen = $sScreen . "Have questions about this timesheet?  ";
                   $sScreen = $sScreen . "Please call us: <b style=|color:#000|>888.427.3283</b> ";
                   $sScreen = $sScreen . "or drop us a note @<br /> ";
                   $sScreen = $sScreen . "<a href=|https://www.icreatives.com/contact-us/| style=|color:#a4100c; font-weight:bold| title=|contact Us|>creatives.com/contact-us</a> ";
                 
                $sScreen = $sScreen . "</td> ";
            
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-right.gif| width=|15| height=|85| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td align=|left| valign=|bottom|  style=|background:url(/email/images/images/sides/side-left.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-bottom.gif| width=|190| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td align=|right| valign=|bottom|  style=|background:url(/email/images/images/sides/side-right.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
      $sScreen = $sScreen . "</table> ";
      
      
      $sScreen = $sScreen . "<!-- CTA2 -->    "  . "\r\n"; 
	  $sScreen = $sScreen . "<!-- place back when we do featured again";
      $sScreen = $sScreen . "<table width=|220| border=|0| cellpadding=|0| cellspacing=|0| style=| margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0 border-spacing: 0;|> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td align=|left| valign=|top|  style=|background:url(/email/images/images/sides/side-left.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-top.gif| width=|15| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-top.gif| width=|190| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td align=|right| valign=|top|  style=|background:url(/email/images/images/sides/side-right.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-top.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-left.gif| width=|15| height=|260| border=|0| /></td> ";
                $sScreen = $sScreen . "<td valign=|top|  style=|font-size:11px; ;padding:15px 5px; line-height:12px; color:#666;|> ";
                         $sScreen = $sScreen . "<h1  style=|color:#a4100c; font-size:25px; margin:0; padding:0; line-height:20px|>FEATURED</h1> ";
                         $sScreen = $sScreen . "<h1  style=|color:#a4100c; font-size:25px; margin:0; padding:0;line-height:20px; margin-bottom:5px|>CREATIVE</h1> "   ;               
                         $sScreen = $sScreen . "World&#39;s first talking portfolio<br /> ";
                         $sScreen = $sScreen . "";
                        
                         $sScreen = $sScreen . "<div align=|center|><img src=|https://www.icreatives.com/webtime/email/images/assets/featured.jpg| style=|padding:10px| width=|96| height=|82| border=|0|   /></div> ";
                        
                         $sScreen = $sScreen . "Listen to the talent, get to learn more about the thought processes of the talent, and personality, that only sound can bring. Another i creatives first. Click  ";
                          $sScreen = $sScreen . "<a href=|https://www.icreatives.com/featured-talent/| style=|color:#a4100c; font-weight:bold| title=|Feature Creative|>here</a> ";
                         $sScreen = $sScreen . "to experience our featured artists.  ";
                         $sScreen = $sScreen . "</td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-right.gif| width=|15| height=|260| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td align=|left| valign=|bottom|  style=|background:url(/email/images/images/sides/side-left.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
                  $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-bottom.gif| width=|190| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td align=|right| valign=|bottom|  style=|background:url(/email/images/images/sides/side-right.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
      $sScreen = $sScreen . "</table>  -->";
     
      $sScreen = $sScreen . "<!-- CTA3 -->     "  . "\r\n"; 
      $sScreen = $sScreen . "<table width=|220| border=|0| cellpadding=|0| cellspacing=|0| style=|border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0;|> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td align=|left| valign=|top|  style=|background:url(/email/images/images/sides/side-left.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-top.gif| width=|15| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-top.gif| width=|190| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td align=|right| valign=|top|  style=|background:url(/email/images/images/sides/side-right.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-top.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr> ";
                $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-left.gif| width=|15| height=|260| border=|0| /></td> ";
                $sScreen = $sScreen . "<td style=|font-size:11px; ;padding:15px 5px; line-height:12px; color:#666;| > ";
                
                         $sScreen = $sScreen . "<h1  style=|color:#a4100c; font-size:25px; margin:0; padding:0; line-height:20px|>OUR</h1> ";
                         $sScreen = $sScreen . "<h1  style=|color:#a4100c; font-size:25px; margin:0; padding:0;line-height:20px; margin-bottom:5px|>COMMERCIAL</h1> ";
                         $sScreen = $sScreen . "<A Href=|http://youtu.be/UyRqMAP6pG8| border=|0|>";
                         $sScreen = $sScreen . "<div align=|center|><img src=|https://www.icreatives.com/webtime/email/images/assets/commercial.jpg| style=|padding:6px 0|  width=|178| height=|128| border=|0|   /></div> ";
                         $sScreen = $sScreen . "</A>";
                         $sScreen = $sScreen . "See our new commercial. ";
                $sScreen = $sScreen . "</td> ";
                $sScreen = $sScreen . "<td ><img src=|https://www.icreatives.com/webtime/email/images/sides/side-right.gif| width=|15| height=|260| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
              $sScreen = $sScreen . "<tr > ";
                $sScreen = $sScreen . "<td align=|left| valign=|bottom|  style=|background:url(/email/images/images/sides/side-left.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
                 $sScreen = $sScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-bottom.gif| width=|190| height=|15| border=|0| /></td> ";
                $sScreen = $sScreen . "<td align=|right| valign=|bottom|  style=|background:url(/email/images/images/sides/side-right.gif) repeat-y|> ";
                    $sScreen = $sScreen . "<img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
              $sScreen = $sScreen . "</tr> ";
      $sScreen = $sScreen . "</table> ";
      
$sScreen = $sScreen . "</td> "  . "\r\n"; 
  $sScreen = $sScreen . "</tr> ";
  $sScreen = $sScreen . "<tr> ";
    $sScreen = $sScreen . "<td colspan=|2|> ";

      $sScreen = $sScreen . "<table width=|790| height=|100| border=|0| cellpadding=|0| cellspacing=|0| style=|border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0;|> <tr> ";
          $sScreen = $sScreen . "<td align=|left| valign=|top|bgcolor=|#9C0000|><img src=|https://www.icreatives.com/webtime/email/images/footer/footer-left.gif| width=|15| height=|100| border=|0| /></td> ";
          $sScreen = $sScreen . "<td width=|760| height=|100| align=|center| valign=|middle| style=| font-weight:bold ;font-size:11px; color:#FFF | background=|/webtime/email/images/footer/footer-repeat.gif| bgcolor=|#9C0000|  > ";
          $sScreen = $sScreen . "<P><font color=|#FFFFFF|>This <A style=|color:#FFFFFF| Href=|https://www.icreatives.com|>i creatives</A> time approval is generated once our talent has completed an online timesheet for work performed for ";
          $sScreen = $sScreen . "your company. Please click on the &quot;Approve&quot; or &quot;Decline&quot; link. Once a button is clicked, you will be asked to sign by typing in your full name. <P>After you click on the &quot;submit&quot; button, an &quot;approved&quot; timesheet is immediately emailed for your records. ";
	  $sScreen = $sScreen . "</font>"                    ;
	  $sScreen = $sScreen . "</td> ";
          $sScreen = $sScreen . "<td align=|right| valign=|top| bgcolor=|#9C0000|><img src=|https://www.icreatives.com/webtime/email/images/footer/footer-right.gif| width=|15| height=|100| border=|0| /></td> ";
      $sScreen = $sScreen . "</tr></table> ";
$sScreen = $sScreen . "</td> ";
$sScreen = $sScreen . "</tr> ";
$sScreen = $sScreen . "</table> <P> <P>" . "\r\n"; 

$sScreen = $sScreen . "<table width=|790| border=|0| align=|center| cellpadding=|0| cellspacing=|0| style=|font-family:Arial, Helvetica, sans-serif; font-size:12px; border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0;|> <tr><td> ";
$sScreen = $sScreen . "<DIV align=justify><FONT size = |1| color=|#436376| width = |400|> ";

$sScreen = $sScreen . "Terms: Being duly authorized on behalf of the below customer, the undersigned hereby (1) certifies that the total hours shown are true and correct, the work was performed ";
$sScreen = $sScreen . "satisfactorily and my signature is authorization to bill the below named customer for the hours shown, (2) constitutes an agreement between i creatives and ";
$sScreen = $sScreen . "customer with respect to the services performed hereunder and any future services that (a) i creatives&#39; terms are that an invoice rendered for these hours shall be net due upon receipt and ";
$sScreen = $sScreen . "if not paid within ten days a late payment (liquidated damages) charge of one and one-half percent per month (which is an annual percentage rate of eighteen percent) will be calculated ";
$sScreen = $sScreen . "on the balance shown on our statement as being past due and payable; (b) that all i creatives&#39; temporary personnel represent a substantial investment to i creatives. We agree not to " . "\r\n"; 
$sScreen = $sScreen . "employ directly, or refer for hiring, any i creatives temporary personnel until one year from the date last worked at our firm as a i creatives temporary person, unless we reimburse i creatives ";
$sScreen = $sScreen . "20% of annual salary in liquidated damages for the replacement costs of like personnel; (c) customer shall not entrust i creatives personnel with unattended premises, cash negotiables ";
$sScreen = $sScreen . "and other valuables, or authorize such personnel to operate machinery or motor vehicles without prior written permission from i creatives in each instance; (d) should a lawsuit ";
$sScreen = $sScreen . "be necessary to enforce this agreement, the customer and the individual, agent or company official signing this time card shall be jointly and severably liable for any amount due ";
$sScreen = $sScreen . "or the liquidated damages, venue is waived and suit may be brought in Miami, Florida. Should it be necessary for i creatives to employ an attorney at law to collect any amount due or ";
$sScreen = $sScreen . "the liquidated damages, then we agree to pay all reasonable attorney&#39;s fees plus court costs. " . "\r\n"; 

$sScreen = $sScreen . "</FONT><BR> ";
$sScreen = $sScreen . "</DIV>";
      $sScreen = $sScreen . "</td></tr></table> ";


$sScreen = $sScreen . "</body> ";
$sScreen = $sScreen . "</html> ";
?>