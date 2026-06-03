</a><?php include 'manatal_header.php'; ?>
<?php	
echo "<h1>MANATAL PO STATUS REPORT</h1>";
// Connect to the MySQL database

require_once __DIR__ . '/../db/db.php';
$link = db();   


// Retrieve form values
$week_starting = isset($_REQUEST['week_starting']) ? $_REQUEST['week_starting'] : '';
$week_ending = isset($_REQUEST['week_ending']) ? $_REQUEST['week_ending'] : '';
$company_name = isset($_REQUEST['company_name']) ? $_REQUEST['company_name'] : '';
$include_closed = isset($_REQUEST['include_closed']) ? $_REQUEST['include_closed'] : '';

?>

<form method="post">
    Week Starting: <input type="date" id="week_starting" name="week_starting" value="<?php echo htmlspecialchars($week_starting); ?>">
    Week Ending: <input type="date" id="week_ending" name="week_ending" value="<?php echo htmlspecialchars($week_ending); ?>">
    Company: <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>">
    <br>
    Include Closed Records: <input type="checkbox" id="include_closed" name="include_closed" value="1" <?php if ($include_closed == '1') echo 'checked'; ?>>
    <input type="submit" value="Submit">
</form>

<P>

<?php
$strSQL = "SELECT m.company_name, m.job_name, TRIM(m.po_number) AS po_number, m.po_end_date, m.po_amount, 
COALESCE(SUM(ROUND(t.billrate * t.Hours, 2)), 0) AS total_bill_amount 
FROM ic_timesheets t
JOIN ic_matches m ON m.job = t.AssignmentNumber AND t.Employee_ID = m.candidate 
WHERE (t.void <> 1) 
AND m.po_number <> '' AND m.po_number IS NOT NULL ";

// Apply date filters only if they are provided
if (!empty($week_ending)) {
    $strSQL .= " AND t.WeekEnding <= '". $week_ending . "' ";
}
if (!empty($week_starting)) {
    $strSQL .= " AND t.WeekEnding >= '". $week_starting . "' ";
}

// Apply company name filter if provided
if (!empty($company_name)) {
    $strSQL .= " AND m.company_name LIKE '%". $company_name . "%' ";
}

// Default to exclude closed records unless the checkbox is checked
if ($include_closed != '1') {
    $strSQL .= " AND m.closed = 0 ";
}

$strSQL .= " GROUP BY po_number";
// echo $strSQL;
// Query the database for the list of recipients
$result = mysqli_query($link, $strSQL);

// Output the table headers
echo '<table>';
echo '<tr align="center">
<th>PO Number</th>
<th>Client</th>
<th>Ending</th>
<th>PO Amount</th>
<th>PO To Date</th>
</tr>';

// Loop through the results and output each row
while ($row = mysqli_fetch_array($result)) {
    echo '<tr align="center">';
    echo '<td ALIGN="LEFT"><a target="_blank" href="/mngr/manatal_po_report.php?po=' . $row['po_number'] . '">' . $row['po_number'] . '</a></td>';
    echo '<td ALIGN="LEFT">' . $row['company_name'] . '</td>';
    echo "<td>" . $row['po_end_date'] . '</td>';
    echo "<td ALIGN='right'>" . number_format($row['po_amount'], 2) . "</td>";
    if ($row["po_amount"] >= $row["total_bill_amount"]) {
        echo "<td align='right'>" . number_format($row["total_bill_amount"], 2) . "</td>";
    } else {
        echo "<td align='right'><font color='red'>" . number_format($row["total_bill_amount"], 2) . "</font></td>";
    }
    echo '</tr>';
}

// Close the table and add the submit button
echo '</table><p>';
?>
