<?php

// ob_clean();
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


$query = "
SELECT full_name,id,company_name,email from ic_contacts
where (full_name NOT LIKE '%unknown%' AND email NOT LIKE '%blindemail%' AND email <> ''  AND company_name NOT LIKE '%Walmart%' AND company_name NOT LIKE '%Sams%')
";

	$result = mysqli_query($link,$query );

// Check if the query was successful
// Check if the query was successful
if (!$result) {
    die("Query failed: " . $link->error);
}

// Create a file pointer connected to the output stream
$output = fopen('ic_contacts.csv', 'w');

// Output column headers
fputcsv($output, ['Full Name', 'ID', 'Company Name', 'Email']);

// Output data from MySQL query
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close the file pointer
fclose($output);

// Close the database connection
$link->close();

// Generate a link to download the CSV file
$downloadLink = 'ic_contacts.csv';

echo "CSV file created successfully! <a href='$downloadLink' download>Download CSV</a>";
?>