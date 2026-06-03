<!DOCTYPE html>
<html>
<head>
	<style>
	body{ 
	font-family: Arial, Helvetica, sans-serif; 
	font-size: 12px;
	} 
	</style>
    <title>ICREATIVES PO <?php echo $_REQUEST['po']?> REPORT</title>
    <script>
        function openPopup(url) {
            var width = 800;
            var height = 850;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            var popup = window.open(url, 'PopupWindow', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
            popup.focus();
        }
    </script>
</head>
<body>
    <?php // include 'manatal_header.php'; ?>
    <?php
	 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    echo "<h1>ICREATIVES PO ".$_REQUEST['po']." REPORT</h1>";

    // Connect to the MySQL database
   // use Dompdf\Dompdf;
require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();   
?>
    <form method="post">
        PO Number: <input type="text" id="po_number" name="po_number" value="<?php echo htmlspecialchars($_REQUEST['po']); ?>">   
        Week Starting: <input type="date" id="week_starting" name="week_starting" value="<?php echo isset($_POST['week_starting']) ? htmlspecialchars($_POST['week_starting']) : ''; ?>">  
        Week Ending: <input type="date" id="week_ending" name="week_ending" value="<?php echo isset($_POST['week_ending']) ? htmlspecialchars($_POST['week_ending']) : ''; ?>">  
        <input type="submit" value="Create Report">
    </form>

    <?php 
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST')  {

        // Calculate spent amount
        $query = "SELECT SUM(ROUND(ts.hours * ts.billrate,2)) AS spent FROM ic_timesheets ts  
                  JOIN ic_matches m ON ts.AssignmentNumber = m.job AND ts.Employee_ID = m.candidate 
                  WHERE TRIM(m.po_number) = '". trim($_POST['po_number']). "' AND NOT VOID ";

        if (!empty($_POST['week_ending'])) {
            $query .= " AND ts.WeekEnding <= '". $_POST['week_ending'] . "' ";
        }
        if (!empty($_POST['week_starting'])) {
            $query .= " AND ts.WeekEnding >= '". $_POST['week_starting'] . "' ";
        }
        $sum = mysqli_query($link, $query);
        $rowS = mysqli_fetch_array($sum);
        $spent = $rowS['spent'];

        // Query the database for the list of recipients
        $strSQL = "SELECT * FROM ic_timesheets ts
                   JOIN ic_matches m ON ts.AssignmentNumber = m.job AND ts.Employee_ID = m.candidate 
                   WHERE TRIM(m.po_number) = '". trim($_POST['po_number']). "' AND NOT VOID ";
      
        if (!empty($_POST['week_ending'])) {
            $strSQL .= " AND ts.WeekEnding <= '". $_POST['week_ending'] . "' ";
        }
        if (!empty($_POST['week_starting'])) {
            $strSQL .= " AND ts.WeekEnding >= '". $_POST['week_starting'] . "' ";
        }

        $strSQL .= " ORDER BY m.candidate_name, ts.Employee_ID ASC, ts.Weekending ASC";
        
        $result = mysqli_query($link, $strSQL);
        $row = mysqli_fetch_array($result);

        echo '
        <table border="1" style="border-collapse:collapse;">
            <tr>
                <td colspan="10" align="center"><h2><b>&nbsp;Company Name:&nbsp;' . $row["company_name"] . '</b></h2></td>
            </tr>
            <tr>
                <td></td>
                <td width="200"></td>
                <td width="400" align="center"></td>
                <td width="100" align="center"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">' . $row["po_note"] . '</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>PO Amount:&nbsp;</td>
                <td align="right">$' . number_format($row["po_amount"], 2) . '</td>
            </tr>
            <tr>
                <td colspan="2"><b>&nbsp;PO Number:&nbsp;</b>' . $row["po_number"] . '</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right"><b>Spent:</b>&nbsp;</td>
                <td align="right"><b>$' . number_format($spent, 2) . '</b></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right">Remaining:&nbsp;</td>
                <td align="right">$' . (number_format($row["po_amount"] - $spent, 2)) . '</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td align="center">Invoice No.</td>
                <td align="center">Talent Name</td>
                <td align="center">Job Title</td>
                <td align="center">Week Ending</td>
                <td align="center">Hours</td>
                <td align="center">Rate</td>
                <td align="center">Amount</td>
                <td align="center">Amt Paid</td>
                <td align="center">Inv Date</td>
            </tr>';

        $gt_amount = '0.00';
        $c_amount = '0.00';
        $candidate_test = '';

        mysqli_data_seek($result, 0); // Reset result pointer

        while ($row = mysqli_fetch_array($result)) {
            $invoice_number = $row['invoice_number'];
            $candidate_name = $row['candidate_name'];
            $job_name = $row['title'];
            $candidate = $row['candidate'];
            $WeekEnding = $row['WeekEnding'];
            $Hours = $row['Hours'];
            $billrate = $row['billrate'];
            $amount = number_format(($Hours * $billrate), 2, '.', '');
            $paid_amount = $row['paid_amount'];
            $gt_amount = round($gt_amount + $amount, 2);

            $inv_date = date("m/d/Y", strtotime($WeekEnding));

            // Check if the candidate has changed
            if ($candidate_test != '' && $candidate_test != $candidate) {
                // Print the cumulative amount for the previous candidate
                echo "<td align='right'>&nbsp;" . number_format($c_amount, 2) . "&nbsp;</td></tr>";
                $c_amount = '0.00';
            }

            // Start a new row
            echo "<tr>
                <td>&nbsp;$invoice_number&nbsp;</td>
                <td>&nbsp;$candidate_name&nbsp;</td>
                <td>&nbsp;$job_name&nbsp;</td>
                <td width='100' align='center'>&nbsp$inv_date&nbsp</td>
                <td align='right'>&nbsp;$Hours&nbsp</td>
                <td  align='right'>&nbsp$billrate&nbsp</td>
                <td  align='right'>&nbsp" . number_format($amount, 2) . "&nbsp</td>
                <td  align='right'>&nbsp" . number_format($paid_amount, 2) . "&nbsp</td>
                <td  align='right'>&nbsp$inv_date&nbsp</td>";

				$c_amount = round((float)$c_amount + (float)$amount, 2);


            // Update the candidate_test variable
            $candidate_test = $candidate;
        }

        // Print the cumulative amount for the last candidate
        if ($candidate_test != '') {
            echo "<td align='right'>" . number_format($c_amount, 2) . "</td></tr>";
        }

        echo "<tr>
            <td colspan='9' align='right'><b>Total Amount Invoiced&nbsp;&nbsp; </b> </td>
            <td align='right'><b>$" . number_format($gt_amount, 2) . "</b></td>
            <td></td> 
        </tr>";
        echo "</table>";
    }
    ?>
</body>
</html>
