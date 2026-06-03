<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit Graph</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <div style="width: 80%; margin: auto;">
        <div style='padding-top:50px;'> </div>
        <form method="post" action="">
            <label for="startDate"> Start Date &nbsp;</label>
		    <input type="date" id="startDate" name="startDate" value="<?php echo isset($_POST['startDate']) ? htmlspecialchars($_POST['startDate']) : date('Y') . '-01-01'; ?>">

			<label for="endDate"> &nbsp; End Date &nbsp;</label>        
            <input type="date" id="endDate" name="endDate" value="<?php echo isset($_POST['endDate']) ? htmlspecialchars($_POST['endDate']) : date('Y-m-d'); ?>">

 &nbsp;  <input type="checkbox" name="Walmart" value="yes" <?php echo isset($_POST['Walmart']) ? 'checked' : ''; ?>>
<label for="end_date">Include Walmart </label>
   
<input type="checkbox" name="showTotalProfit" value="yes" <?php echo isset($_POST['showTotalProfit'])  ? 'checked' : ''; ?>>

<label for="showTotalProfit">Show Total Profit &nbsp;</label>
 			
<input type="checkbox" name="showVoided" value="yes"      <?php echo isset($_POST['showVoided'])  ? 'checked' : ''; ?>>
<label for="showVoided">Show Voided &nbsp;</label>

<input type="checkbox" name="showTemp" value="yes"   <?php echo isset($_POST['showTemp'])   || empty($_POST) ? 'checked' : ''; ?> onclick="handleShowTempCheck()">
<label for="showTemp">Show Temp &nbsp;</label>

<input type="checkbox" name="showFulltime" value="yes" <?php echo isset($_POST['showFulltime']) ? 'checked' : ''; ?> onclick="handleShowFulltimeCheck()">
<label for="showFulltime">Show Full-Time &nbsp;</label>


            <button type="submit">Generate Chart</button>
        </form>

        <canvas id="profitChart"></canvas>
    </div>

    <?php
	 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the MySQL database
require_once dirname(__DIR__). '/db/db.php';
$conn = db(); 
	

    // Set default start and end dates
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y') . '-01-01';
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');

    // Query to fetch data
    $sql = "
SELECT 
	Month,
	Unique_id,
	Hours,
	billrate,
	payrate,
	OwnerName,
	OwnerPercent
FROM(
	SELECT 
    DATE_FORMAT(WeekEnding, '%Y-%m') AS Month,
	Unique_id,
    Hours,
    billrate,
    payrate,
    owner_1_name AS OwnerName,
    owner_1_percent AS OwnerPercent
FROM ic_timesheets
LEFT JOIN ic_matches 
    ON ic_timesheets.AssignmentNumber = ic_matches.job 
    AND ic_timesheets.Employee_ID = ic_matches.candidate 
WHERE owner_1_name IS NOT NULL AND owner_1_name <> '' 
    AND WeekEnding BETWEEN '$startDate' AND '$endDate' ";
if (($_REQUEST['showTemp']  ?? '')== "yes" || !$_POST) {
    $sql = $sql . "AND invoice_type = 't'  ";
}
if (($_REQUEST['showFulltime'] ?? '') == "yes" ) {
    $sql = $sql . "AND invoice_type = 'f'  ";
}
if (($_REQUEST['Walmart'] ?? '') !== "yes" ) {
    $sql = $sql . " AND (ic_timesheets.company_name NOT LIKE '%Walmart%' AND ic_timesheets.company_name NOT LIKE '%Sams%') ";
}
if (($_REQUEST['showVoided']  ?? '')!== "yes" && $_POST ) {
    $sql = $sql . " AND (NOT void or void = '' or void is NULL ) ";
}

$sql = $sql . "

UNION

SELECT 
    DATE_FORMAT(WeekEnding, '%Y-%m') AS Month,
	Unique_id,
    Hours,
    billrate,
    payrate,
    owner_2_name AS OwnerName,
    owner_2_percent AS OwnerPercent
FROM ic_timesheets
LEFT JOIN ic_matches 
    ON ic_timesheets.AssignmentNumber = ic_matches.job 
    AND ic_timesheets.Employee_ID = ic_matches.candidate 
WHERE owner_2_name IS NOT NULL AND owner_2_name <> '' 
    AND WeekEnding BETWEEN '$startDate' AND '$endDate' ";
if (($_REQUEST['showTemp']  ?? '')== "yes"  || !$_POST) {
    $sql = $sql . "AND invoice_type = 't'  ";
}
if (($_REQUEST['showFulltime']  ?? '')== "yes" ) {
    $sql = $sql . "AND invoice_type = 'f'  ";
}
if ((((($_REQUEST['Walmart']  ?? '') ?? '') ?? '') ?? '')!== "yes" ) {
    $sql = $sql . " AND (ic_timesheets.company_name NOT LIKE '%Walmart%' AND ic_timesheets.company_name NOT LIKE '%Sams%') ";
}
if (($_REQUEST['showVoided']  ?? '')!== "yes" && $_POST ) {
    $sql = $sql . " AND (NOT void or void = '' or void is NULL ) ";
}
$sql = $sql . "

UNION

SELECT 
    DATE_FORMAT(WeekEnding, '%Y-%m') AS Month,
	Unique_id,
    Hours,
    billrate,
    payrate,
    owner_3_name AS OwnerName,
    owner_3_percent AS OwnerPercent
FROM ic_timesheets
LEFT JOIN ic_matches 
    ON ic_timesheets.AssignmentNumber = ic_matches.job 
    AND ic_timesheets.Employee_ID = ic_matches.candidate 
WHERE owner_3_name IS NOT NULL AND owner_3_name <> '' 
    AND WeekEnding BETWEEN '$startDate' AND '$endDate' ";
if (($_REQUEST['showTemp']  ?? '')== "yes"  || !$_POST) {
    $sql = $sql . "AND invoice_type = 't'  ";
}
if (($_REQUEST['showFulltime']  ?? '')== "yes" ) {
    $sql = $sql . "AND invoice_type = 'f'  ";
}
if ((((($_REQUEST['Walmart']  ?? '') ?? '') ?? '') ?? '')!== "yes" ) {
    $sql = $sql . " AND (ic_timesheets.company_name NOT LIKE '%Walmart%' AND ic_timesheets.company_name NOT LIKE '%Sams%') ";
}
if (($_REQUEST['showVoided']  ?? '')!== "yes" && $_POST ) {
    $sql = $sql . " AND (NOT void or void = '' or void is NULL ) ";
}
$sql = $sql . "

UNION

SELECT 
    DATE_FORMAT(WeekEnding, '%Y-%m') AS Month,
	Unique_id,
    Hours,
    billrate,
    payrate,
    'Unknown' AS OwnerName,
    100 AS OwnerPercent
FROM ic_timesheets
LEFT JOIN ic_matches 
    ON ic_timesheets.AssignmentNumber = ic_matches.job 
    AND ic_timesheets.Employee_ID = ic_matches.candidate 
WHERE  WeekEnding BETWEEN '$startDate' AND '$endDate' ";
if (($_REQUEST['showTemp']  ?? '')== "yes"  || !$_POST) {
    $sql = $sql . "AND invoice_type = 't'  ";
}
if (($_REQUEST['showFulltime']  ?? '')== "yes" ) {
    $sql = $sql . "AND invoice_type = 'f'  ";
}
if ((((($_REQUEST['Walmart']  ?? '') ?? '') ?? '') ?? '')!== "yes" ) {
    $sql = $sql . " AND (ic_timesheets.company_name NOT LIKE '%Walmart%' AND ic_timesheets.company_name NOT LIKE '%Sams%') ";
}
if (($_REQUEST['showVoided']  ?? '')!== "yes" && $_POST ) {
    $sql = $sql . " AND (NOT void or void = '' or void is NULL ) ";
}

    $sql = $sql . "AND (
        (owner_1_name IS NULL OR owner_1_name = '')
        AND (owner_2_name IS NULL OR owner_2_name = '')
        AND (owner_3_name IS NULL OR owner_3_name = '')
    ) ";

$sql = $sql . "
) AS subquery 
ORDER BY Month";

// echo $sql;
// exit();
$result = $conn->query($sql);
if (!$result) {
    die("SQL Error: " . $conn->error);
}

// Function to generate all months between start and end dates
function getMonths($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('first day of next month');

    $interval = new DateInterval('P1M');
    $daterange = new DatePeriod($start, $interval, $end);

    $months = [];
    foreach ($daterange as $date) {
        $months[] = $date->format('Y-m');
    }

    return $months;
}

// Get all months between the start and end dates
$labels = getMonths($startDate, $endDate);

$owners = [];
while ($row = $result->fetch_assoc()) {
    $month = $row['Month'];
    $ownerName = $row['OwnerName'];
    // $profit = ($row['billrate'] - $row['payrate']) * $row['Hours'];
    $profit = ($row['OwnerPercent'] / 100) * ($row['billrate'] - $row['payrate']) * $row['Hours'];
	 
    if (!isset($owners[$ownerName])) {
        $owners[$ownerName] = array_fill(0, count($labels), 0);
    }

    $monthIndex = array_search($month, $labels);
    $owners[$ownerName][$monthIndex] += $profit;
}

// Fill gaps in data for each owner
foreach ($owners as $ownerName => $profits) {
    if (count($profits) < count($labels)) {
        $owners[$ownerName] = array_pad($profits, count($labels), 0);
    }
}

$datasets = [];
foreach ($owners as $owner => $data) {
    $datasets[] = [
        'label' => $owner,
        'data' => $data,
        'fill' => false,
        'borderColor' => randomColor(),
    ];
}

if (isset($_POST['showTotalProfit']) && $_POST['showTotalProfit'] === 'yes') {
    $totalProfit = array_fill(0, count($labels), 0);
    foreach ($datasets as $dataset) {
        $totalProfit = array_map(function ($a, $b) { return $a + $b; }, $totalProfit, $dataset['data']);
    }
    $datasets[] = [
        'label' => 'Total Profit',
        'data' => $totalProfit,
        'fill' => false,
        'borderColor' => randomColor(),
    ];
}

// Generate a random color for each line
function randomColor() {
    $letters = '0123456789ABCDEF';
    $color = '#';
    for ($i = 0; $i < 6; $i++) {
        $color .= $letters[rand(0, 15)];
    }
    return $color;
}
?>

<script>
    // Chart.js configuration
    var ctx = document.getElementById('profitChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: <?php echo json_encode($datasets); ?>
        },
        options: {
            scales: {
                x: {
                    type: 'category',
                    labels: <?php echo json_encode($labels); ?>,
                    beginAtZero: true,
                    max: <?php echo count($labels) - 1; ?>,
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var showTempCheckbox = document.getElementsByName('showTemp')[0];
    var showFulltimeCheckbox = document.getElementsByName('showFulltime')[0];
	showFulltimeCheckbox.addEventListener('change', function() {
        if (this.checked) {
            showTempCheckbox.checked = false;
        }
    });

    showTempCheckbox.addEventListener('change', function() {
        if (this.checked) {
            showFulltimeCheckbox.checked = false;
        }
    });
});
</script>