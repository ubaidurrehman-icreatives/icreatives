<?php
/*
function convertTime($dec)
{
    // start by converting to seconds
    $seconds = ($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    return lz($hours).":".lz($minutes);
}
*/

// lz = leading zero
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}	

Function Time2AmPm($PassTime)  {
	$PassHour = ( SubStr($PassTime,0,2) ) ;
	If ( $PassHour > 12 ) {
		$PassTime = convertTime($PassTime - 12) . " PM" ;
		// $PassTime = Str_Pad( ($PassHour - 12) ,2,"0",STR_PAD_LEFT ) . ":" . SubStr($PassTime,3) . " PM" ;
	} else {
		// $PassTime . " AM" ;
		$PassTime = convertTime($PassTime) . " AM" ;
	}
Return $PassTime ;
}
		
	
// $sScreen = "<!DOCTYPE html PUBLIC |-//W3C//DTD XHTML 1.0 Transitional//EN| |http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd|>" ;
// $sScreen = $sScreen . "<html xmlns=|http://www.w3.org/1999/xhtml|> ";
// $sScreen = $sScreen . "<head> ";
// $sScreen = $sScreen . "<meta http-equiv=|Content-Type| content=|text/html; charset=utf-8| /> ";
// $sScreen = $sScreen . "<title>ICreatives</title> ";
// $sScreen = $sScreen . "</head> "  . "\r\n"; 

// $sScreen = $sScreen . "<body> "  . "\r\n"; 

$sScreen = $sScreen . "
<table width=|600| border=|0| align=|center| cellpadding=|0| cellspacing=|0| style=|align:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; |>
	<tr>
		<td width=|600| align=|left| valign=|top| >
			<!-- Content --> \r\n
			<table style=| width:600px; border-spacing: 5px; border-radius:20px 20px 20px 20px;border: 2px solid #A50F14; |>
				<tr >
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align=|center| valign=|top| style=|padding:10px 0;| background=|https://www.icreatives.com/webtime/email/images/assets/background.gif|>
						<table width=|600| border=|0| align=|center|> 
							<tr>
								<td width=|260|  height=|170| align=|left| valign=|top|><a href=|https://www.icreatives.com| border=0><img src=|https://www.icreatives.com/webtime/email/images/assets/logo.gif| width=|110| height=|123| border=|0| alt=|i creatives logo| style=|margin:15px 0px| />
								</td>
								<td width=|230| align=|right| valign=|top| >
									<h1  style=|font-family:Arial; color:#a4100c; margin:0; padding:2px; font-size:30px|>TimeSheet</h1>
										<b>Week Ending:</b> " . date('m/d/Y',StrToTime($row2["WKEND"])) . "
								</td>
							</tr>
							<tr>
								<td align=|left|><b style=|color:#a4100c|>Contractor: </b>" . $row2["FIRST"] . "
								</td>"
								if (!empty($row2["PO"])) {
$sScreen = $sScreen . 				"<td align=|right|><b style=|color:#a4100c|>PO Number:  </b>" . $row2["PO"] . "</td> ";
								} else {														
$sScreen = $sScreen .				"<td>&nbsp;</td>";}
$sScreen = $sScreen . 		"</tr>
							<tr>
								<td  align=|left| valign=|top|><b style=|color:#a4100c|>Customer: </b> " . str_replace("_"," ",$row2["JOB"]) . "
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align=|left| valign=|top|><b style=|color:#a4100c|>Position: </b> " . str_replace("_"," ",$row2["ITEM"]) . "
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td height=|220| colspan=|2| valign=|top|>
									<table width=|600| border=|0| cellpadding=|4| style=|padding:4px 0px|>
										<tr>
											<td width=|136| align=|left|><b>Day</b></td>
											<td width=|90| align=|right|><b>In</b></td>
											<td width=|90| align=|right|><b>Out</b></td>
											<td width=|90| align=|right|><b>Break</b></td>
											<td width=|42| align=|right|><b>Total</b></td>
										</tr>
										<tr>
											<td colspan=|5| style=|border-top:solid 1px #ccc; height:5px|></td>
										</tr> \r\n";
$grandtotal = 0;
for ($x = 1; $x <= 7; $x++) {
	$sScreen = $sScreen .				"<tr>  "   ;
	$sScreen = $sScreen . 							"<td align=|left| ><b>". jddayofweek($x-2,1) . "</b></td>  "   ;
	$sScreen = $sScreen . 							"<td align=|left|><span style=|color:#666|>" . Time2AmPm(($row2["INHR" . $x]))  .  "</span></td>  " ;  	    	
	$sScreen = $sScreen . 							"<td align=|left|><span style=|color:#666|>" . Time2AmPm(($row2["OUTHR" . $x]))  .  "</span></td>  " ;		
	$sScreen = $sScreen . 							"<td align=|left|><span style=|color:#666|>" . convertTime($row2["BREAK" . $x])  . "</span></td>  "  ; 
	$sScreen = $sScreen . 							"<td align=|right|><b>" . strval($row2['OUTHR'. $x] - $row2['INHR' . $x] - $row2['BREAK' . $x]) . "</b></td>  "   ;
	$sScreen = $sScreen . 				"</tr>  "   ;
	$grandtotal =  $grandtotal + $row2['OUTHR'. $x] - $row2['INHR' . $x] - $row2['BREAK' . $x]; 
}
$sScreen = $sScreen . 					"<tr>
											<td colspan=|5| style=|border-bottom:solid 1px #ccc; height:5px|></td>
										</tr> \r\n
										<tr>
											<td colspan=|2| align=|left|><b style=|color:#a4100c|>Assignment is: </b> ";
												if ($row2["CONT"] == "1") { 
													$sScreen = $sScreen . "Continuing";
												} Else {
													$sScreen = $sScreen . "Over";
												}
$sScreen = $sScreen . 						"</td>
											<td colspan=|3| align=|right|><b style=|color:#a4100c|>Total: </b><b> " . $grandtotal. "</b></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height=|50px| colspan=|2| align=|left| valign=|bottom|><h2 style=|font-size:14px;  margin:0 ; padding:0|>WAITING FOR APPROVAL</h2></td>
							</tr>
							<tr>
								<td colspan=|2| align=|left| valign=|top|>
									<table border=|0| width=|400| cellpadding=|0| cellspacing=|0| style=|font-family:Arial, Helvetica, sans-serif; font-size:12px; |>
										<tr>
											<td width=|195| align=|center| background=|/webtime/email/images/assets/click-here.gif| height=|38| bgcolor=|#9C0000|>
												<a href=|". "https://www.icreatives.com" ."/index.php?pagename=approve-timesheet&varib=1" . $row2["UNUMB"] . "-" . $row2["xORDER"] . "| style=|color:#FFFFFF|><b>CLICK HERE TO APPROVE</B></a>
											</td>
											<td width=|5|></td>
											<td width=|195| align=|center| background=|/webtime/email/images/assets/click-here.gif| height=|38| bgcolor=|#9C0000|>
												<a href=|". "https://www.icreatives.com" ."/index.php?pagename=approve-timesheet&varib=0" . $row2["UNUMB"]. "-" . $row2["xORDER"] . "| style=|color:#FFFFFF|><B>CLICK HERE TO DECLINE</B></a>
												</b></font>
											</td>
											<td width=|5|></td>
										</tr>
									</table>      
								</td>
							</tr> 
							<tr> 
								<td colspan=|3| align=|left| valign=|bottom| height=|20px|><b>" . $row2["xORDER"] ."</b>
								</td>
							</tr> 
						</table> 
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td> ";
		<td width=|150| valign=|top| align=|left| style=|padding:0px 0px 0px 10px;|>
		<!-- CTA1 -->  \r\n
			<table style=| padding:-0px; width:150px; border-spacing: 5px; border-radius:20px 20px 20px 20px;border: 2px solid #A50F14; |>
				<tr>
					<td align=|left| valign=|top|></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td> </td>";
					<td valign=|top|  style=|font-size:11px; color:#666;padding:5px;|>
						<b style=|color:#a4100c; font-size:12px|>QUESTIONS ABOUT THIS TIMESHEET?</b>
						Have questions about this timesheet?&nbsp;
						Please call us: <b style=|color:#000|>888.427.3283</b>&nbsp;
						or drop us a note @<br />
						<a href=|https://www.icreatives.com/contact-us/| style=|color:#a4100c; font-weight:bold| title=|contact Us|>creatives.com/contact-us</a>
					</td> ";
					<td>&nbsp;</td>
				</tr> ";
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr> 
			</table>
		<!-- CTA2 -->    "  . "\r\n"; 
			// old featured below:
// $sScreen = $sScreen .			"<!-- ";
// $sScreen = // $sScreen . 				"<table width=|150| border=|0| cellpadding=|0| cellspacing=|0| > ";
// $sScreen = // $sScreen . 					"<tr > ";
// $sScreen = // $sScreen . 						"<td> </td>";
// $sScreen = // $sScreen . 						"<td> </td>";
// $sScreen = // $sScreen . 						"<td> </td>";
// $sScreen = // $sScreen . 					"</tr> ";
// $sScreen = // $sScreen . 					"<tr> ";
// $sScreen = // $sScreen . 						"<td> </td>";
// $sScreen = // $sScreen . 						"<td valign=|top|  style=|font-size:11px; ;padding:15px 5px; line-height:12px; color:#666;|> ";
// $sScreen = // $sScreen . 							"<h1  style=|color:#a4100c; font-size:25px; margin:0; padding:0; line-height:20px|>FEATURED</h1> ";
// $sScreen = // $sScreen . 							"<h1  style=|color:#a4100c; font-size:25px; margin:0; padding:0;line-height:20px; margin-bottom:5px|>CREATIVE</h1> "   ;               
// $sScreen = // $sScreen . 							"World&#39;s first talking portfolio<br /> ";
// $sScreen = // $sScreen . 							"";
// $sScreen = // $sScreen . 							"<div align=|center|><img src=|https://www.icreatives.com/webtime/email/images/assets/featured.jpg| style=|padding:10px| width=|96| height=|82| border=|0|   /></div> ";
// $sScreen = // $sScreen . 							"Listen to the talent, get to learn more about the thought processes of the talent, and personality, that only sound can bring. Another i creatives first. Click  ";
// $sScreen = // $sScreen . 							"<a href=|https://www.icreatives.com/featured-talent/| style=|color:#a4100c; font-weight:bold| title=|Feature Creative|>here</a> ";
// $sScreen = // $sScreen . 							"to experience our featured artists.  ";
// $sScreen = // $sScreen . 					"</td> ";
// $sScreen = // $sScreen . 					"<td> </td>";
// $sScreen = // $sScreen . 				"</tr> ";
// $sScreen = // $sScreen . 				"<tr > ";
// $sScreen = // $sScreen . 					"<td> </td>"; 
// $sScreen = // $sScreen . 					"<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-bottom.gif| width=|190| height=|15| border=|0| /></td> ";
// $sScreen = // $sScreen . 					"<td> </td>";
// $sScreen = // $sScreen .				"</tr> ";
// $sScreen = $sScreen . 				"</table> -->";
/*
$sScreen = $sScreen . 				"<!-- CTA3 -->     "  . "\r\n"; 
$sScreen = $sScreen . 				"<div style=|height:10px;|> </div>"; 
// $sScreen = $sScreen . 				"<table width=|200| border=|0| cellpadding=|0| cellspacing=|0| > ";
$sScreen = $sScreen . 			"<table style=| width:200px; padding:10px 5px 5px 10px; width:150px;  border-radius:20px 20px 20px 20px;border: 2px solid #A50F14; |>";
$sScreen = $sScreen . 					"<tr> ";
$sScreen = $sScreen . 						"<td> </td>";
$sScreen = $sScreen . 						"<td style=|width:120px;font-size:11px; line-height:12px; color:#666;| > ";
$sScreen = $sScreen . 							"<h1  style=|color:#a4100c; font-size:21px; margin:0; padding:0; line-height:20px|>OUR</h1> ";
$sScreen = $sScreen . 							"<h1  style=|color:#a4100c; font-size:21px; margin:0; padding:0 3px 0 0;line-height:20px; margin-bottom:5px|>COMMERCIAL </h1> ";
$sScreen = $sScreen . 							"<A Href=|http://youtu.be/UyRqMAP6pG8| border=|0|>";
$sScreen = $sScreen . 							"<div align=|center|><img src=|https://www.icreatives.com/webtime/email/images/assets/commercial.jpg| style=|align:left; padding:10px 0px 0px 0px;|  width=|115| height=|70| border=|0|   /></div> ";
$sScreen = $sScreen . 							"</A>";
// $sScreen = $sScreen . 							"See our new commercial. ";
$sScreen = $sScreen . 						"</td> ";
$sScreen = $sScreen . 						"<td > </td>";
$sScreen = $sScreen . 					"</tr> ";
$sScreen = $sScreen . 					"<tr > ";
$sScreen = $sScreen . 						"<td>&nbsp;</td>";
$sScreen = $sScreen . 						"<td>&nbsp;</td>";
$sScreen = $sScreen . 						"<td>&nbsp;</td>";
$sScreen = $sScreen . 					"</tr> ";
$sScreen = $sScreen . 				"</table> ";
$sScreen = $sScreen . 			"</td> "  . "\r\n"; 
$sScreen = $sScreen . 		"</tr> ";
$sScreen = $sScreen . 		"<tr> ";
$sScreen = $sScreen . 			"<td colspan=|2|> ";
$sScreen = $sScreen . 				"<table width=|600| height=|100| border=|0| cellpadding=|0| cellspacing=|0|> ";
$sScreen = $sScreen . 					"<tr> ";
$sScreen = $sScreen . 						"<td> </td>";
$sScreen = $sScreen . 						"<td>";
$sScreen = $sScreen . 							"<P><font color=|#FFFFFF|>This <A style=|color:#FFFFFF| Href=|https://www.icreatives.com|>i creatives</A> time approval is generated once our talent has completed an online timesheet for work performed for ";
$sScreen = $sScreen . 							"your company. Please click on the &quot;Approve&quot; or &quot;Decline&quot; link. Once a button is clicked, you will be asked to sign by typing in your full name. <P>After you click on the &quot;submit&quot; button, an &quot;approved&quot; timesheet is immediately emailed for your records. ";
$sScreen = $sScreen .							"</font>"                    ;
$sScreen = $sScreen . 							"</td> ";
$sScreen = $sScreen . 						"<td> </td>";
$sScreen = $sScreen . 					"</tr>";
$sScreen = $sScreen . 				"</table> ";
*/
$sScreen = $sScreen ."</td>
				</tr>
			</table>
			<table width=|650| border=|0| align=|center| cellpadding=|0| cellspacing=|0| style=|align:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; |>
				<tr>
					<td>
						<center>
						<DIV style=|padding-top:20px;| align=justify><FONT size = |1| color=|#436376| width = |480|>
						terms: Being duly authorized on behalf of the below customer, the undersigned hereby (1) certifies that the total hours shown are true and correct, the work was performed&nbsp;
						satisfactorily and my signature is authorization to bill the below named customer for the hours shown, (2) constitutes an agreement between i creatives and &nbsp;
						customer with respect to the services performed hereunder and any future services that (a) i creatives&#39; terms are that an invoice rendered for these hours shall be net due upon receipt and&nbsp;
						if not paid within ten days a late payment (liquidated damages) charge of one and one-half percent per month (which is an annual percentage rate of eighteen percent) will be calculated&nbsp;
						on the balance shown on our statement as being past due and payable; (b) that all i creatives&#39; temporary personnel represent a substantial investment to i creatives. We agree not to r\n &nbsp;
						employ directly, or refer for hiring, any i creatives temporary personnel until one year from the date last worked at our firm as a i creatives temporary person, unless we reimburse i creatives &nbsp;
						20% of annual salary in liquidated damages for the replacement costs of like personnel; (c) customer shall not entrust i creatives personnel with unattended premises, cash negotiables &nbsp;
						and other valuables, or authorize such personnel to operate machinery or motor vehicles without prior written permission from i creatives in each instance; (d) should a lawsuit &nbsp;
						be necessary to enforce this agreement, the customer and the individual, agent or company official signing this time card shall be jointly and severably liable for any amount due &nbsp;
						or the liquidated damages, venue is waived and suit may be brought in Miami, Florida. Should it be necessary for i creatives to employ an attorney at law to collect any amount due or &nbsp;
						the liquidated damages, then we agree to pay all reasonable attorney&#39;s fees plus court costs r\n
						</FONT><BR>
						</DIV></center>
					</td>";
					<td WIDTH=|160|> </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
// $sScreen = $sScreen . "</body> ";
// $sScreen = $sScreen . "</html> ";
// echo str_replace("|","'",$sScreen);
?>