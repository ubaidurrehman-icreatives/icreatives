
<html>
<head>
<style>
body {
  margin: 0;
  display: flex;
  justify-content: center;
  background-color: #f7f7f7;
}

.main-container {
  width: 100%;
  max-width: 800px; /* limit width */
  background: #fff;
  padding: 30px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>
</head>
<body>
<div class="main-container">
<?php
require_once __DIR__ . '/../db/db.php';
$link = db();   

$CID = $_GET['CID'];

$sql = "SELECT html from ic_candidate_self_eval where id = '$CID'";
	$SQLr = mysqli_query($link,$sql );
	$row = mysqli_fetch_array($SQLr);
	$html = $row['html'];
	
	echo $html;
	
	?>
</div>
</body>
</html>