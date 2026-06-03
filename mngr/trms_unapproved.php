<?php include 'trms_header.php'; ?>
<?php

// Connect to the MySQL database
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

$strSQL = "SELECT  'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", wt.Employee_ID as EMPID ";
$strSQL = $strSQL . ", wt.billrate as BILLRATE ";
$strSQL = $strSQL . ", wt.payrate as PAYRATE ";
$strSQL = $strSQL . ", wt.Hours as HOURS ";
$strSQL = $strSQL . ", REPLACE(oj.customer_name,' ','_') as JOB ";
$strSQL = $strSQL . ", REPLACE(wt.title,' ','_') as ITEM ";
$strSQL = $strSQL . ", wt.Assignment_ID as PROJ ";
$strSQL = $strSQL . ", wt.AssignmentNumber as xORDER ";
$strSQL = $strSQL . ", oj.Primary_Contact_ID as ORDERTAKENID ";
$strSQL = $strSQL . ", oj.Second_Contact_ID as REPORTID ";
$strSQL = $strSQL . ", oj.Primary_Contact_First as PFIRST "; 
$strSQL = $strSQL . ", oj.Primary_Contact_Last as PLAST "; 
$strSQL = $strSQL . ", wt.Primary_Contact_Email as email ";
$strSQL = $strSQL . ", oj.Second_Contact_First as SFIRST ";
$strSQL = $strSQL . ", oj.Second_Contact_Last as SLAST ";
$strSQL = $strSQL . ", wt.Second_Contact_Email as semail  ";
$strSQL = $strSQL . ", wt.Unique_id as UNUMB  ";
$strSQL = $strSQL . ", '40' as DURATION ";
$strSQL = $strSQL . ", '1' as BILLINGSTATUS ";
$strSQL = $strSQL . ", 'CONTR' as PITEM ";
$strSQL = $strSQL . ", '0' as BITEM ";
$strSQL = $strSQL . ", 'Yes' as Contract "; 

for ($i = 1; $i <= 7; $i++) {

 	$strSQL = $strSQL . ", wt.TimeInHr" . $i . " as INHR" . $i . " ";
	$strSQL = $strSQL . ", wt.TimeOutHr" . $i . " as OUTHR" . $i . " ";
	$strSQL = $strSQL . ", wt.Break" . $i . " as BREAK" . $i . " ";
}
$strSQL = $strSQL . ", wt.Hours as HOURS " ;
$strSQL = $strSQL . ", wt.Continuing as CONT " ;
$strSQL = $strSQL . ", wt.ApproveDate as APPROVE " ;
$strSQL = $strSQL . ", wt.SentDate as SENT " ;
$strSQL = $strSQL . ", wt.DeclineDate as DECLINE " ;
$strSQL = $strSQL . ", wt.Weekending as WKEND " ;
$strSQL = $strSQL . "from ic_webtime wt ";

$strSQL = $strSQL . "LEFT JOIN ic_candidate_open_jobs oj ON (oj.resource_id = wt.employee_id AND oj.order_id = wt.Assignment_ID) 

WHERE wt.ApproveDate = '0000-00-00 00:00:00' AND wt.ExportDate = '0000-00-00 00:00:00'";

// Query the database for the list of recipients
$result = mysqli_query($link,$strSQL);	

// Start the HTML form
echo '<form method="post">';
echo '<br>';
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

    // echo '<td><input type="checkbox" name="recipients[]" value="' . $row['UNUMB'] . '"></td>';
	// echo '<input type="hidden" name="WKEND[]" value="' . $row['WKEND'] . '">';
	// echo '<input type="hidden" name="PROJ[]"  value="' . $row['PROJ']  . '">';
	// echo '<input type="hidden" name="EMPID[]" value="' . $row['EMPID'] . '">';
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
	echo "<td>" . $row['PROJ'] . "</td>";
	echo "<td>" . $row['EMPID'] . "</td>";
    echo '</tr>';
}

// Close the table and add the submit button
echo '</table><p>';
echo '<input type="submit" value="Download and Mark">';
echo '</form>';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
    // Get the selected recipients
    $recipients = $_POST['recipients'];
	$WKEND = $_POST['WKEND'];
	
	 // Define the CSV file name and path
	$filename = 'selected_timesheets.csv';
	$filepath = '' . $filename;

	// Open the CSV file for writing
	$file = fopen($filepath, 'w');
  
	// Open file for appending
	$file = fopen($filename, "a");
	
	// make header
	 $text = '"Worker","Date","Regular Hours","Overtime Hours","Double Time Hours","OrderID"'. PHP_EOL;
	 fwrite($file, $text);

    // Loop through the recipients and build the file

	$filename = "time.txt";
	for($i=0; $i<count($recipients); $i++) {
	
 		$query2 = $strSQL . " AND wt.Unique_id= '". $recipients[$i]."'";
// 		AND wt.Assignment_ID = '". $PROJ[$i]."' AND wt.Weekending = '" .$WKEND[$i]. "'";

		// echo $query2;

		$result2 = mysqli_query($link,$query2);
		$row2 = mysqli_fetch_array($result2);
		
		$timestamp = strtotime($row2['WKEND']);
		$sunday_timestamp = strtotime('last sunday', $timestamp);
		$sunday_date = date('Y-m-d', $sunday_timestamp);

		for ($t = 0; $t <= 7; $t++) {
			$wdays = $t-1;
			// $dow = date('Y-m-d', strtotime($wdays.' days', strtotime($row2['WKEND'])));
			$dow = date('Y-m-d', strtotime(' +'.$wdays.' day', strtotime($sunday_date)));
			$hrs = ($row2['OUTHR'.$t]-$row2['INHR'.$t])-$row2['BREAK'.$t];	
			$text = $row2['FIRST']." ".$row2['LAST'].",".  $dow.",".$hrs.",0,0,".$row2['PROJ']."\n";
			if ($hrs > 0) {
				fwrite($file, $text);
			}
		}
		    // Mark the row as downloaded
		$update_sql = "UPDATE ic_webtime SET ExportDate = NOW() WHERE  Unique_ID = '". $recipients[$i]."'";
		mysqli_query($link,$update_sql);
    }
	fclose($file);
 // Redirect the user to the CSV file download link
  $download_link = 'https://' . $_SERVER['HTTP_HOST'] . '/mngr/' . $filepath;
  echo '<BR><a href="'.$download_link.'">Download CSV</a>';

// Delete the CSV file from server
// unlink($file);
	

	
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



