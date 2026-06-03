<?php
// Create connection
$conn = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2', 'ck3b2t');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch column names excluding 'id'
$columns = [];
$sql_columns = "SHOW COLUMNS FROM ic_contacts";
$result_columns = mysqli_query($conn, $sql_columns);

if (!$result_columns) {
    die("Error fetching columns: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result_columns)) {
    if ($row['Field'] !== 'id') {
        $columns[] = $row['Field'];
    }
}

// Construct the dynamic WHERE clause
$where_clause = implode(" AND ", array_map(function($col) {
    return "c1.$col = c2.$col";
}, $columns));

// Construct the dynamic SQL query
$sql_duplicates = "
    SELECT c1.*
    FROM ic_contacts c1
    WHERE EXISTS (
        SELECT 1
        FROM ic_contacts c2
        WHERE c1.id <> c2.id
        AND $where_clause
    )
";

$result_duplicates = mysqli_query($conn, $sql_duplicates);

if (!$result_duplicates) {
    die("Error finding duplicates: " . mysqli_error($conn));
}

// Fetch and display the results
while ($row = mysqli_fetch_assoc($result_duplicates)) {
    echo "ID: " . $row['id'] . "\n";
    foreach ($columns as $column) {
        echo "$column: " . $row[$column] . "\n";
    }
    echo "\n";
}

// Close connection
mysqli_close($conn);
?>
