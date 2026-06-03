<h1>MANATAL TIMESHEET IMPORT</h1>
<?php include 'manatal_header.php'; ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for="selectedDate">Select a Friday:</label>
  <input type="date" id="selectedDate" name="selectedDate" required>
  <button type="submit">Submit</button>
</form>

<script>
// Initialize the date picker
$(document).ready(function() {
  $('#selectedDate').datepicker({
    format: 'yy-mm-dd',
    autoclose: true,
    beforeShowDay: function(date) {
      // Disable non-Friday dates
      return [date.getDay() == 5, ''];
    },
    onSelect: function(dateText) {
      // Fill out the field with the selected date
      $('#selectedDate').val(dateText);
    }
  });
});
</script>

<?php
// echo "selected date = ". $_REQUEST['selectedDate'];

$lastFriday = date('Y-m-d', strtotime('last friday'));

$lastFriday = $_REQUEST['selectedDate'];
if (empty($lastFriday)) { $lastFriday = date('Y-m-d', strtotime('last friday')); }


echo "Results for week ending: ". $lastFriday;


// Connect to the MySQL database
require_once __DIR__ . '/../db/db.php';
$link = db(); 

$strSQL = "SELECT  'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", wt.Employee_ID as EMPID ";
$strSQL = $strSQL . ", wt.billrate as BILLRATE ";
$strSQL = $strSQL . ", wt.payrate as PAYRATE ";
$strSQL = $strSQL . ", wt.Hours as HOURS ";
$strSQL = $strSQL . ", REPLACE(oj.company_name,' ','_') as JOB ";
$strSQL = $strSQL . ", REPLACE(wt.title,' ','_') as ITEM ";
$strSQL = $strSQL . ", wt.Assignment_ID as PROJ ";
$strSQL = $strSQL . ", wt.AssignmentNumber as xORDER ";
// $strSQL = $strSQL . ", oj.Primary_Contact_ID as ORDERTAKENID ";
// $strSQL = $strSQL . ", oj.Second_Contact_ID as REPORTID ";
// $strSQL = $strSQL . ", oj.Primary_Contact_First as PFIRST "; 
// $strSQL = $strSQL . ", oj.Primary_Contact_Last as PLAST "; 
$strSQL = $strSQL . ", wt.Primary_Contact_Email as email ";
// $strSQL = $strSQL . ", oj.Second_Contact_First as SFIRST ";
// $strSQL = $strSQL . ", oj.Second_Contact_Last as SLAST ";
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
$strSQL = $strSQL . ", wt.ExportDate as EXPORT " ;
$strSQL = $strSQL . "from ic_timesheets wt ";

$strSQL = $strSQL . "LEFT JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.Assignment_ID) 
WHERE wt.void = FALSE AND wt.WeekEnding = '". $lastFriday ."' ORDER BY LAST ASC";
// echo $strSQL;
// Query the database for the list of recipients
$result = mysqli_query($link,$strSQL);	

// Start the HTML form

echo '<br>';
// Output the table headers
echo '<table>';
echo "<thead><tr><th><input type='checkbox' id='check_all'></th><th><P>Check All</p></th></tr></thead>";
echo '<tr align="center">

<th>Talent</th>
<th>Position</th>
<th>Client</th>
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
<th>Declined Date</th>
<th>Export Date</th>
</tr>';

// Loop through the results and output each row
while ($row = mysqli_fetch_array($result)) {
	$approved_date = strtotime( $row['APPROVE']);
	$week_ending = strtotime( $row['WKEND']);
    echo '<tr align="center">';
   // echo '<td>' . $row['UNUMB'] . '</td>';
	// echo '<input type="hidden" name="WKEND[]" value="' . $row['WKEND'] . '">';
	// echo '<input type="hidden" name="PROJ[]"  value="' . $row['PROJ']  . '">';
	// echo '<input type="hidden" name="EMPID[]" value="' . $row['EMPID'] . '">';
    echo '<td ALIGN="LEFT">' . $row['FIRST'] . ' ' .$row['LAST'] . '</td>';
	echo '<td ALIGN="LEFT">' . $row['ITEM'] .  '</td>';
	echo '<td ALIGN="LEFT">' . $row['JOB'] . "</td>";
	echo "<td>" . $row['HOURS'] . "</td>";
	echo "<td>$" . $row['BILLRATE'] . "/hr</td>";
	echo "<td>$" . round($row['BILLRATE'] * $row['HOURS'],2) . "</td>";
	echo "<td>$" . $row['PAYRATE'] . "/hr</td>";
	echo "<td>$" . round($row['PAYRATE'] * $row['HOURS'],2) . "</td>";
	echo "<td>" . date("m/d/Y",$week_ending) . '</td>';
	echo "<td>".  $row["HOURS"]."</td>";
	if ($row['APPROVE'] !== "0000-00-00 00:00:00"){
		echo "<td>" . date("m/d/Y",$approved_date) . '</td>';
	}else{ echo "<td> </td>";}
	echo "<td>" . $row['PROJ'] . "</td>";
	echo "<td>" . $row['EMPID'] . "</td>";
	echo "<td>";
	if ($row['DECLINE'] !== "0000-00-00 00:00:00"){echo date("m/d/Y",strtotime( $row['DECLINE']));}
	echo "</td>";
	echo "<td>";
	if ($row['EXPORT'] !== "0000-00-00 00:00:00"){echo date("m/d/Y",strtotime( $row['EXPORT']));}
	echo "</td>";
    echo '</tr>';
}

// Close the table and add the submit button
echo '</table><p>';


// Check if the form has been submitted




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



