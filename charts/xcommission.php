<!DOCTYPE html>
<?php
session_start();

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
// Connect to the MySQL database
require_once __DIR__ . '/../db/db.php';
$conn = db(); 
	

// ---- Defaults for date inputs (current year) ----
$year = date('Y');
$defaultStart = "$year-01-01";
$defaultEnd   = "$year-12-31";

// ---- Persist inputs (POST -> SESSION -> defaults) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['start_date'] = $_POST['start_date'] ?? $defaultStart;
    $_SESSION['end_date']   = $_POST['end_date']   ?? $defaultEnd;
    $_SESSION['selectName'] = $_POST['selectName'] ?? '';
    $_SESSION['Walmart']    = isset($_POST['Walmart']) ? 'yes' : '';
    $_SESSION['HideProfit'] = isset($_POST['HideProfit']) ? 'yes' : '';
}

$start_val      = $_SESSION['start_date']  ?? $defaultStart;
$end_val        = $_SESSION['end_date']    ?? $defaultEnd;
$selectNameVal  = $_SESSION['selectName']  ?? '';
$walmartVal     = $_SESSION['Walmart']     ?? '';
$hideProfitVal  = $_SESSION['HideProfit']  ?? '';

// ---- Fetch users for the dropdown ----

$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/', [
  'headers' => [
    'Authorization' => $token,
    'accept'        => 'application/json',
  ],
]);
$jsonData    = (string)$response->getBody();
$optionsData = json_decode($jsonData, true);
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recruiter Temp Commission Report</title>
</head>
<body>
  <h2>Recruiter Commission Report</h2>

  <form method="POST" action="">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date"
           value="<?= htmlspecialchars($start_val) ?>" required>

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date"
           value="<?= htmlspecialchars($end_val) ?>" required>

    <label for="selectName">Select Name:</label>
    <select id="selectName" name="selectName">
      <option value=""></option>
      <?php
      if (!empty($optionsData['results']) && is_array($optionsData['results'])) {
          foreach ($optionsData['results'] as $item) {
              $dn = $item['display_name'] ?? '';
              $sel = ($dn === $selectNameVal) ? 'selected' : '';
              echo '<option value="'.htmlspecialchars($dn, ENT_QUOTES).'" '.$sel.'>'.htmlspecialchars($dn).'</option>';
          }
      }
      ?>
    </select>

    <label for="Walmart">Include Walmart:</label>
    <input type="checkbox" id="Walmart" name="Walmart" value="yes" <?= $walmartVal === 'yes' ? 'checked' : '' ?>>

    <label for="HideProfit">Hide Totals:</label>
    <input type="checkbox" id="HideProfit" name="HideProfit" value="yes" <?= $hideProfitVal === 'yes' ? 'checked' : '' ?>>

    <button type="submit">Submit</button>
  </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p><b>Commission Report From ". htmlspecialchars($start_val). " to ". htmlspecialchars($end_val). "</b><br>";

    $start_date = $start_val;
    $end_date   = $end_val;
    $full_name  = $selectNameVal;

    // --- Main query (same logic, with stable ORDER BY) ---
    $sql = "
    SELECT DISTINCT
        name,
        WeekEnding,
        invoice_type,
        company_name,
        Employee_ID,
        AssignmentNumber,
        company_id,
        comm_hours,
        bill_rate,
        pay_rate,
        title,
        Unique_id,
        percent,
        first_name,
        last_name,
        SUM( (percent/100) * ((bill_rate-pay_rate) * comm_hours)) as total_profit,
        SUM(comm_hours) as total_hours
    FROM (
        SELECT 
            owner_1_name AS name,
            ic_timesheets.company_name as company_name,
            WeekEnding,
            Employee_ID,
            AssignmentNumber,
            company_id,
            title,
            Unique_id,
            first_name,
            last_name,
            CASE 
                WHEN invoice_type = 'f' THEN 'Full-Time'
                WHEN invoice_type = 't' THEN 'Contract'
                ELSE 'Unknown'
            END AS invoice_type,
            owner_1_percent as percent,
            hours AS comm_hours,
            billrate as bill_rate,    
            payrate as pay_rate 
        FROM ic_timesheets
        LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job
            AND ic_timesheets.Employee_ID = ic_matches.candidate
        WHERE owner_1_name IS NOT NULL AND owner_1_name <> '' AND 
            (NOT void or void = '' or void is NULL )
            AND invoice_type = 't' 
            AND ic_timesheets.WeekEnding BETWEEN '$start_date' AND '$end_date'

        UNION

        SELECT 
            owner_2_name AS name,
            ic_timesheets.company_name as company_name,
            WeekEnding,
            Employee_ID,
            AssignmentNumber,
            company_id,
            title,
            Unique_id,
            first_name,
            last_name,
            CASE 
                WHEN invoice_type = 'f' THEN 'Full-Time'
                WHEN invoice_type = 't' THEN 'Contract'
                ELSE 'Unknown'
            END AS invoice_type,
            owner_2_percent as percent,
            hours AS comm_hours,
            billrate as bill_rate,    
            payrate as pay_rate 
        FROM ic_timesheets
        LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job
            AND ic_timesheets.Employee_ID = ic_matches.candidate
        WHERE owner_2_name IS NOT NULL AND owner_2_name <> '' AND 
            (NOT void or void = '' or void is NULL )
            AND invoice_type = 't' 
            AND ic_timesheets.WeekEnding BETWEEN '$start_date' AND '$end_date'

        UNION

        SELECT 
            owner_3_name AS name,
            ic_timesheets.company_name as company_name,
            WeekEnding,
            Employee_ID,
            AssignmentNumber,
            company_id,
            title,
            Unique_id,
            first_name,
            last_name,
            CASE 
                WHEN invoice_type = 'f' THEN 'Full-Time'
                WHEN invoice_type = 't' THEN 'Contract'
                ELSE 'Unknown'
            END AS invoice_type,
            owner_3_percent as percent,
            hours AS comm_hours,
            billrate as bill_rate,    
            payrate as pay_rate 
        FROM ic_timesheets
        LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job
            AND ic_timesheets.Employee_ID = ic_matches.candidate
        WHERE owner_3_name IS NOT NULL AND owner_3_name <> '' AND 
            (NOT void or void = '' or void is NULL )
            AND invoice_type = 't' 
            AND ic_timesheets.WeekEnding BETWEEN '$start_date' AND '$end_date'		

        UNION

        SELECT 
            'Unknown' AS name,
            ic_timesheets.company_name as company_name,
            WeekEnding,
            Employee_ID,
            AssignmentNumber,
            company_id,
            title,
            Unique_id,
            first_name,
            last_name,
            CASE 
                WHEN invoice_type = 'f' THEN 'Full-Time'
                WHEN invoice_type = 't' THEN 'Contract'
                ELSE 'Unknown'
            END AS invoice_type,
            '100' AS percent,
            hours AS comm_hours,
            billrate as bill_rate,    
            payrate as pay_rate 
        FROM ic_timesheets
        LEFT JOIN ic_matches ON ic_timesheets.AssignmentNumber = ic_matches.job
            AND ic_timesheets.Employee_ID = ic_matches.candidate
        WHERE (COALESCE(owner_1_name, owner_2_name, owner_3_name) IS NULL OR COALESCE(owner_1_name, owner_2_name, owner_3_name) = '')
            AND (NOT void or void = '' or void is NULL )
            AND invoice_type = 't' 
            AND ic_timesheets.WeekEnding BETWEEN '$start_date' AND '$end_date'
    ) AS subquery
    ";

    // Filters
    $wherePieces = [];
    if ($walmartVal !== "yes") {
        $wherePieces[] = "(company_name NOT LIKE '%Walmart%' AND company_name NOT LIKE '%Sams%')";
    }
    if (!empty($full_name)) {
        $safeName = mysqli_real_escape_string($conn, $full_name);
        $wherePieces[] = "(name = '$safeName')";
    }
    if (!empty($wherePieces)) {
        $sql .= " WHERE " . implode(" AND ", $wherePieces);
    }

    $sql .= "
    GROUP BY
        name, company_id, AssignmentNumber, Employee_ID
    ORDER BY
        name, company_id, AssignmentNumber, Employee_ID
    ";

    $result = mysqli_query($conn, $sql);
    ?>
    <table border='0'>
    <tr>
      <td>Recruiter</td>
      <td>Title</td>
      <td>Talent</td>
      <td>Job No</td>
      <td>Company</td>
      <td>Share</td>
      <td>Hours</td>
      <td>Profit</td>
      <td>3.5% Comm</td>
    </tr>
    <?php
    $st = 0;   // subtotal (profit)
    $gt = 0;   // grand total (profit)
    $ht = 0;   // subtotal hours
    $gth = 0;  // grand total hours
    $stc = 0;  // NEW: subtotal commission (sum of per-row rounded)
    $gtc = 0;  // NEW: grand total commission (sum of per-row rounded)
    $name = "";

    while ($row = mysqli_fetch_array($result)) {
        if ($name !== "" && $name !== $row['name']) {
            // Intermediate subtotal row (always visible)
            echo "<tr><td></td><td></td><td></td><td></td><td></td><td align='right'><b>Total</b></td><td align='right'><b>".number_format($ht,2)."</b></td><td align='right'><b>".number_format($st,2)."</b></td><td align='right'><b>".number_format($stc,2)."</b></td></tr>\n";
            echo "<tr><td colspan='9'></td></tr>\n";
            echo "<tr><td height='10' colspan='9'></td></tr>\n";
            $st = 0;
            $ht = 0;
            $stc = 0; // reset commission subtotal
        }

        $rowComm = round($row['total_profit'] * 0.035, 2); // per-row commission, rounded

        echo "<tr>";
        echo "<td>". htmlspecialchars($row['name']) ."</td>";
        echo "<td>". htmlspecialchars($row['title']) ."</td>";
        echo "<td>". htmlspecialchars($row['first_name']." ".$row['last_name']) ."</td>";
        echo "<td><a target='_blank' href='https://app.manatal.com/jobs/".htmlspecialchars($row['AssignmentNumber'])."'>".htmlspecialchars($row['AssignmentNumber'])."</a></td>";
        echo "<td>". htmlspecialchars($row['company_name']) ."</td>";
        echo "<td align='right'>". number_format($row['percent'],2)."% </td>";
        echo "<td align='right'>". number_format($row['total_hours'],2)."</td>";
        echo "<td align='right'>". number_format($row['total_profit'],2)."</td>";
        echo "<td align='right'>". number_format($rowComm,2)."</td>";
        echo "</tr>\n";

        $gt  += round($row['total_profit'], 2);
        $st  += round($row['total_profit'], 2);
        $ht  += round($row['total_hours'], 2);
        $gth += round($row['total_hours'], 2);

        $stc += $rowComm; // accumulate rounded per-row commission
        $gtc += $rowComm; // accumulate rounded per-row commission (grand)

        $name = $row['name'];
    }

    // Final subtotal row (ALWAYS visible now) — use $stc so it matches row sums
    echo "<tr><td></td><td></td><td></td><td></td><td></td><td align='right'><b>Total</b></td>";
    echo "<td align='right'><b>".number_format($ht,2)."</b></td>";
    echo "<td align='right'><b>".number_format($st,2)."</b></td>";
    echo "<td align='right'><b>".number_format($stc,2)."</b></td>";
    echo "</tr>";

    // Spacer + rule
    echo "<tr><td colspan='9'></td></tr>";
    echo "<tr><td></td><td></td><td></td><td></td><td></td><td><hr></td><td><hr></td><td><hr></td></tr>";

	// Show Grand Totals ONLY when not hiding
	if ($hideProfitVal !== "yes") {
    echo "<tr>";
    echo "<td></td><td></td><td></td><td></td><td></td>";
    echo "<td><b>Grand Total</b></td>";
    echo "<td><b>".number_format($gth,2)."</b></td>";   // total hours
    echo "<td><b>".number_format($gt,2)."</b></td>";    // total profit
    echo "<td><b>".number_format($gtc,2)."</b></td>";   // total commission (rounded per row)
    echo "</tr>";
	}


    echo "<tr><td height='20' colspan='9'></td></tr>";
    echo "</table>";

    $conn->close();
} // end POST
?>
