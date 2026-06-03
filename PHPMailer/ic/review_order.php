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
$order = $_REQUEST['orderID'];

?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS, custom CSS -->
  <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min1.css">
  <link rel="stylesheet" href="order_review_style.css">

  <title>Rate Candidates</title>
</head>
<body>
  <main role="main" class="container">
    <div class="row">
      <button type="button" class="btn btn-primary" id="back">back to dashboard</button>
    </div>
    <div class="row">
      <div class="col">
        <h1>
          <?php
          $query = "SELECT Position_Title FROM OrderMaster WHERE Order_ID = ?";
          $pstmt = odbc_prepare($conn,$query);
          odbc_execute($pstmt, array($order));
          $row = odbc_fetch_array($pstmt);
          echo $row['Position_Title'];
          ?>
        </h1>
        <table class="table table-hover">
          <thead>
            <tr>
              <th></th>
              <th>Date Updated</th>
              <th>Rating</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $_SESSION['candidates_review'] = [];

            $query = "SELECT	candidates.employee_id,
                              candidates.rating,
                              candidates.date_updated,
                              em.First_Name,
                              em.Last_Name,
                              submitals.submital_id
                      FROM ic_submitals submitals
                        JOIN OrderMaster om ON submitals.order_id = om.Order_ID
                        JOIN ic_candidates candidates ON submitals.submital_id = candidates.submital_id
                      JOIN EmployeeMaster em ON candidates.employee_id = em.Employee_ID
                      WHERE om.Order_ID = ?
                        AND submitals.recruiter_id = ?
                        AND candidates.reviewed = 1
                      ORDER BY candidates.date_updated DESC, candidates.reviewed, submitals.submital_id DESC, candidates.weight DESC";

            $pstmt = odbc_prepare($conn,$query);
            odbc_execute($pstmt, array($order, $id));
            while($row = odbc_fetch_array($pstmt)) {
              array_push($_SESSION['candidates_review'], array('employee_id' => $row['employee_id'], 'submital' => $row['submital_id']));
              $date = "";
              if(isset($row['date_updated'])) {
                $timestamp = strtotime($row['date_updated']);
                $date = date('d F, Y', $timestamp);
              } else {
                $date = "-";
              }
              echo '
              <tr class="candidate" data-candidate="'.$row['employee_id'].'" data-submital="'.$row['submital_id'].'">
              <th>'.$row['First_Name'].' '.$row['Last_Name'].'</td>
              <td>'.$date.'</td>
              <td class="rating">
              <div class="star_group">';
              for($i = 0; $i < 5; $i++) {
                if($i < $row['rating']) {
                  echo '<span class="star_label selected">★</span>';
                } else {
                  echo '<span class="star_label">★</span>';
                }
              }
              echo '
              </div>
              </td>
              </tr>';
            }

            ?>


          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- jQuery
  Bootstrap JS
  PDFJS-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="../bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

  <script>
  $('.candidate').each(function() {
  });

  $('.candidate').click(function() {
    document.location.assign("./candidate_result.php?candidate="+(this.rowIndex - 1)+"&orderID=<?php echo $order ?>");
  });

  $('#back').click(function() {
    document.location.assign("./dash.php");
  });
</script>
</body>
</html>
