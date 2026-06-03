<?php
$connection_string = 'DRIVER={ODBC Driver 17 for SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';

$user = 'sa';
$pass = 'ic3eempact!';

$conn = odbc_connect($connection_string, $user, $pass );

if (!$conn) {
    die("Connection failed: " . odbc_errors());
}

$tableName = "ic_webtime_dup";
$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName'";

$result = odbc_exec($conn,$query);


// $result = sqlsrv_query($conn, $query);
$fields = array();

while ($row = odbc_fetch_array($result)) {
    $fields[] = $row['COLUMN_NAME'];
}

$query = "SELECT id, fullname,email,company   FROM $tableName";
$result = odbc_exec($conn, $query);

$csvFileName = "output.csv";
$csvFile = fopen($csvFileName, 'w');

if ($csvFile) {
    fputcsv($csvFile, $fields);

    while ($row = odbc_fetch_array($result)) {
		
		// echo $row['company_name'];
        fputcsv($csvFile, $row);
    }

    fclose($csvFile);
	echo "DONE";
} else {
    die("Failed to open CSV file for writing.");
}

odbc_close($conn);
?>
