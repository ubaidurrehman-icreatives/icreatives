<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION['recruiter_id'])) {
  session_regenerate_id();
  header("Location: login.php");
  return;
}
if(!isset($_SESSION['candidates_review'])) {
  if(isset($_REQUEST['orderID'])) {
    header("Location: order_candidates.php?orderID=".$_REQUEST['orderID']);
  } else {
    header("Location: dash.php");
  }
}

require('./db.php');
$conn = db_connect($_SESSION['recruiter_id'], $_SESSION['password']);

$id = $_SESSION['recruiter_id'];
$order = $_REQUEST['orderID'];


$candidate = $_SESSION['candidates_review'][$_REQUEST['candidate']]['employee_id'];
$order = $_REQUEST['orderID'];
$submital = $_SESSION['candidates_review'][$_REQUEST['candidate']]['submital'];?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min1.css">
  <link rel="stylesheet" href="candidate_style.css">

  <!-- jQuery
      Bootstrap JS
      PDFJS-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="../bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

  <title>Review Candidate</title>
</head>
<body>

<form action="">
  <div class="container" id="candidates">
    <button type="button" class="btn btn-primary" id="back">back</button>

  <?php
  // select candidate with employee_id in the submital
  $query = "SELECT  candidates.weight,
                    candidates.rating,
                    candidates.ic_comments,
                    candidates.customer_comments,
                    candidates.interview_time,
                    candidates.schedule_interview,
                    em.First_Name,
                    em.Last_Name,
                    comment.Monitor_Comment
            FROM ic_candidates candidates
            LEFT JOIN EmployeeMaster em ON candidates.employee_id = em.Employee_ID
            LEFT JOIN EmployeeInterviewSummary comment ON em.Employee_ID = comment.Employee_ID
            WHERE candidates.employee_id = ? AND submital_id = ?";
  $pstmt = odbc_prepare($conn, $query);
  odbc_execute($pstmt, array($candidate, $submital));

  if(odbc_num_rows($pstmt) == 0) {
    echo "No Applicants";
    return;
  }

  $row = odbc_fetch_array($pstmt);
  $rating = $row['rating'];
  $time = is_null($row['interview_time']) ? "" : date('Y-m-d\TH:i', strtotime($row['interview_time'])); // clears out datetime input on null
  ?>
  <h1><?php echo $row['First_Name']." ".$row['Last_Name'] ?></h1>
  <div class="row justify-content-center">
    <div class="col-md-7">
      <a href="../resumes/<?php echo $candidate ?>.pdf" target="_blank">
        <canvas id="resume" class="resume"></canvas>
      </a>
    </div>
    <div class="col-md-5">
      <fieldset disabled>
        <div class="star_group">
          <?php
          for($i = 0; $i < 5; $i++) {
            if($i < $row['rating']) {
              echo '<span class="star_label selected">★</span>';
            } else {
              echo '<span class="star_label">★</span>';
            }
          }
          ?>
        </div>
        <div class="form-group">
          <label for="interview_date">Interview Date</label>
          <input type="datetime-local" class="form-control" name="interview_date" id="interview_date" value="<?php echo $time; ?>">
        </div>
        <div class="form-group">
          <p class="comment"><?php echo $row['ic_comments'] ?></p><br>
        </div>
        <div class="form-group">
          <label for="msg">Candidate Comments</label>
          <textarea id="comments" name="comments" class="form-control"><?php echo $row['customer_comments'] ?></textarea>
        </div>
        <div class="form-group">
          <div class="columns">
            <p class="skills">
              <?php
              $query = "SELECT SK.Code as Code,
                                ES.Years_Experience as Years,
                                SK.Description as Description,
                                ES.Skill_ID as SKILL_ID
                        FROM EmployeeSkill ES
                          JOIN IO_SKILL SK on ES.SKILL_ID = SK.Code
                        WHERE ES.employee_ID = ?
                          AND SUBSTRING(description,1,4)<>'ZONE'
                          AND description <>'Interviewed'
                          AND description <>'Personal'
                        ORDER BY es.Skill_ID";
              $pstmt = odbc_prepare($conn,$query);
              odbc_execute($pstmt, array($candidate));
              $head = "";

              // get first result
              $first = true;
              while($row = odbc_fetch_array($pstmt)) {
                if(strpos("ABCDFJLMIRT",substr($row['Code'],0,1)) != false){
                  $description = explode('-',$row['Description'],2);
                  $category = trim($description[0]);
                  if(strcmp($category,$head)!=0) {
                    $head = $category;
                    if(!$first) { echo "<br>"; } else { $first = false; }
                    echo '<span class="skills-cat">'.$category.'<br></span>';
                  }
                  $skill = sizeof($description)==1 ? $category : $description[1];
                  echo $skill.'<br>';
                }
              }
              ?>
            </p>
          </div>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input must_meet" name="must_meet" id="must_meet" value="love_them" <?php if($row['schedule_interview']) echo "checked" ?>>
          <label for="must_meet" class="form-check-label">Love them! Set me up with an interview</label>
        </div>
      </fieldset>
    </div>
  </div>
  <div class="pagination">
    <button type="button" class="btn btn-link" id="prev" <?php if($_REQUEST['candidate'] == 0) echo 'disabled'; ?>>Prev</button>
    <button type="button" class="btn btn-link" id="next" <?php if($_REQUEST['candidate'] == sizeof($_SESSION['candidates_review']) - 1) echo 'disabled'; ?>>Next</button>
  </div>
</div>
</form>

<script>
// render pdf
$('.resume').each(function() {
  var canvas = this;
  var pdf = '../resumes/<?php echo $candidate ?>.pdf';
  var pdfjsLib = window['pdfjs-dist/build/pdf'];
  pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';
  var loadingTask = pdfjsLib.getDocument(pdf);
  loadingTask.promise.then(function(pdf) {
    console.log('PDF loaded');

    // Fetch the first page
    var pageNumber = 1;
    pdf.getPage(pageNumber).then(function(page) {
      console.log('Page loaded');

      var renderScale = 2;  // resolution goes up as scale goes up
      var viewport = page.getViewport({scale:renderScale});
      var context = canvas.getContext('2d');
      canvas.width = viewport.width;
      canvas.height = viewport.height;
      canvas.style.width = '100%';
      canvas.style.height = viewport.height*(canvas.style.width/canvas.width);

      // Render PDF page into canvas context
      var renderContext = {
        canvasContext: context,
        viewport: viewport
      };
      var renderTask = page.render(renderContext);
      renderTask.promise.then(function () {
        console.log('Page rendered');
      });
    });
  }, function (reason) {
    // PDF loading error
    console.error(reason);
  });
});

$('#back').click(function() {
  document.location.assign("./review_order.php?orderID=<?php echo $order; ?>");
});

$('#next').click(function() {
  document.location.assign("./candidate_result.php?candidate="+<?php echo $_REQUEST['candidate']+1 ?>+"&orderID=<?php echo $_REQUEST['orderID'] ?>");
});

$('#prev').click(function() {
  document.location.assign("./candidate_result.php?candidate="+<?php echo $_REQUEST['candidate']-1 ?>+"&orderID=<?php echo $_REQUEST['orderID'] ?>");
});
</script>
</body>
</html>
