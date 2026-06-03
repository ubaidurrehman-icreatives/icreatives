<?php

// ob_clean();
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


$query = "
SELECT candidate_name,id,candidate_email from ic_matches
where (candidate_email NOT LIKE '%blindemail%' AND candidate_email <> '') GROUP BY candidate_email
";

	$result = mysqli_query($link,$query );

// Check if the query was successful
// Check if the query was successful
if (!$result) {
    die("Query failed: " . $link->error);
}

// Create a file pointer connected to the output stream
$output = fopen('ic_talent.csv', 'w');

// Output column headers
fputcsv($output, ['Full Name', 'ID', 'Email']);

// Output data from MySQL query
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close the file pointer
fclose($output);

// Close the database connection
$link->close();

// Generate a link to download the CSV file
$downloadLink = 'ic_talent.csv';

echo "CSV file created successfully! <a href='$downloadLink' download>Download CSV</a>";
?>