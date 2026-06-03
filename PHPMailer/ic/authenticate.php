<?php
// TODO: make secure session
session_start();

if(isset($_POST['user']) && isset($_POST['password'])) {
  require("./db.php");
  $conn = db_authenticate($_POST['user'], $_POST['password']);

  if($conn == "ok") {
    session_regenerate_id();
    $_SESSION['recruiter_id'] = $_POST['user'];
    $_SESSION['password'] = $_POST['password'];
    echo "success";
  } else {
    echo "username or password incorrect";
  }
} else {
  echo "username or password not entered";
}

 ?>
