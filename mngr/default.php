<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// echo $_SERVER['DOCUMENT_ROOT']."<br>"; 

$path = realpath(dirname(__FILE__));


// require_once($_SERVER['DOCUMENT_ROOT']. '/wp-content/custom-php/functions.php');        
     
  if(!isset($_REQUEST['p'])){
	$page="blank";
  }else{
	$page=$_REQUEST['p'];
  }

if($page !== "blank") {
	echo '<div style="text-align:center; padding:0px 0px 10px 0px;"> ';
   	include($path . '/main_nav.tpl.php');
	echo '</div>';
}

switch ($page) {
    case "remindclients":
	echo '<div style="text-align:center;"> ';
	echo "<iframe name='remindclients' src='/mngr/webtime/TimeCheck2.asp?varib1=" . $_SESSION["User_ID"] . "&varib2=" . $_SESSION["Upass"] . "' width=95% height=2000 style='border:2px solid #b22625;'></iframe>";
	echo '</div>';
        break;
    case "remindtalent":
	echo '<div style="text-align:center;"> ';
	echo "<iframe name='remindtalent' src='/mngr/webtime/RemindTalent1b.asp?varib1=" . $_SESSION["User_ID"] . "&varib2=" . $_SESSION["Upass"] . "' width=95% height=2000 style='border:2px solid #b22625;'></iframe>";
	echo '</div>';

        break;
    case "import":
	echo '<div style="text-align:center;"> ';
	echo "<iframe name='import' src='/mngr/webtime/IIF_IMPORT.asp?varib1=" . $_SESSION["User_ID"] . "&varib2=" . $_SESSION["Upass"] . "'  width=95% height=2000 style='border:2px solid #b22625;'></iframe>";
	echo '</div>';

        break;
    case "checkstubs":
	echo '<div style="text-align:center;"> ';
	echo "<iframe name='import' src='/mngr/email_checks.asp" . "'  width=95% height=2000 style='border:2px solid #b22625;'></iframe>";
	echo '</div>';

        break;
    case "invoices":
	echo '<div style="text-align:center;"> ';
	echo "<iframe name='import' src='/mngr/email_invoices.asp?varib1=" . $_SESSION["User_ID"] . "&varib2=" . $_SESSION["Upass"] . "'  width=95% height=2000 style='border:2px solid #b22625;'></iframe>";
	echo '</div>';

        break;

    case "statements":
	echo '<div style="text-align:center;"> ';
	echo "<iframe name='statements' src='/mngr/statement1.asp?varib1=" . $_SESSION["User_ID"] . "&varib2=" . $_SESSION["Upass"] . "'  width=95% height=2000 style='border:2px solid #b22625;'></iframe>";
	echo '</div>';

        break;

	case "logout":
		unset($_SESSION['User_ID']);
		unset($_SESSION['Upass']);
		session_destroy();
	  	header( "Location: /mngr/login/Admin-sign-in" );
        break;
	case "blank":
        include($path . '/admin-login2.tpl.php');
        break;
} 

  
?>

           
            



