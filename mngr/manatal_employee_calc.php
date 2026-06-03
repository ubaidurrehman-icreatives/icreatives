<?php include 'manatal_header.php'; ?>
<h1>MANATAL EMPLOYEE HOURS CALCULATOR</h1>
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
    wt.invoice_number as `INVNUM`,
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
	wt.WeekEnding >= '".$_REQUEST['start_date']."' 
	AND wt.WeekEnding <= '".$_REQUEST['end_date']."' 
    AND wt.Employee_ID = '".$_REQUEST['employee']."' 
	
"; 

if( !isset($_REQUEST['voided'])) {
	$strSQL = $strSQL ." AND wt.void = FALSE ";
}	

if(!empty($_REQUEST['company'])) {
	$strSQL = $strSQL ." AND wt.company_name like '%".$_REQUEST['company']."%' ";
}	
if(!empty($_REQUEST['assignment'])) {
	$strSQL = $strSQL ." AND wt.AssignmentNumber = '".$_REQUEST['assignment']."' ";
}

$strSQLn = $strSQL . "ORDER BY `WeekEnding` ASC;";
// echo $strSQLn;
// Query the database for the list of recipients
$result = mysqli_query($link,$strSQLn);	

// Start the HTML form
echo '<form name = "form1" method="post">';
echo '&nbsp;&nbsp;&nbsp;Pay  <input type="checkbox" name="show_pay" value="'.$_REQUEST['show'].'">';
echo '&nbsp;&nbsp;&nbsp;Bill <input type="checkbox" name="show_bill" value="'.$_REQUEST['bill'].'">';
echo '&nbsp;&nbsp;&nbsp;Voided <input type="checkbox" name="voided">';
echo '&nbsp;&nbsp;&nbsp;Start Date: <input type = "date" id = "start_date" name = "start_date" value="'.$_REQUEST['start_date'].'" required>';
echo '&nbsp;&nbsp;&nbsp;End Date: <input type = "date" id = "end_date" name = "end_date" value="'.$_REQUEST['end_date'].'" required>';
echo '&nbsp;&nbsp;&nbsp;Employee <input type = "text" id = "employee" name = "employee" placeholder ="Employee ID" value="'.$_REQUEST['employee'].'" required>';
echo '&nbsp;&nbsp;&nbsp;Company <input type = "text" id = "company" name = "company" placeholder ="Any part of name">';
echo '&nbsp;&nbsp;&nbsp;Job Number <input type = "text" id = "assignment" value="'.$_REQUEST['assignment'].'" name = "assignment">';

echo '&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">';
echo '</form>';
echo '<form name = "form2" method="post">';
// echo '<input type="hidden" name="pay_group" id = "pay_group" value = "'.$_REQUEST['pay_group'].'">';
// echo '<input type="hidden" name="pay_date" id = "pay_group" value = "'.$_REQUEST['pay_date'].'">';

// Output the table headers
echo '<table>';
echo "<thead><tr><th></th><th></th></tr></thead>";
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
<th>Invoice</th>
</tr>';

// Loop through the results and output each row
$total_hours = 0;
$total_pay = 0;
$total_bill = 0;
$total_count = 0;
while ($row = mysqli_fetch_array($result)) {
	$total_count = $total_count + 1;
	$total_hours = $total_hours + $row["HOURS"];
	$total_pay = $total_pay + round($row['PAYRATE'] * $row['HOURS'],2);
	$total_bill = $total_bill + round($row['BILLRATE'] * $row['HOURS'],2);
	$approved_date = strtotime( $row['APPROVE']);
	$week_ending = strtotime( $row['WKEND']);
    echo '<tr align="center">';
	    echo '<td>';
		
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
	echo "<td>$" . number_format($row['BILLRATE'] * $row['HOURS'],2) . "</td>";
	echo "<td>$" . $row['PAYRATE'] . "/hr</td>";
	echo "<td>$" . number_format($row['PAYRATE'] * $row['HOURS'],2) . "</td>";
	echo "<td>" . date("m/d/Y",$week_ending) . '</td>';
	echo "<td>".  $row["HOURS"]."</td>";
    echo "<td>" . date("m/d/Y",$approved_date) . '</td>';
	echo "<td><a target = '_blank' href = 'https://app.manatal.com/jobs/" . $row['PROJ']."'>" . $row['PROJ']. "</a></td>";
	echo "<td><a target = '_blank' href = 'https://app.manatal.com/candidates/" . $row['EMPID']."'>" . $row['EMPID']. "</a></td>";
	echo "<td>" . $row['INVNUM'] . "</td>";
    echo '</tr>';
}

// Close the table and add the submit button
// ECHO "<p><b>For Pay Date: </b>". $_REQUEST['pay_date'];
echo '<tr align="center">
<th> </th>
<th></th>
<th></th>
<th></th>
<th>'.$total_hours.'</th>
<th></th>
<th>'.$total_bill.'</th>
<th></th>
<th>'.$total_pay.'</th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>';
echo '</table><p>';

// echo '<input type="submit" value="Download and Mark">';
// echo '</form>';


// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

    // Get the selected recipients

	$WKEND = $_POST['WKEND'];
	
	 // Define the CSV file name and path
	$filename = $_REQUEST['employee'].' Report.csv';
	$filepath = '' . $filename;

	// Open the CSV file for writing
	$file = fopen($filepath, 'w');
  
	// Open file for appending
	$file = fopen($filename, "a");
	
	
	
	// make header
	 $text = '"Name","Position","Hours"';
	 if($_REQUEST['show_bill']) {
		$text = $text . ',"Bill Rate","Invoice Amount"';
	}
	 if($_REQUEST['show_pay']) {
		$text = $text . ',"Pay Rate","Pay Amount"';
	}
	 
	 $text = $text . ',"Wk Ending"'. PHP_EOL;

	 fwrite($file, $text);

    // Loop through the recipients and build the file

	$result = mysqli_query($link,$strSQLn);	
	while ($row = mysqli_fetch_array($result)) {
		$text = '"'.$row['LAST'].','.$row['FIRST']. '","'.
					$row['ITEM']. '","'.
					$row['HOURS']. '","';
		if($_REQUEST['show_bill']) {
			$text = $text .
					$row['BILLRATE']. '","'.
					round($row['BILLRATE'] * $row['HOURS'],2). '","';					
		}
		if($_REQUEST['show_pay']) {
			$text = $text .
					$row['PAYRATE']. '","'.
					round($row['PAYRATE'] * $row['HOURS'],2). '","';					
		}
			$text = $text . date("m/d/Y",strtotime($row['WKEND'])). '","';
			$text = $text . $row['INVNUM']. '"'. PHP_EOL;
				fwrite($file, $text);
    }
	
	
 $text = ',,"'.$total_hours.'"';
	 if($_REQUEST['show_bill']) {
		$text = $text . ',,"'.$total_bill.'"';
	}
	 if($_REQUEST['show_pay']) {
		$text = $text . ',,"'.$total_pay.'"';
	}
	 
	 $text = $text . ',,'. PHP_EOL;

	 fwrite($file, $text);	

	fclose($file);
 // Redirect the user to the CSV file download link
  $download_link = 'https://' . $_SERVER['HTTP_HOST'] . '/mngr/' . $filepath;
  echo '<BR><a href="'.$download_link.'">Download CSV</a>';

// Delete the CSV file from server
// unlink($file);
	
}
	


// }



// Close the database connection
// $link->close();
?>




