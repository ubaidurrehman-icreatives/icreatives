<HTML>
<BODY>

<?php


// Echo "Accept: ".$Accept."<BR>";

		// $link = mysqli_connect('localhost', 'wp_vtnzp', 'mpee86JAW0*bd@Ac','wp_bfsbj') or die("Error: " . mysqli_error());
	$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


// list($rep, $id, $repemail)  = explode("-", $VarChar);


	// SQL queries
	
	$strSQL = "SELECT number,recruiter,LastName , Company,LogDate,Accepted from ic_contact_form ORDER BY Number DESC";
	$result = mysqli_query($link,$strSQL);

// echo "<BR>".$strSQL."<BR>;


echo "<table  style='width:100%'>";

	while ($row = mysqli_fetch_array($result)) {
		Echo "<tr>";
		Echo "<td><a href='/sales/viewlead.php?lnum=".$row['number']."'>".$row['number']."</a></td>";
		Echo "<td>".$row['recruiter']."</td>";
		Echo "<td>".$row['LastName']."</td>";
		Echo "<td>".$row['Company']."</td>";
		Echo "<td>".$row['LogDate']."</td>";
		Echo "<td>".$row['Accepted']."</td>";
		Echo "</tr>";
	}

Echo "</table>";

?>
</BODY>
</HTML>





