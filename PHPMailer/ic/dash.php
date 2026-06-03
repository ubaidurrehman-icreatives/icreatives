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

?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS, custom CSS -->
  <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min1.css">
  <link rel="stylesheet" href="dash_styles.css">

  <title>Rate Candidates</title>
</head>
<body>

<div class="container">
  <div class="row mh-33">
    <div class="col-lg-4">
      <h4>Reviewed Orders:
      </h4>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Title</th>
            <th>Order</th>
            <th>Submitted</th>
            <th>Reviewed</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT om.Order_ID, om.Position_Title, COUNT(candidates.employee_id) AS total, SUM(CASE WHEN candidates.reviewed = 1 THEN 1 ELSE 0 END) AS reviewed
                    FROM ic_submitals submitals
                      JOIN ic_candidates candidates ON submitals.submital_id = candidates.submital_id
					            JOIN OrderMaster om ON submitals.order_id = om.Order_ID
                    WHERE submitals.recruiter_id = ?
                    GROUP BY om.Order_ID, om.Position_Title";
          $pstmt = odbc_prepare($conn,$query);
          odbc_execute($pstmt, array($_SESSION['recruiter_id']));
          odbc_execute($pstmt, array($_SESSION['recruiter_id']));
          while($row = odbc_fetch_array($pstmt)) {
            echo '
            <tr class="order" data-order="'.$row['Order_ID'].'" data-total="'.$row['total'].'">
              <td>'.$row['Position_Title'].'</td>
              <td>'.$row['Order_ID'].'</td>
              <td>'.$row['total'].'</td>
              <td>'.$row['reviewed'].'</td>
            </tr>';
          }

          ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <button type="button" class="btn btn-primary" onclick="window.location.href='./approve.php'">Approve Candidates</button>
  </div>
</div>

<!-- jQuery
    Bootstrap JS
    PDFJS-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="../bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

<script>
$('.order').click(function() {
  document.location.assign("./review_order.php?orderID="+$(this).data('order'));
});
</script>
</body>
</html>
