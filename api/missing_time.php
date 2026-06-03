<?php
// Connect to MySQL
// $mysqli = new mysqli("host", "user", "password", "database");
$mysqli = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


// Get the columns from tableA
$result = $mysqli->query("SHOW COLUMNS FROM ic_timesheets_backup4");
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

// Create the INSERT query using the fields from tableA
$fields = implode(", ", $columns);
$query = "INSERT INTO ic_timesheets_new ($fields) 
          SELECT $fields 
          FROM ic_timesheets_backup4 a 
          WHERE NOT EXISTS (
              SELECT 1 FROM ic_timesheets_new b WHERE a.Unique_id = b.Unique_id
          )";

// Execute the query
if ($mysqli->query($query) === TRUE) {
    echo "Records inserted successfully!";
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>