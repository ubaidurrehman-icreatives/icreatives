<HTML>
<BODY>
<?php
$lnum = $_GET['lnum'];
$edit = $_GET['edit'];
$update = $_GET['update'];
$recruiter=$_POST['recruiter'];

// echo "Update: " . $update . "<br>";
// echo "recruiter: " . $recruiter. "<br>";


// show lead detail
	//	$link = mysqli_connect('localhost', 'wp_vtnzp', 'mpee86JAW0*bd@Ac','wp_bfsbj') or die("Error: " . mysqli_error());
	$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

IF ($update=="t" and $lnum > 0)  {
	$strSQL = "Update ic_contact_form SET recruiter='". $recruiter. "' where number = '" . $lnum. "'";
	$result = mysqli_query($link, $strSQL);
	$update = "x";
}

	$strSQL = "SELECT number, recruiter, LastName , Company,LogDate,Accepted, email, Phone, Comment	 from ic_contact_form where number = '". $lnum . "'  ";
	$result = mysqli_query($link,$strSQL);

// echo "<BR>".$strSQL."<BR>";

echo "Lead detail:<P>";


	
	

If ($lnum > 0){

	while ($row = mysqli_fetch_array($result)) {
		Echo "Number: ". $row['number']. "<br>";
		Echo "Lead Date: ". $row['LogDate']. "<br>";
		Echo "Name: ". $row['LastName']. "<br>";

		Echo "Company: ". $row['Company']. "<br>";
		Echo "Email: ". $row['email']. "<br>";
		Echo "Phone: ". $row['Phone']. "<br>";
		Echo "Recruiter: ". $row['recruiter']. "<br>";
		$recruiter = $row['recruiter'];
		Echo "Accepted: ". $row['Accepted']."<br>";
		Echo "Comment: <br>".$row['Comment']."<br>";

	}
$update="f";
}
If (strtoupper($edit) == "T" and $lnum > 0) { ?>
<P>
<form Action = viewlead.php?lnum=<?php echo $lnum; ?>&update=t method="POST">
<input type="text" width="30" name = "recruiter" value = "<?php echo $recruiter; ?>">
<input type = "submit" name="submit" value="Update">
</form>
<?php } ?>


</BODY>
</HTML>





