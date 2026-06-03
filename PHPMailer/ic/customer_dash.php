<!DOCTYPE html>
<?php

session_start();
if(!isset($_SESSION['recruiter_id'])) {
  session_regenerate_id();
  header("Location: login.php");
  return;
}

require('./db.php');
$conn = db_connect($_SESSION['recruiter_id'], $_SESSION['password']);

$id = $_SESSION['recruiter_id'];

function encode($str) {
  $alph = "";
  for($x = 0; $x < 26; $x++) $alph .= chr(65+$x);
  for($x = 0; $x < 26; $x++) $alph .= chr(97+$x);
  for($x = 0; $x < 10; $x++) $alph .= $x;
  $encodedStr = "";
  $alphOffset = substr($alph, 13).substr($alph, 0, 13);
  for($i = 0; $i < strlen($str); $i++) {
    $pos = strpos($alph, $str[$i]);
    if($pos == false) {
      $encodedStr .= $str[$i];
    } else {
      $encodedStr .= $alphOffset[$pos];
    }
  }
  return $encodedStr;
}

?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS, custom CSS -->
  <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min1.css">
  <link rel="stylesheet" href="portal_styles.css">

  <title>Rate Candidates</title>
</head>
<body>
  <form id="find_order" class="form-inline mb-2" action"<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
    <div class="form-group">
      <label for="ContactEmail_Search" class="col-sm-4 col-form-label">Contact Email</label>
      <div class="input-group col-sm-8">
        <input type="text" class="form-control" name="contactEmail" id="ContactEmail_Search">
        <div class="input-group-append">
          <input class="btn btn-outline-secondary" type="submit" value="Find">
        </div>
      </div>
    </div>
  </form>
<?php
$query = "SELECT 1
          FROM CFG_USERPROFILE
          WHERE User_ID = ?
            AND IsCommAllowed > 0";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($id));
if(odbc_num_rows($pstmt) == 0): ?>
You do not have access to view customer's dashboard
</body>
</html>

<?php
elseif(!isset($_REQUEST['contactEmail'])):?>
</body>
</html>

<?php
else:

$contactEmail = $_REQUEST['contactEmail'];

$query = "SELECT cm.Division_ID
FROM ContactMaster cm
  JOIN DivisionMaster dm ON cm.Division_ID = dm.Division_ID
  JOIN SecurityFilterUserBranches bs ON dm.Branch_ID = bs.BranchKey
WHERE cm.InternetSMTPEmail = ?";
$pstmt = odbc_prepare($conn, $query);
odbc_execute($pstmt, array($contactEmail));

if(odbc_num_rows($pstmt) == 0): ?>
Customer not found in your domain.
</body>
</html>

<?php
else:
$row = odbc_fetch_array($pstmt);
$divisionID = $row['Division_ID'];
$query = "SELECT  om.Order_ID,
                  om.Position_Title,
                  sum(CASE WHEN candidates.candidate_id IS NULL THEN 0 ELSE 1 END) total,
                  sum(CASE WHEN candidates.reviewed = 1 THEN 1 ELSE 0 END) reviewed
          FROM OrderMaster om
            LEFT JOIN ic_submitals submitals ON submitals.order_id = om.Order_ID
            LEFT JOIN ic_candidates candidates ON submitals.submital_id = candidates.submital_id
            JOIN SecurityFilterUserBranches bs ON bs.BranchKey = om.Branch_ID
          WHERE om.Division_ID = ?
          GROUP BY om.Order_ID, om.Position_Title
          ORDER BY
            CASE WHEN sum(CASE WHEN candidates.reviewed = 1 THEN 1 ELSE 0 END) < sum(CASE WHEN candidates.candidate_id IS NULL THEN 0 ELSE 1 END) then 1 else 0 END DESC,
            CASE WHEN sum(CASE WHEN candidates.reviewed = 1 THEN 1 ELSE 0 END) > 0 THEN 1 else 0 END DESC";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($divisionID));
$open_orders = [];
while($row = odbc_fetch_array($pstmt)) {
  $open_orders[$row['Order_ID']]['reviewed'] = $row['reviewed'];
  $open_orders[$row['Order_ID']]['total'] = $row['total'];
  $open_orders[$row['Order_ID']]['Position_Title'] = $row['Position_Title'];
}

$query = "SELECT Order_ID, Position_Title
          FROM OrderMaster om
            JOIN SecurityFilterUserBranches bs ON bs.BranchKey = om.Branch_ID
          WHERE Customer_ID=?
            AND [Status] = 4
            AND Taken_DateTime>=GETDATE() - 90";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($divisionID));
$filled_orders = [];
while($row = odbc_fetch_array($pstmt)) {
  $filled_orders[$row['Order_ID']]['Position_Title'] = $row['Position_Title'];
}

$query = "SELECT	wt.Unique_ID,
              em.Last_Name,
              em.First_Name,
              em.Middle_Name,
              om.Position_Title as TITLE,
              wt.WeekEnding as WKEND,
              wt.Continuing as CONT
          FROM orderassignment oa
            JOIN ordermaster om ON oa.Order_ID = om.Order_ID
            JOIN IC_WebTime wt ON oa.AssignmentNumber = wt.AssignmentNumber
            JOIN employeemaster em ON em.employee_ID = oa.Employee_ID
            JOIN SecurityFilterUserBranches bs ON bs.BranchKey = wt.Branch_ID
          WHERE oa.Division_ID = ?
            AND wt.SentDate IS NOT NULL and wt.ApproveDate IS NULL";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($divisionID));

$timesheets = [];
while($row = odbc_fetch_array($pstmt)) {
  $timesheets[$row['Unique_ID']]['name'] = $row['First_Name'].' '.(is_null($row['Middle_Name'] ? $row['Middle_Name'].' ' : '')).$row['Last_Name'];
  $timesheets[$row['Unique_ID']]['title'] = $row['TITLE'];
  $timesheets[$row['Unique_ID']]['week_ending'] = date('d F, Y',strtotime($row['WKEND']));
}

$query = "SELECT  GETDATE() as CURRENTDATE,
                  ard.DocumentKey as DOCKEY,
                  ard.DocumentDate as INVDATE,
                  ard.AmountPaid as AMOUNTPAID,
                  bp.CreditTerms as TERMS,
                  inv.Amount as AMOUNT
          FROM AR_document ard
            JOIN CustomerBillingProfile bp ON bp.BillingProfileKey = ard.BillingProfileKey
            JOIN AR_Invoice inv ON inv.DocumentKey = ard.DocumentKey
            JOIN CustomerMaster cm ON bp.CustomerKey = cm.Customer_ID
            JOIN DivisionMaster dm ON cm.Customer_ID = dm.Customer_ID
            JOIN SecurityFilterUserBranches bs ON bs.BranchKey = dm.Branch_ID
          WHERE	inv.voiddate is NULL
              AND dm.Division_ID = ?
              AND ard.AmountPaid < ard.Amount
          ORDER BY inv.DocumentDate ASC";
$pstmt = odbc_prepare($conn, $query);
odbc_execute($pstmt, array($divisionID));
$total = 0;
$invoices = [];
while($row = odbc_fetch_array($pstmt)) {
  $timestamp = strtotime($row['INVDATE']);
  $invdate = date("d F, Y", $timestamp);
  $duetimestamp = $timestamp + ($row['TERMS']*24*60*60);
  $duedate = date("d F, Y", $duetimestamp);
  $currentdate = strtotime($row['CURRENTDATE']);
  $delta = (time() - $timestamp)/60/60/24;
  $balance;
  if($delta < (10 + $row['TERMS'])) {
    $balance = $row['AMOUNT'] - $row['AMOUNTPAID'];
  } else {
    $balance = round(($row['AMOUNT'] - $row['AMOUNTPAID']) + ($delta * 18.0/365 * ($row['AMOUNT'] - $row['AMOUNTPAID'])) / 100,2);
  }
  $total += $balance;
  $displayBalance = number_format($balance,2);
  $invoices[$row['DOCKEY']]['encoded_key'] = encode("Invnum")."=".encode(substr($row['DOCKEY'],-5));
  $invoices[$row['DOCKEY']]['duedate'] = $duedate;
  $invoices[$row['DOCKEY']]['displaybalance'] = $displayBalance;
}
?>
<div class="container">
  <div class="row mb-1">
    <div class="col-lg-4">
      <h4>Open Orders
      </h4>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($open_orders as $key => $val) { ?>
            <tr class="order" data-order="<?php echo $key ?>" data-reviewed="<?php echo $val['reviewed'] ?>" data-total="<?php echo $val['total'] ?>">
              <td><?php echo $val['Position_Title'] ?></td>
              <td class="status"></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row mb-1">
    <div class="col-lg-4">
      <h4>Filled Orders
      </h4>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($filled_orders as $key => $val) { ?>
            <tr>
              <td><?php echo $val['Position_Title'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row mb-1">
    <div class="col-lg-4">
      <h4>Pending Timesheets
      </h4>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table">
        <thead>
          <tr>
            <th>Talent</th>
            <th>Title</th>
            <th>Week Ending</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($timesheets as $key => $val) { ?>
            <tr>
              <td><?php echo $val['name'] ?></td>
              <td><?php echo $val['title'] ?></td>
              <td><?php echo $val['week_ending'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row mb-1">
    <div class="col-lg-4">
      <h4>Unpayed Invoices
      </h4>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table" id="invoice_table">
        <thead>
          <tr>
            <th>Due Date</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($invoices as $key => $val) { ?>
            <tr class="invoice" data-encodedinvoice="<?php echo $val['encoded_key'] ?>" data-duedate="<?php echo $val['duedate'] ?>">
              <td><?php echo $val['duedate'] ?></td>
              <td class="balance"><?php echo $val['displaybalance'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- jQuery
    Bootstrap JS
    PDFJS-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="../bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

<script>
$('.order').each(function() {
  if($(this).data('reviewed') < $(this).data('total')) {
    this.classList.add('table-danger');
    $(this).children('.status').html($(this).data('reviewed')+' of '+$(this).data('total')+' Applicants reviewed');
  } else {
    $(this).children('.status').html('Fully reviewed');
  }
});

$('.invoice').each(function() {
  var duedate = Date.parse($(this).data('duedate'));
  var today = new Date();
  if(duedate < today) {
    this.classList.add('table-danger');
  } else if(duedate === today) {
    this.classList.add('table-warning');
  }
});

</script>
</body>
</html>
<?php
endif;
endif; ?>
