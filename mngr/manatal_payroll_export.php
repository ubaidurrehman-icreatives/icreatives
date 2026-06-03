<?php include 'manatal_header.php'; ?>
<h1>MANATAL PAYROLL EXPORT</h1>
<?php

// Connect to the MySQL database
require_once __DIR__ . '/../db/db.php';
$link = db();   

$strSQL = "
SELECT
    'TIMEACT' as `TIMEACT`,
    wt.first_name as `FIRST`,
    wt.last_name as `LAST`,
    oj.pay_group as `PAYGROUP`,
    oj.file_number as `FILENUMBER`,
    oj.department as `DEPARTMENT`,
    wt.Employee_ID as `EMPID`,
    wt.billrate as `BILLRATE`,
    wt.payrate as `PAYRATE`,
    wt.Hours as `HOURS`,
    oj.company_name  as `JOB`,
    wt.title as `ITEM`,
    wt.AssignmentNumber as `PROJ`,
    wt.AssignmentNumber as `xORDER`,
    wt.Primary_Contact_Email as `email`,
    wt.Unique_id as `UNUMB`,
    '40' as `DURATION`,
    '1' as `BILLINGSTATUS`,
    'CONTR' as `PITEM`,
    '0' as `BITEM`,
    'Yes' as `Contract`,
    wt.Hours as `HOURS`,
    wt.Continuing as `CONT`,
    wt.ApproveDate as `APPROVE`,
    wt.SentDate as `SENT`,
    wt.DeclineDate as `DECLINE`,
    wt.Weekending as `WKEND`
FROM
    ic_timesheets wt
LEFT JOIN
    ic_matches oj ON (oj.candidate = wt.Employee_ID AND oj.job = wt.AssignmentNumber)
WHERE
    wt.void = FALSE 
	AND wt.invoice_type = 't' 
    AND wt.adp_export = '0000-00-00 00:00:00'
    AND wt.ApproveDate <> '0000-00-00 00:00:00' 
	AND wt.ExportDate <> '0000-00-00 00:00:00'

"; 

// echo $strSQL ;

if(!empty($_REQUEST['pay_group'])) {
	$strSQL = $strSQL ." AND oj.pay_group = '".$_REQUEST['pay_group']."' ";
}	
	
$strSQLn = $strSQL . "ORDER BY `PAY_GROUP` ASC, WeekEnding ASC, Employee_ID ASC;";
// Query the database for the list of recipients
$result = mysqli_query($link,$strSQLn);	

// Start the HTML form
echo '<form name = "form1" method="post">';
// echo 'Pay Date: <input type = "date" id = "pay_date" name = "pay_date" required>';
echo '&nbsp;&nbsp;&nbsp;Pay Group <input type = "text" id = "pay_group" name = "pay_group" required>';
echo '&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">';
echo '</form>';
echo '<form name = "form2" method="post">';
echo '<input type="hidden" name="pay_group" id = "pay_group" value = "'.$_REQUEST['pay_group'].'">';
// echo '<input type="hidden" name="pay_date" id = "pay_group" value = "'.$_REQUEST['pay_date'].'">';

// Output the table headers
echo '<table>';
echo "<thead><tr><th><input type='checkbox' id='check_all'></th><th><P>Check All</p></th></tr></thead>";
echo '<tr align="center">
<th> </th>
<th>Talent</th>
<th>Client</th>
<th>Position</th>
<th>Hours</th>
<th>Bill Rate</th>
<th>Inv Amt</th>
<th>Pay Rate</th>
<th>Pay Amt</th>
<th>Wk Ending</th>
<th>Hours</th>
<th>Approve Date</th>
<th>Job ID</th>
<th>Emp ID</th>
<th>Group</th>
<th>File#</th>
</tr>';

// Loop through the results and output each row
while ($row = mysqli_fetch_array($result)) {
	$approved_date = strtotime( $row['APPROVE']);
	$week_ending = strtotime( $row['WKEND']);
    echo '<tr align="center">';
	    echo '<td>';
		if (!is_null($row['JOB'])){
	echo '<input type="checkbox" name="recipients[]" value="' . $row['UNUMB'] . '">';
	}
	echo '</td>';

    echo '<td ALIGN="LEFT">' . $row['FIRST'] . ' ' .$row['LAST'] . '</td>';
		echo '<td ALIGN="LEFT">';
		if (!is_null($row['JOB'])) {
			echo $row['JOB'] ;
		} else {
			echo '<font color="red"><b>RE-OPEN JOB</b></font>';
		}
		echo "</td>";

	echo '<td ALIGN="LEFT">' . $row['ITEM'] .  '</td>';
	// echo '<td ALIGN="LEFT">' . $row['JOB'] . "</td>";
	echo "<td>" . $row['HOURS'] . "</td>";
	echo "<td>$" . $row['BILLRATE'] . "/hr</td>";
	echo "<td>$" . $row['BILLRATE'] * $row['HOURS'] . "</td>";
	echo "<td>$" . $row['PAYRATE'] . "/hr</td>";
	echo "<td>$" . $row['PAYRATE'] * $row['HOURS'] . "</td>";
	echo "<td>" . date("m/d/Y",$week_ending) . '</td>';
	echo "<td>".  $row["HOURS"]."</td>";
    echo "<td>" . date("m/d/Y",$approved_date) . '</td>';
	echo "<td><a target = '_blank' href = 'https://app.manatal.com/jobs/" . $row['PROJ']."'>" . $row['PROJ']. "</a></td>";
	echo "<td><a target = '_blank' href = 'https://app.manatal.com/candidates/" . $row['EMPID']."'>" . $row['EMPID']. "</a></td>";
	echo "<td>" . $row['PAYGROUP'] . "</td>";
	echo "<td>" . $row['FILENUMBER'] . "</td>";
    echo '</tr>';
}

// Close the table and add the submit button
// ECHO "<p><b>For Pay Date: </b>". $_REQUEST['pay_date'];
echo '</table><p>';
echo '<input type="submit" value="Download and Mark">';
echo '</form>';
		echo "<P> <br/></P>";
		echo "<P> <br/></P>";

if(!empty($_REQUEST['pay_group'])) {
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && is_array($_POST['recipients'])) {

    // Get the selected recipients
    $recipients = $_POST['recipients'];
	$WKEND = $_POST['WKEND'];
	
	 // Define the CSV file name and path
	$filename = 'PR'.$_REQUEST['pay_group'].'EPI'.DATE('Ymd').'.csv';
	$filepath = '' . $filename;

	// Open the CSV file for writing
	$file = fopen($filepath, 'w');
  
	// Open file for appending
	$file = fopen($filename, "a");
	
	
	
	// make header
	 // $text = '"Worker","Date","Regular Hours","Overtime Hours","Double Time Hours","OrderID"'. PHP_EOL;
	//  $text = '"CO Code","Batch ID","File #","Employee Name","Temp Dept","Temp Rate","Reg hours","O/T hours","Other Period Beginning Date","Other Period Ending Date"'. PHP_EOL;
	 $text = '"CO Code","Batch ID","File #","Employee Name","Temp Dept","Temp Rate","Reg hours","O/T hours","Other Period Beginning Date","Other Period Ending Date","Reg Earnings"'. PHP_EOL;

	 fwrite($file, $text);

    // Loop through the recipients and build the file

	// $filename = "time.txt";
	for($i=0; $i<count($recipients); $i++) {
	
 		$query2 = $strSQL . " AND wt.Unique_id= '". $recipients[$i]."'";
// 		AND wt.Assignment_ID = '". $PROJ[$i]."' AND wt.Weekending = '" .$WKEND[$i]. "'";

		// echo $query2;

		$result2 = mysqli_query($link,$query2);
		$row2 = mysqli_fetch_array($result2);
		
		$timestamp = strtotime($row2['WKEND']);
		$sunday_timestamp = strtotime('last sunday', $timestamp);
		$sunday_date = date('Y-m-d', $sunday_timestamp);

		// $batch_no = str_replace('-', '', substr($row2['WKEND'], 2, 8));
		$batch_no = Date('ymd');
		list($department,$depname) = explode('-',$row2['DEPARTMENT']);
		$text = '"'.$row2['PAYGROUP']. '","'.
					$batch_no. '","'.
					str_pad($row2['FILENUMBER'],6,"0",STR_PAD_LEFT). '","'.
					$row2['LAST'].','.$row2['FIRST']. '","'.
					$department. '","'.
					$row2['PAYRATE']. '","'.
					$row2['HOURS']. '","","'.
					date("m/d/Y",strtotime($row2['WKEND']. '- 5 days')).'","'.
					date("m/d/Y",strtotime($row2['WKEND']. '+ 1 days')).'",""'. PHP_EOL;
			if ($row2['HOURS'] > 0 ) {
				fwrite($file, $text);
			}		
		    // Mark the row as downloaded
		$update_sql = "UPDATE ic_timesheets SET adp_export = NOW() WHERE  Unique_ID = '". $recipients[$i]."'";
		// echo $update_sql;
		mysqli_query($link,$update_sql);
    }
	fclose($file);
 // Redirect the user to the CSV file download link
  $download_link = 'https://' . $_SERVER['HTTP_HOST'] . '/mngr/' . $filepath;
  echo '<BR><a href="'.$download_link.'">Download CSV</a>';
		echo "<P> <br/></P>";
		echo "<P> <br/></P>";
// Delete the CSV file from server
// unlink($file);
	
}
	
} else {
	if(is_array($_POST['recipients'])) {
		echo "Error: No Group Specified";
	}

}



// Close the database connection
// $link->close();
?>
<script>
// Add a "Check All" checkbox to select all checkboxes at once
var checkAll = document.getElementById('check_all');
checkAll.addEventListener('click', function() {
    var checkboxes = document.getElementsByName('recipients[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = checkAll.checked;
    }
});
</script>



