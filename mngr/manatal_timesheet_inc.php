<?php
$sScreen = '
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> <title>ICreatives</title>  
 </head>
 <body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="5" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; ">
   <tbody>
      <tr>
         <td width="100%" align="left" valign="top">
            <!-- Content -->    
            <table style="border-spacing: 5px; border-radius:20px 20px 20px 20px;border: 2px solid #A50F14; ">
               <tbody>
                  <tr>
                     <td> </td>
                     <td> </td>
                     <td> </td>
                  </tr>
                  <tr>
                     <td></td>
                     <td align="center" valign="top" style="padding:10px 0;" background="https://www.icreatives.com/webtime/email/images/assets/background.gif">
                        <table width=537" border="0" align="center">
                           <tbody>
<tr>
   <td width="220" height="170" valign="top" align="left" valign="top"><a href="https://www.icreatives.com" border="0"><img src="https://port.icreatives.com/webtime/images/ts_logo.gif" width="110" height="123" border="0" alt="i creatives logo" style="margin:15px 0px"></a></td>
   <td width="180" align="right" valign="top">
      <h1 style="color:#a4100c; margin:0; padding:2px; font-size:30px">TimeSheet</h1>
      <b>Week Ending: </b>'.  date('Y-m-d', strtotime($row6["WKEND"])) . 
	  '<br><b>';
	  if(!empty($row6["PO"])){ 
		$sScreen = $sScreen . 'PO Number: </b> ' . $row6["PO"]. ' ';
	  }
	$sScreen = $sScreen . '</b>
   </td>
</tr>
<tr>
   <td align="left" valign="top"><b style="color:#a4100c">Contractor: </b>' . $row6["FIRST"] . '</td>
   <td align="right"><b></b></td>
</tr>
<tr>
   <td align="left" valign="top"><b style="color:#a4100c">Customer: </b>' . $row6['COMPANY'] . '<br></td>
   <td>&nbsp;</td>
</tr>
<tr>
   <td align="left" valign="top"><b style="color:#a4100c">Project: </b>' . $row6['ITEM'] . '<br></td>
   <td>&nbsp;</td>
</tr>
<tr>
   <td height="220" colspan="2" valign="top">
      <table width="100%" border="0" cellpadding="4" style="padding:4px 0px">
         <tbody>
            <tr>
               <td width="80" align="left"><b>Day</b></td>
               <td width="60" align="left"><b>In</b></td>
               <td width="60" align="left"><b>Out</b></td>
               <td width="50" align="left"><b>Break</b></td>
               <td width="32" align="right"><b>Total</b></td>
            </tr>
            <tr>
               <td colspan="5" style="border-top:solid 1px #ccc; height:5px"></td>
            </tr>';
			for ($z = 1; $z <= 7; $z++) {
				$sScreen = $sScreen . '<tr>  '   ;		
				$sScreen = $sScreen . '<td align=|left| ><b>'. jddayofweek($z-2,1) . '</b></td>  '   ;
				$sScreen = $sScreen . '<td align=|right|><span style=|text-align:right; color:#666;|>'. HrComp($row6["INHR". $z], $row6["OUTHR". $z])  . ':' . MinComp($row6["INHR". $z])   . ' ' . AmPmCompIn($row6["INHR". $z])    .  '</span></td>  '   ;
	
				// $sScreen = $sScreen . '<td align=|left|><span style=|color:#666|>'. HrComp($row6["INHR". $z],00) s . ':' . MinComp($row6["INHR". $z])   . ' ' . AmPmCompIn($row6["INHR". $z])    .  '</span></td>  '   ;
				$sScreen = $sScreen . '<td align=|right|><span style=|text-align:right; color:#666;|>'. HrComp($row6["OUTHR". $z],00) . ':' . MinComp($row6["OUTHR". $z])  . ' ' . AmPmCompIn($row6["OUTHR". $z])   .  '</span></td>  '   ;
				$sScreen = $sScreen . '<td align=|right|><span style=|text-align:right; color:#666;|>'. HrComp($row6["BREAK". $z],00) . ':' . MinComp($row6["BREAK". $z]) . '</span></td>  '   ;
				$sScreen = $sScreen . '<td align=|right|><b>'. number_format(($row6["OUTHR". $z] - $row6["INHR". $z]) -$row6["BREAK". $z],2)  . '</b></td>  '   ;
				// $sScreen = $sScreen . '<td align=|right|><b>'. number_format(round($row6["OUTHR". $z] - $row6["INHR". $z]) -$row6["BREAK". $z],2)  . '</b></td>  '   ;
				$sScreen = $sScreen . ' </tr>  '   ;
			}
			$sScreen = $sScreen . '
			<tr>
               <td colspan="5" style="border-bottom:solid 1px #ccc; height:5px"></td>
            </tr>
            <tr>
               <td colspan="2" align="left"><b style="color:#a4100c">
			   Assignment is: </b> ';
				If ($row6["CONT"] = "1") { 
					$sScreen = $sScreen . "Continuing";
				} Else {
					$sScreen = $sScreen . "Over";
				}
			   $sScreen = $sScreen .'			   
			   </td>
               <td colspan="3" align="right"><b style="color:#a4100c">Total: </b><b>' . $row6["HOURS"] . '</b></td>
            </tr>
         </tbody>
      </table>
   </td>
</tr>
<tr> </tr>
<tr>
   <td colspan="2" align="left" valign="top">
      <table border="0" width="100%">
         <tbody>
            <tr>
               <td width="53%" rowspan="3">
                  <p align="center"><font size="6">APPROVED</font></p>
               </td>
               <td width="8%"><font size="1">By:</font></td>
               <td width="89%"><font size="2">'.$row6['ESIG'].'</font></td>
            </tr>
            <tr>
               <td width="8%"><font size="1">Date:</font></td>
               <td width="89%"><font size="2">'.$row6["APPROVE"].'</font></td>
            </tr>
            <tr>
               <td width="8%"><font size="1">IP:</font></td>
               <td width="89%"><font size="2">'.$row6["IP"].'</font></td>
            </tr>
         </tbody>
      </table>
   </td>
</tr>
<tr>
   <td colspan="2" align="left" valign="bottom" height="20px"><b>'.$row6["PROJ"].'</b></td>
</tr>
                           </tbody>
                        </table>
                     </td>
                     <td>
					 </td>
                  </tr>
                  <tr>
                     <td> </td>
					 <td> </td>
					 <td> </td>
				  </tr>
               </tbody>
            </table>
         </td>
         <td width="355" valign="top">
            <!-- CTA1 -->     
            <table width="25%" border="0" cellpadding="0" cellspacing="0">
               <tbody>
                  <tr>
                     <td> </td>
					 <td> </td>
					 <td> </td>                  
				  </tr>
                  <tr>
                     <td> </td>
					 <td> </td>
					 <td> </td>                  
				  </tr>
                  <tr>
                     <td> </td>
					 <td> </td>
					 <td> </td>                  
				  </tr>
               </tbody>
            </table>
            <!-- CTA2 -->    
			 <table width="25%" padding="10" style="border-spacing: 5px; border-radius:20px 20px 20px 20px;border: 2px solid #A50F14; ">
               <tbody>
                   <tr>
                     <td> </td>
					 <td> </td>
					 <td> </td>                  
				  </tr>
                  <tr>
                     <td></td>
                    <td valign="top" style="font-size:11px; ;padding:15px 5px; line-height:12px; color:#666;">
                        <h1 style="color:#a4100c; font-size:25px; margin:0; padding:0; line-height:20px">FEATURED</h1>
                        <h1 style="color:#a4100c; font-size:25px; margin:0; padding:0;line-height:20px; margin-bottom:5px">CREATIVE</h1>
                        World&#39;s first talking portfolio<br> 
                        <div align="center"><img src="https://port.icreatives.com/webtime/images/featured.jpg" style="padding:10px" width="96" height="82" border="0"></div>
                        Listen to the talent, get learn more amout the thought process of the talent, and personality, that only sound can bring. Another i creatves first. Click  <a href="https://www.icreatives.com/featured-talent/" style="color:#a4100c; font-weight:bold" title="Feature Creative">here</a> to experience our featured artists.  
                     </td>
                     <td></td>
					</tr>
                   <tr>
                     <td> </td>
					 <td> </td>
					 <td> </td>
				  </tr>
               </tbody>
            </table>
            <!-- CTA3 -->     
          </td>
      </tr>
      <tr>
         <td colspan="2">
            <table style="border-spacing: 4px; border-radius:20px 20px 20px 20px;border: 2px solid #A50F14;" bgcolor="#A50F14" ">
               <tbody>
                  <tr>
                     <td>
                     <td width="545" align="center" valign="middle" style=" font-weight:bold ;font-size:10px; color:#FFFFFF " bgcolor="#A50F14">
                        <p><font color="#FFFFFF">This <a style="color:#FFFFFF" href="https://www.icreatives.com">i creatives</a> 
						confirmation is automatically generated once a timesheet has been approved and digitally signed on our 
						website  form by an authorized representative of your company. <b>Please save this approved timesheet for 
						your records</b>. <br>We hope you are more than satisfied with the level of talent i creatives provides.
						</font></p>
                     </td>
                     <td align="right" valign="top" bgcolor="#9C0000"></td>
				</tr>
				
				</tr>
               </tbody>
            </table>
         </td>
		 
      </tr>
   </tbody>
</table>
<center>
	  <div style="text-align:justify;font-size:9px; color:#436376; width:750px;">
				 Terms: Being duly authorized on behalf of the below customer, the undersigned hereby (1) certifies 
				 that the total hours shown are true and correct, the work was performed satisfactorily and my 
				 signature is authorization to bill the below named customer for the hours shown, (2) constitutes 
				 an agreement between i creatives and customer with respect to the services performed hereunder 
				 and any future services that (a) i creatives&#39; terms are that an invoice rendered for these hours 
				 shall be net due upon receipt and if not paid within ten days a late payment (liquidated damages) 
				 charge of one and one-half percent per month (which is an annual percentage rate of eighteen percent) 
				 will be calculated on the balance shown on our statement as being past due and payable; (b) that all 
				 i creatives&#39; temporary personnel represent a substantial investment to i creatives. We agree not to 
				 employ directly, or refer for hiring, any i creatives temporary personnel until one year from the date 
				 last worked at our firm as a i creatives temporary person, unless we reimburse i creatives 20% of annual 
				 salary in liquidated damages for the replacement costs of like personnel; (c) customer shall not entrust
				 i creatives personnel with unattended premises, cash negotiables and other valuables, or authorize 
				 such personnel to operate machinery or motor vehicles without prior written permission from 
				 i creatives in each instance; (d) should a lawsuit be necessary to enforce this agreement, the 
				 customer and the individual, agent or company official signing this time card shall be jointly and 
				 severably liable for any amount due or the liquidated damages, venue is waived and suit may be 
				 brought in Miami, Florida. Should it be necessary for i creatives to employ an attorney at law to 
				 collect any amount due or the liquidated damages, then we agree to pay all reasonable attorney&#39;s 
				 fees plus court costs.
	</div>
</center>

</body></html>	  
';
$sScreen = str_replace("|","'",$sScreen);
?>