<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Full-Time Commission Report</title>
</head>
<body>
    <h2>Recruiter Full-Time Commission Report</h2>
    <form method="POST" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>

        <button type="submit">Submit</button>
    </form>

    <?php
	
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		echo "<p><b>Commission Report From ". $_REQUEST['start_date']. " to ". $_REQUEST['end_date']. "</b><br>";


require_once dirname(__DIR__) . '/db/db.php';
$conn = db();
if (!$conn) {
  http_response_code(500);
  echo "Database connection failed.";
  exit;
}


// Get the start and end date from the user input
$start_date = $_POST['start_date']; // replace with your actual form field name
$end_date = $_POST['end_date']; // replace with your actual form field name


$sqltest = "
SELECT SUM(billrate - payrate) AS Profit
FROM ic_timesheets
WHERE invoice_date BETWEEN '$start_date' AND '$end_date' AND invoice_type = 'f' AND NOT void 
";
// echo $sqltest;
$result2 = mysqli_query($conn, $sqltest);
$row2 = mysqli_fetch_array($result2);
echo "<p>This should total to " . number_format($row2['Profit'],2);

// Prepare and execute the SQL query
$sql = "
SELECT DISTINCT
    name,
	invoice_date,
    invoice_number,
    company_name,
	Employee_ID,
	AssignmentNumber,
	company_id,
	billrate,
	payrate,
	title,
	Unique_id,
	percent,
	first_name,
	last_name,
	fee_percent,
	salary,
	paid_date,
    SUM( (percent/100) * (billrate-payrate)) as total_profit 

FROM (
    SELECT 
        owner_1_name AS name,
        ic_timesheets.company_name as company_name,
		invoice_date,
		invoice_number,
		Employee_ID,
		AssignmentNumber,
		company_id,
		title,
		Unique_id,
		first_name,
		last_name,
        owner_1_percent as percent,
        billrate,    
        payrate,
		fee_percent,
		ic_matches.salary as salary,
		paid_date
		FROM 
        ic_timesheets 
    LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job 
        AND ic_timesheets.Employee_ID = ic_matches.candidate 
    WHERE owner_1_name IS NOT NULL AND owner_1_name <> '' AND 
        (NOT void or void = '' or void is NULL ) 
        AND invoice_type = 'f' 
        AND ic_timesheets.invoice_date BETWEEN '$start_date' AND '$end_date' 

    UNION

    SELECT 
        owner_2_name AS name, 
        ic_timesheets.company_name as company_name,
		invoice_date,
		invoice_number,
		Employee_ID,
		AssignmentNumber,
		company_id,
		title,
		Unique_id,
		first_name,
		last_name,
        owner_2_percent as percent,
        billrate,    
        payrate,
		fee_percent,
		ic_matches.salary as salary,
		paid_date
		FROM  
        ic_timesheets 
    LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job 
        AND ic_timesheets.Employee_ID = ic_matches.candidate 
    WHERE owner_2_name IS NOT NULL AND owner_2_name <> '' AND 
        (NOT void or void = '' or void is NULL ) 
        AND invoice_type = 'f' 
        AND ic_timesheets.invoice_date BETWEEN '$start_date' AND '$end_date' 

    UNION

    SELECT 
        owner_3_name AS name,
        ic_timesheets.company_name as company_name,
		invoice_date,
		invoice_number,
		Employee_ID,
		AssignmentNumber,
		company_id,
		title,
		Unique_id,
		first_name,
		last_name,
        owner_3_percent as percent,
        billrate,    
        payrate,
		fee_percent,
		ic_matches.salary as salary,
		paid_date
		FROM
        ic_timesheets 
    LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job
        AND ic_timesheets.Employee_ID = ic_matches.candidate 
    WHERE owner_3_name IS NOT NULL AND owner_3_name <> '' AND 
        (NOT void or void = '' or void is NULL ) 
        AND invoice_type = 'f' 
        AND ic_timesheets.invoice_date BETWEEN '$start_date' AND '$end_date'		
	UNION

    SELECT 
        'Unknown' AS name,
        ic_timesheets.company_name as company_name,
		invoice_date,
		invoice_number,
		Employee_ID,
		AssignmentNumber,
		company_id,
		title,
		Unique_id,
		first_name,
		last_name,
        '100' as percent,
        billrate,    
        payrate,
		fee_percent,
		ic_matches.salary as salary,
		paid_date
		FROM 
        ic_timesheets 
    LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job 
        AND ic_timesheets.Employee_ID = ic_matches.candidate 
   WHERE (COALESCE(owner_1_name, owner_2_name, owner_3_name) IS NULL OR COALESCE(owner_1_name, owner_2_name, owner_3_name) = '') 
		AND (NOT void or void = '' or void is NULL ) 
        AND invoice_type = 'f' 
        AND ic_timesheets.invoice_date BETWEEN '$start_date' AND '$end_date' 

) AS subquery 

GROUP BY
    name, company_id, AssignmentNumber, Employee_ID
";
// echo $sql;
$result = mysqli_query($conn, $sql);

// Process the results into a format suitable for pChart
// $data = array();

$profit = 0;
?>
<html>
<body>
<table border='0'>
<tr>
<td>Recruiter</td>
<td>Title</td>
<td>Talent</td>
<td>Job No</td>
<td>Company</td>
<td>Salary</td>
<td>Fee %</td>
<td>Invoice Amt</td>
<td>Cost</td>
<td>Adjusted</td>
<td>Share</td>
<td>15% Comm</td>
<td>Inv No</td>
<td>Inv Date</td>
<td>Paid</td>
</tr>
<?php
$st = 0; // subtotal
$gt = 0; // grand total
$ht = 0; // hour total
$gth = 0; //grand total hours
$name = "";
$ct = 0; //commission total
$gct = 0; // grand commission total
while ($row = mysqli_fetch_array($result)) {
	if ($name !== "" && $name !== $row['name']) {
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td>
		<td align='right'><b>".""."</b></td>
		<td align='right'><b>Totals</b></td><td></td>	
		<td align='right'><b>".number_format($st,2)."</b></td><td></td>
		<td align='right'><b>".number_format($ct,2)."</b></td><td></td><td></td><td></td></tr>".PHP_EOL;
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>".PHP_EOL;
		echo "<tr><td height='10'></td><td></td><td><td></td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><tr>".PHP_EOL;
		$st = 0;
		$ct = 0;
	} 		
		echo "<tr><td>". $row['name'] ."</td>".PHP_EOL;
		echo "<td>".$row['title']."</td>".PHP_EOL;
		echo "<td>".$row['first_name']." ".$row['last_name']."</td>".PHP_EOL;
		echo "<td><a target = '_blank' href='https://app.manatal.com/jobs/".$row['AssignmentNumber']."'>".$row['AssignmentNumber']."</a></td>".PHP_EOL;
		echo "<td width = '45'>". $row['company_name'] ."</td>".PHP_EOL;
		echo "<td>". number_format($row['salary'],2) ."</td>".PHP_EOL;
		echo "<td align='right'>". number_format($row['fee_percent'],2)."% </td>".PHP_EOL;	
		echo "<td align='right'>". number_format($row['billrate'],2)." </td>".PHP_EOL;	
		echo "<td align='right'>". number_format($row['payrate'],2)." </td>".PHP_EOL;	
		echo "<td align='right'>". number_format(($row['percent']/100) * ($row['billrate']-$row['payrate']),2)." </td>".PHP_EOL;	
		echo "<td align='right'>". number_format($row['percent'],2)."%</td>".PHP_EOL;
		$commission = ($row['percent']/100) * ($row['billrate']-$row['payrate'])  * .15;
		echo "<td align='right'>". number_format($commission ,2)."</td>".PHP_EOL;	
		echo "<td align='right'>". $row['invoice_number'] ."</td>".PHP_EOL;
		echo "<td align='right'>". $row['invoice_date'] ."</td>".PHP_EOL;
		echo "<td align='right'>". $row['paid_date'] ."</td></tr>".PHP_EOL;
		$gt = $gt + round(($row['percent']/100) * ($row['billrate']-$row['payrate']),2);
		$st = $st + round(($row['percent']/100) * ($row['billrate']-$row['payrate']),2);
		$ct = $ct + round($commission ,2);
		$gct = $gct + round($commission ,2);

		$name = $row['name'];
}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td>
		<td align='right'><b>".""."</b></td>
		<td align='right'><b>Totals</b></td><td></td>
		<td align='right'><b>".number_format($st,2)."</b></td><td></td>
		<td align='right'><b>".number_format($ct,2)."</b></td><td></td><td></td><td><td></td></td></tr>";
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
?>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td><hr></td>
<td></td>
<td><hr></td>
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td colspan="2" align = 'right'><b>Grand Totals</b></td>
<td></td>
<td align = 'right'><b><?php echo number_format($gt,2); ?></b></td>
<td></td>
<td align = 'right'><b><?php echo number_format($gct,2); ?></b></td>
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td height = "40"></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
</table>
<p> <p>
</body>
</html>

<?php
// echo "total = ".$profit;
// Close the database connection
// $conn->close();

	}
?>