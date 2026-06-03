<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION['recruiter_id']))      // if there is no valid session
{
  session_regenerate_id();
  header("Location: login.php");
  return;
}

require('./db.php');
$conn = db_connect($_SESSION['recruiter_id'], $_SESSION['password']);
?>

<html>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min1.css">
  <link rel="stylesheet" href="style.css">

  <!-- Optional JavaScript -->
  <!-- jQuery
        Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="../bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

</head>
<body>
  <form id="find_order" class="form-inline mb-2" action"<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
    <div class="form-group">
      <label for="OrderID_Search" class="col-sm-4 col-form-label">Job Order #</label>
      <div class="input-group col-sm-8">
        <input type="text" class="form-control" name="orderID" id="OrderID_Search">
        <div class="input-group-append">
          <input class="btn btn-outline-secondary" type="submit" value="Find">
        </div>
      </div>
    </div>
  </form>

<?php if(!isset($_REQUEST['orderID'])):?>
</body>
</html>

<?php else:
$orderID = $_REQUEST['orderID'];
// Get contact info for order
$query = "SELECT om.Customer_ID, om.Division_ID, cm.First_Name, cm.Last_Name, cm.InternetSMTPEmail
          FROM OrderMaster om
            JOIN ContactMaster cm ON om.TakenContactKey = cm.Contact_ID
          WHERE Order_ID = ?";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($orderID));
$row = odbc_fetch_array($pstmt);
$customerID = $row['Customer_ID'];
$divisionID = $row['Division_ID'];
$contactName = $row['First_Name']." ".$row['Last_Name'];
$contactEmail = $row['InternetSMTPEmail'];

if(odbc_num_rows($pstmt) == 0) {
  echo $orderID." not found";
  return;
}

// Get recruiter's name
$query = "SELECT Last_Name, First_Name
          FROM CFG_USERPROFILE
          WHERE User_ID=?";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($_SESSION['recruiter_id']));
$row = odbc_fetch_array($pstmt);
$recruiterName = ucfirst(strtolower($row['First_Name']))." ".ucfirst(strtolower($row['Last_Name']));

$query = "SELECT  em.Employee_ID,
                  em.First_Name,
                  em.Last_Name,
                  em.InternetHTTPWeb2,
                  om.Position_Title,
                  ei.Monitor_Comment
          FROM EmployeeMaster em
          JOIN UMResponse umr ON umr.Employee_ID = em.Employee_ID
            AND umr.[State] = 'Accepted'
          JOIN UltraMATCHSearches ums ON ums.UltraMATCHKey = umr.UltraMATCHKey
          LEFT JOIN EmployeeInterviewSummary ei ON ei.Employee_ID = em.Employee_ID
          LEFT JOIN ic_candidates ON em.Employee_ID = ic_candidates.employee_id
          JOIN OrderMaster om ON ums.OrderKey = om.Order_ID
          JOIN OrderAssignment oa ON ums.OrderKey = oa.Order_ID
            AND umr.Employee_ID = oa.Employee_ID
            AND oa.IsBookedSoft = 1
          JOIN SecurityFilterUserBranches bs on bs.BranchKey = om.Branch_ID
          WHERE om.Order_ID = ? AND ic_candidates.employee_id IS NULL";

$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt,array($orderID));

$candidateInfo = [];
while($row = odbc_fetch_array($pstmt)) {
  $candidateInfo[$row["Employee_ID"]] = ["name" => $row["First_Name"].' '.$row["Last_Name"], "comments" => $row['Monitor_Comment']];
  $link = strtolower($row['InternetHTTPWeb2']);

  // format url to include http://
  if(strcmp(substr($link,0,7),"http://") || strcmp(substr($link,0,7),"https://")) {
    $link = "http://".$link;
  }
  // if not valid url
  if(!filter_var($link,FILTER_VALIDATE_URL)) {
    $link = "";
  }
  $candidateInfo[$row['Employee_ID']]['link'] = $link;
}
?>

<form class="center_div" id="post_candidate" method="post" action="post_candidates.php" enctype='multipart/form-data'>
<!-- <form class="center_div" id="post_candidate"> -->
  <input type="hidden" name="orderID" value="<?php echo $orderID ?>">
  <input type="hidden" name="customerID" value="<?php echo $customerID ?>">
  <input type="hidden" name="divisionID" value="<?php echo $divisionID ?>">

  <div class="form-group">
    <label for="msg">Message to customer</label>
    <textarea id="msg" name="msg" class="form-control" rows="5" required>
Dear <?php echo $contactName ?>,

Lorem ipsum dolor,
<?php echo $recruiterName ?>
</textarea>
  </div>
  <div class="form-group">
    <label for="emailaddress">Email Address: </label>
    <input type="email" class="form-control" name="emailaddress" id="emailaddress" value="<?php echo $contactEmail ?>">
  </div>
  <div class="form-group">
    <label for="xtremail">Extra Email (CC):</label>
    <input type="email" class="form-control" name="xtremail">
  </div>
  <div class="container" id="Candidates">
    <?php if(sizeof($candidateInfo) == 0): ?>
      No candidates found
    <?php else:
      foreach($candidateInfo as $key=>$val) { ?>
      <div class="row mb-3" name="candidate" class="candidate" id="<?php echo $key ?>">
        <input type="hidden" name="candidateID[]" value = "<?php echo $key ?>">
        <div class="col-sm">
          <h5 id="name<?php echo $key ?>"><?php echo $val["name"] ?></h5>
          <button type="button" class="btn btn-link link" value="<?php $val['link'] ?>" id="link"
      <?php if($val['link'] == "") echo 'disabled' ?>
          >Post Link</button><br>
          <div class="form-check">
            <input type="checkbox" class="form-check-input include" name="include[]" id="include<?php echo $key?>" value="yes">
            <label for="include<?php echo $key ?>" class="form-check-label">Include</label>
          </div>
          <input type="hidden" name="include[]" value="no">
        </div>
        <div class="col-sm">
          <div class="form-group">
            <label for="msg">Candidate Comments</label>
            <textarea id="comments<?php echo $key ?>" name="comments[]" class="form-control"><?php echo $val['comments'] ?></textarea>
          </div>
        </div>
        <div class="col-sm">
          <div class="file-group input-group mb-3">
            <div class="custom-file">
              <input class="resume custom-file-input col-sm-10" type="file" name="resume[]" id="resume-<?php echo $key ?>" data-candidate="<?php echo $key ?>" accept=".pdf">
              <input type="hidden" name="isPrimaryResume[]" id="isPrimaryResume-<?php echo $key ?>">
              <label class="custom-file-label" for="resume-<?php echo $key ?>">Choose resume</label>
            </div>
          </div>
          <input type="hidden" class="selected_order" id="selected_order<?php echo $key ?>">
          <div>
            <label for="weight-<?php echo $key ?>">Weight (1-10): </label>
            <input type="number" id="weight-<?php echo $key ?>" name="weight[]" value="1" min="1" max="10" required>
          </div>
        </div>
      </div>
    <?php }
    endif; ?>
  </div>
  <div>
    <button type="submit" class="btn btn-primary" id="submit" <?php if(sizeof($candidateInfo) == 0) echo "disabled" ?> >Submit</button>
    <button type="button" class="btn btn-secondary">Test</button>
  </div>
</form>
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="container modal-content">
    <div class="row">
      <div class="col">
        <span class="float-left"><h1>Resume Upload Type</h1></span>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <button type="button" class="btn btn-primary closeDialogue" id="isPrimaryResumeBtn">Primary resume</button>
        <button type="button" class="btn btn-primary closeDialogue" id="isCustomerSpecificBtn">Customer-specific</button>
        <button type="button" class="btn btn-secondary closeDialogue" id="cancelUploadBtn">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>

// Candidate link behavior
$('#Candidates').on('click','.link', function() {
  var url = this.value;
  window.open(url, '_blank');
});

// Submission validation
$('#post_candidate').submit(function(e) {
  $('input:file').each(function(i) {
    if(this.value && !this.value.match(/\.pdf$/)) {
      this.value="";
      alert('Invalid file type for candidate '+(i+1)+'. Please submit a pdf');
      e.preventDefault();
    }
  });
  if($('.include:checkbox:checked').length == 0) {
    alert('No candidates selected');
    e.preventDefault();
  }
  if($('#emailaddress').val() == "") {
    confirm('No email entered. Candidadates will be approved without alerting customer. Are you sure you want to continue?')
  }
});

// Resume behavior and dialogue box
var modal = document.getElementById("myModal");
var resumeUpload;
$("#Candidates").on('change','.resume', function() {
  if(!this.value.match(/\.pdf$/)) {
    $(this).parent().find('.rmv_file').prop('disabled', true);
    alert('Invalid file type. Please submit a PDF');
    this.value = "";
  } else {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").html(fileName);
    resumeUpload = this;
    modal.style.display="block";
  }
});
$('#isPrimaryResumeBtn').click(function(){
  $(resumeUpload).siblings(".hidden").val("1");
});
$('#isCustomerSpecificBtn').click(function() {
  $(resumeUpload).siblings(".hidden").val("0");
});
$('#cancelUploadBtn').click(function() {
  resumeUpload.value = "";
  $(resumeUpload).siblings(".custom-file-label").removeClass("selected").html("Choose resume");
});
$('.closeDialogue').click(function() {
  modal.style.display="none";
});

/*
  Prevent submition on 'enter'
  prevent 'enter' on all inputs except textarea
*/
$('#post_candidate').on('keyup keypress', ":input:not(textarea):not(:submit)", function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});
</script>
</body>
</html>
<?php endif; ?>
