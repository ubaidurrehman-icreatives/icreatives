<?php
// Connect to the MySQL database
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


// Check if the form is submitted
if(isset($_POST['submit'])) {
  // Define the CSV file name and path
  $filename = 'selected_timesheets.csv';
  $filepath = '' . $filename;

  // Open the CSV file for writing
  $file = fopen($filepath, 'w');

  // Write the headers to the CSV file
  fputcsv($file, array('Worker','Date','Regular Hours','Overtime Hours','Double Time Hours'));

  // Loop through the submitted rows and write them to the CSV file
  foreach($_POST['row'] as $row_id) {
    // Retrieve the data for the selected row
    $sql = "SELECT * FROM users WHERE id = $row_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // Write the row data to the CSV file
    fputcsv($file, array($row['name'], $row['email']));

    // Mark the row as downloaded
    // $update_sql = "UPDATE users SET downloaded = 1 WHERE id = $row_id";
	// $result2 = mysqli_query($link,$update_sql);
	echo "updated ";
  }

  // Close the CSV file
  fclose($file);

  // Redirect the user to the CSV file download link
  $download_link = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $filepath;
  echo '<a href="'.$download_link.'">Download CSV</a>';
}

// Retrieve the data from the database
$sql = "SELECT * FROM users WHERE downloaded = 0";
$result = mysqli_query($conn, $sql);

// Display the data in a table with checkboxes
echo '<form method="POST">';
echo '<table>';
echo '<tr><th>Name</th><th>Email</th><th>Select</th></tr>';
while($row = mysqli_fetch_assoc($result)) {
  echo '<tr>';
  echo '<td>'.$row['name'].'</td>';
  echo '<td>'.$row['email'].'</td>';
  echo '<td><input type="checkbox" name="row[]" value="'.$row['id'].'"></td>';
  echo '</tr>';
}
echo '</table>';
echo '<input type="submit" name="submit" value="Export to CSV">';
echo '</form>';
?>
