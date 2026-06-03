<?php
$sScreen = "Plain Text Version: icreatives timesheet for week ending: " . request.form("WkEnd") . "\r\n" . "\r\n" ;

	if ($_POST["PoNumber"] <> "") {
   		$sScreen = $sScreen . "PO Number:  " . $_POST["PoNumber"]  . "\r\n" . "\r\n" ;
	}

$sScreen = $sScreen . "\r\n";
$sScreen = $sScreen . "Contractor: " . sTalentName . "\r\n";
   
if ($_POST["PoNumber"] <> "") {
   		$sScreen = $sScreen . "PO Number:  " . request.form("PoNumber")  . "\r\n";
}
$sScreen = $sScreen . "\r\n";   
$sScreen = $sScreen . "Customer: " . str_replace("_"," ",$_POST["JOB"]) . "\r\n" . "\r\n";

for ($x = 1; $x <= 7; $x++) {
	$sScreen = $sScreen . "\t" . WeekDayName($x) ;
	$sScreen = $sScreen . "\t" . " In: " .  $_POST["TimeInHr" . $x]   . ":" . if($_POST["TimeInMin" . $x]  == 0) {"00";} }else{ $_POST["TimeInMin" . $x]} . " " . $_POST["TimeInAmPm"  . $x];  
	$sScreen = $sScreen . "\t" . " Out: " . $_POST["TimeOutHr" . $x]  . ":" . if($_POST["TimeOutMin" . $x] == 0) {"00";} }else{ $_POST["TimeOutMin". $x]} . " " . $_POST["TimeOutAmPm" . $x];
	$sScreen = $sScreen . "\t" . " Break: " .$_POST["BreakHr" . $x]   . ":" . if($_POST["BreakMin" . $x]   == 0) {"00";} }else{ $_POST["BreakMin". $x]};   
	$sScreen = $sScreen . "\t" . " Total: " . TotalHrs($x)  ;
	$sScreen = $sScreen . VBCrLf  
Next
$sScreen = $sScreen . "\r\n"; . "\r\n"  ;

$sScreen = $sScreen . "Assignment is: ";

	if ($_POST["Continuing"] == "1") { 
		$sScreen = $sScreen . "Continuing";
	} Else {
		$sScreen = $sScreen . "Over";
	}

$sScreen = $sScreen . "\t" . "Total: " . GrandTotal() . "\r\n"  ;
$sScreen = $sScreen . "\r\n" . "\r\n"  ;
    
$sScreen = $sScreen . "WAITING FOR YOUR APPROVAL " . "\r\n"  . "\r\n"  ;
$sScreen = $sScreen . "CLICK HERE TO APPROVE" . "\r\n" ;
$sScreen = $sScreen . "https://www.icreatives.com/TS1" . $MyNewRandomNum . "-" . $_POST["xOrder"]  . "\r\n" . "\r\n";
$sScreen = $sScreen . "CLICK HERE TO DECLINE" . "\r\n" 
$sScreen = $sScreen . "https://www.icreatives.com/TS0" . $MyNewRandomNum . "-" . $_POST["xOrder"]  . "\r\n" . "\r\n";

$sScreen = $sScreen . "\r\n" . "\r\n"  ;

$sScreen = $sScreen . "\t" . $_POST[""xORDER"] . "\r\n"  ;
 
$sScreen = $sScreen . "QUESTIONS ABOUT THIS TIMESHEET?" . "\r\n";
$sScreen = $sScreen . "Please call us: 888.427.3283" . "\r\n";
$sScreen = $sScreen . "or drop us a note" . "\r\n";
$sScreen = $sScreen . "https://www.icreatives.com/contact-us/" . "\r\n";
$sScreen = $sScreen . "\r\n" . "\r\n"  ;
$sScreen = $sScreen . "Sincerely," . "\r\n";
$sScreen = $sScreen . "The icreatives Team" . "\r\n";

$sScreen = $sScreen . "\r\n" . "\r\n" ;

?>