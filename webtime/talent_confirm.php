<?php
// Function Talent_Screen() {
$eScreen = "<!DOCTYPE html PUBLIC |-//W3C//DTD XHTML 1.0 Transitional//EN| |https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd|> ";
$eScreen = $eScreen . "<html> ";
$eScreen = $eScreen . "<head> ";
$eScreen = $eScreen . "<meta http-equiv=|Content-Type| content=|text/html; charset=utf-8| /> ";
$eScreen = $eScreen . "<title>i creatives</title> ";
$eScreen = $eScreen . "</head> ";
$eScreen = $eScreen . "<body> ";

$eScreen = $eScreen . "<table width=|112| border=|0| align=|center| cellpadding=|0| cellspacing=|0| style=|font-family: Arial, Helvetica, sans-serif; font-size: 12px|> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td width=|576| align=|left| valign=|top| > ";

      $eScreen = $eScreen . "<!-- Content -->    ";
      $eScreen = $eScreen . "<table width=|570| border=|0| cellpadding=|0| cellspacing=|0| > ";
             $eScreen = $eScreen . " <tr > ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-top.gif| width=|15| height=|15| border=|0| /></td> ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-top.gif| width=|540| height=|15| border=|0| /></td> ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-top.gif| width=|15| height=|15| border=|0| /></td> ";
              	$eScreen = $eScreen . "</tr> ";
              	$eScreen = $eScreen . "<tr> ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-left.gif| width=|15| height=|665| border=|0| /></td> ";
                $eScreen = $eScreen . "<td align=|center| valign=|top| style=|padding:10px 0;| background=|https://www.icreatives.com/webtime/email/images/assets/background.gif|> ";
                $eScreen = $eScreen . "<table width=|400| border=|0| align=|center|> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td width=|260|  height=|170| align=|left| valign=|top|><img src=|https://www.icreatives.com/webtime/email/images/assets/logo.gif| width=|110| height=|123| border=|0|  style=|margin:15px 0px| /></td> ";
    $eScreen = $eScreen . "<td width=|230| align=|right| valign=|top| > ";
        $eScreen = $eScreen . "<h1  style=|font-family: Arial, Helvetica, sans-serif;color:#a4100c; margin:0; padding:2px; font-size:30px|>TimeSheet</h1> ";
        $eScreen = $eScreen . "<b>Week Ending:</b>  " . date("m/d/Y", strtotime($WKEND)) . " ";
    $eScreen = $eScreen . "</td> ";
  $eScreen = $eScreen . "</tr> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td align=|left|><b style=|color:#a4100c|>Contractor: </b>" . "XXXX" . "</td> ";
    $eScreen = $eScreen . "<td align=|right|></td> ";
    $eScreen = $eScreen . "</tr> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td height=|40px| align=|left| valign=|top|></td> ";
    $eScreen = $eScreen . "<td> </td> ";;
  $eScreen = $eScreen . "</tr> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td height=|220| colspan=|2| valign=|top|> ";
    $eScreen = $eScreen . "<table width=|570| border=|0| cellpadding=|4| style=|padding:4px 0px|> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td width=|136| align=|left|><b>Day</b></td> ";
    $eScreen = $eScreen . "<td width=|90| align=|left|><b>In</b></td> ";
    $eScreen = $eScreen . "<td width=|90| align=|left|><b>Out</b></td> ";
    $eScreen = $eScreen . "<td width=|90| align=|left|><b>Break</b></td> ";
    $eScreen = $eScreen . "<td width=|42| align=|right|><b>Total</b></td> ";
  $eScreen = $eScreen . "</tr> ";
  $eScreen = $eScreen . "<tr><td colspan=|5| style=|border-top:solid 1px #ccc; height:5px|></td> </tr> ";

for ($x = 1; $x <= 7; $x++) {
 $eScreen = $eScreen . "<tr>  "   ;
    $eScreen = $eScreen . "<td align=|left| ><b>". jddayofweek($x-2,1) . "</b></td>  "   ;
    $eScreen = $eScreen . "<td align=|right|><span style=|color:#666|>". HrComp($row["INHR" . $x], $row["OUTHR" . $x])  . ":" . MinComp($row["INHR" . $x])   . " " . AmPmCompIn($row["INHR" . $x])    .  "</span></td>  "   ;
    $eScreen = $eScreen . "<td align=|right|><span style=|color:#666|>". HrComp($row["OUTHR" . $x],00) . ":" . MinComp($row["OUTHR" . $x])  . " " . AmPmCompIn($row["OUTHR" . $x])   .  "</span></td>  "   ;
    $eScreen = $eScreen . "<td align=|right|><span style=|color:#666|>". HrComp($row["BREAK" . $x],00) . ":" . MinComp($row["BREAK" . $x]) . "</span></td>  "   ;
    $eScreen = $eScreen . "<td align=|right|><b>". round(($row["OUTHR" . $x] - $row["INHR" . $x]) -$row["BREAK" . $x],2)  . "</b></td>  "   ;
 $eScreen = $eScreen . " </tr>  "   ;
}

    $eScreen = $eScreen . "<tr><td colspan=|5| style=|border-bottom:solid 1px #ccc; height:5px|></td> </tr> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td colspan=|2| align=|left|><b style=|color:#a4100c|>Assignment is: </b>";
	If ($row["CONT"] == "1") { 
		$eScreen = $eScreen . "Continuing";
	} Else {
		$eScreen = $eScreen . "Over";
	}
   $eScreen = $eScreen . "</td> ";
  
 
    $eScreen = $eScreen . "<td colspan=|3| align=|right|><b style=|color:#a4100c|>Total: </b><b> " . $HOURS . "</b></td> ";
    $eScreen = $eScreen . "</tr> ";
    $eScreen = $eScreen . "</table> ";

    
    $eScreen = $eScreen . "</td> ";
    $eScreen = $eScreen . "</tr> ";

  $eScreen = $eScreen . "<tr> ";
  $eScreen = $eScreen . "<td height=|50px| colspan=|2| align=|left| valign=|bottom|><h2 style=|font-size:14px;  margin:0 ; padding:0|>APPROVED</h2></td> ";
  $eScreen = $eScreen . "</tr> ";
  $eScreen = $eScreen . "<tr> ";
  $eScreen = $eScreen . "<td colspan=|2| align=|left| valign=|bottom| height=|20px|><b>" . ($row["xORDER"] ?? '') . "</b></td> ";
  $eScreen = $eScreen . "</tr> ";
               $eScreen = $eScreen . " </table> ";
			    
                $eScreen = $eScreen . "</td> ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-right.gif| width=|15| height=|665| border=|0| /></td> ";
              $eScreen = $eScreen . "</tr> ";
              $eScreen = $eScreen . "<tr > ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-left-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/sides/side-bottom.gif| height=|15| width=|540| /></td> ";
                $eScreen = $eScreen . "<td><img src=|https://www.icreatives.com/webtime/email/images/corners/corner-right-bottom.gif| width=|15| height=|15| border=|0| /></td> ";
              $eScreen = $eScreen . "</tr> ";
     $eScreen = $eScreen . " </table> ";

    $eScreen = $eScreen . "</td> ";
    $eScreen = $eScreen . "<td width=|32| valign=|top|> ";
    	
      $eScreen = $eScreen . "<!-- CTA1 -->   ";
      
      
      $eScreen = $eScreen . "<!-- CTA2 -->     ";
      
      $eScreen = $eScreen . "<!-- CTA3 -->     "   ;   
      
$eScreen = $eScreen . "</td> ";
  $eScreen = $eScreen . "</tr> ";
  $eScreen = $eScreen . "<tr> ";
    $eScreen = $eScreen . "<td colspan=|2| width=|610|> ";
      $eScreen = $eScreen . "<table width=|570| border=|0| cellpadding=|0| cellspacing=|0|> <tr> ";
            $eScreen = $eScreen . "<td align=|left| valign=|top| width=|15|><img src=|https://www.icreatives.com/webtime/email/images/footer/footer-left.gif| width=|15| height=|100| border=|0| /></td> ";
          $eScreen = $eScreen . "<td width=|540| align=|center| valign=|middle| style=|font-weight: bold; font-size: 11px; color: #FFF| background=|https://www.icreatives.com/webtime/email/images/footer/footer-repeat.gif|  > ";
      
            $eScreen = $eScreen . "<p > </p> ";
            $eScreen = $eScreen . "<p >Thank you for letting i creatives represent you.</p>  ";
          
            
          $eScreen = $eScreen . "</td>  ";
            $eScreen = $eScreen . "<td align=|right| valign=|top| width=|15|><img src=|https://www.icreatives.com/webtime/email/images/footer/footer-right.gif|  width=|15| height=|100| border=|0| /></td>  ";
      $eScreen = $eScreen . "</tr></table>  ";
$eScreen = $eScreen . "</td>  ";
  $eScreen = $eScreen . "</tr>  ";
$eScreen = $eScreen . "</table>  ";
$eScreen = $eScreen . "</body>  </html> ";

echo $eScreen;
// Return $eScreen;
// }
/* 
already declaired in ASN_Approve_T.txt
Function AmPmCompIn($lsHr) { // need two because out should default to PM for new record

If ($lsHr >= 12) {
        $AmPmCompIn = "PM";
} Else {
        $AmPmCompIn = "AM";
}
return $AmPmCompIn;
}


Function HrComp($lsInHr,$lsOutHr) {
If ($lsInHr >= 13) {
   $lsInHr = $lsInHr -12;
} ElseIf ($lsInHr == 0 && $lsOutHr <> 0) {
	$lsInHR = 12;
} Else {
   $lsInHr = $lsInHr;
}
return $lsInHr;
}
*/


?>