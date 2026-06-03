<?php
require_once dirname(__DIR__) . '/../db/db.php';
$link = db();

/*
|--------------------------------------------------------------------------
| Default dates
|--------------------------------------------------------------------------
*/
$defaultStartDate = date('Y-m-d', strtotime('-2 years'));
$defaultEndDate   = date('Y-m-d');

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : $defaultStartDate;
$endDate   = isset($_GET['end_date'])   ? $_GET['end_date']   : $defaultEndDate;

/*
|--------------------------------------------------------------------------
| Basic date validation
|--------------------------------------------------------------------------
*/
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
    $startDate = $defaultStartDate;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
    $endDate = $defaultEndDate;
}

/*
|--------------------------------------------------------------------------
| Make sure start date is not after end date
|--------------------------------------------------------------------------
*/
if ($startDate > $endDate) {
    $temp = $startDate;
    $startDate = $endDate;
    $endDate = $temp;
}

/*
|--------------------------------------------------------------------------
| Show form first
|--------------------------------------------------------------------------
*/
if (!isset($_GET['download'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Download Employees CSV</title>
</head>
<body>
<H1><center>List of Employees who have been on an assignment or full-time Placement<br>within a specified period</center></H1>

    <form method="GET">
        <div style="margin-bottom:12px;">
            <label for="start_date">Latest timesheet WeekEnding between:</label><br>
            <input
                type="date"
                id="start_date"
                name="start_date"
                value="<?php echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?>"
            >
        </div>

        <div style="margin-bottom:12px;">
            <label for="end_date">and:</label><br>
            <input
                type="date"
                id="end_date"
                name="end_date"
                value="<?php echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?>"
            >
        </div>

        <input type="hidden" name="download" value="1">
        <button type="submit">Download CSV</button>
    </form>
</body>
</html>
<?php
    exit;
}

/*
|--------------------------------------------------------------------------
| Filename
|--------------------------------------------------------------------------
*/
$filename = 'employees_' . $startDate . '_to_' . $endDate . '.csv';

/*
|--------------------------------------------------------------------------
| Query
|--------------------------------------------------------------------------
| Gets each employee once, based on their latest WeekEnding,
| and only if that latest WeekEnding falls within the selected range.
*/
$query = "
    SELECT
        TRIM(t1.first_name) AS first_name,
        TRIM(t1.last_name) AS last_name,
        TRIM(t1.Email) AS Email,
        TRIM(t1.Employee_ID) AS Employee_ID
    FROM ic_timesheets AS t1
    INNER JOIN (
        SELECT
            Email,
            MAX(WeekEnding) AS LatestWeekEnding
        FROM ic_timesheets
        WHERE Email IS NOT NULL
          AND Email != ''
        GROUP BY Email
    ) AS latest
        ON t1.Email = latest.Email
       AND t1.WeekEnding = latest.LatestWeekEnding
    WHERE latest.LatestWeekEnding BETWEEN ? AND ?
      AND t1.Email IS NOT NULL
      AND t1.Email != ''
    ORDER BY t1.Email
";

try {
    $stmt = $link->prepare($query);

    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $link->error);
    }

    $stmt->bind_param('ss', $startDate, $endDate);

    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $results = $stmt->get_result();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    if ($output === false) {
        throw new Exception('Unable to open output stream.');
    }

    fputcsv($output, ['first_name', 'last_name', 'Email', 'Employee_ID']);

    while ($row = $results->fetch_assoc()) {
        fputcsv($output, [
            $row['first_name'],
            $row['last_name'],
            $row['Email'],
            $row['Employee_ID']
        ]);
    }

    fclose($output);
    $stmt->close();
    $link->close();
    exit;

} catch (Exception $e) {
    if (!headers_sent()) {
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo 'An error occurred: ' . $e->getMessage();
    exit;
}
?>