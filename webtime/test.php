
<?php include "db5.php";
$strSQL = "SELECT MAX(Merit_ID) as MAXMERITID FROM PERC_AssignmentMerit";


	$resMySel = odbc_exec($conn,$strSQL);
	$row = odbc_fetch_array($resMySel);

$sMerit_ID = $row["MAXMERITID"];

$newnum = base_convert( $sMerit_ID , 32, 10);

echo "Merit ID = " .base_convert( $sMerit_ID , 32, 10) ."<br>";

echo "Merit ID = " . $sMerit_ID . "<br>";

$newnum = StrToUpper(Str_Pad(base_convert(base_convert( $sMerit_ID , 32, 10)+1,10,32),8,"0",STR_PAD_LEFT));

$newnum = StrToUpper(Str_Pad(base_convert(base_convert( $row["MAXMERITID"] , 32, 10)+1,10,32),8,"0",STR_PAD_LEFT));

echo $newnum;


?>





