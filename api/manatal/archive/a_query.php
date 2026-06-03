<?php
$conn = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2', 'ck3b2t') or die("Error: " . mysqli_error());

// Specify the Employee_ID for filtering
$employeeID = 8638;

// Retrieve column names and data types from ic_webtime
$sql = "SELECT COLUMN_NAME, COLUMN_TYPE
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'ic_webtime' AND TABLE_SCHEMA = 'ck3b2t'";

$result = mysqli_query($conn, $sql);

if ($result->num_rows > 0) {
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row;
    }

    // Construct the INSERT SQL query for each row
    $insertQuery = "INSERT INTO ic_timesheets (";
    $insertQuery .= implode(', ', array_column($columns, 'COLUMN_NAME')) . ") VALUES ";

    // Retrieve data for each column
    $dataQuery = "SELECT ";
    foreach ($columns as $column) {
        if (strpos($column['COLUMN_TYPE'], 'decimal') !== false) {
            $dataQuery .= 'ROUND(' . $column['COLUMN_NAME'] . ', 2) AS ' . $column['COLUMN_NAME'] . ', ';
        } else {
            $dataQuery .= $column['COLUMN_NAME'] . ', ';
        }
    }
    $dataQuery = rtrim($dataQuery, ', ') . " FROM ic_webtime WHERE Employee_ID = $employeeID";

    $dataResult = mysqli_query($conn, $dataQuery);

    if ($dataResult->num_rows > 0) {
        while ($row = $dataResult->fetch_assoc()) {
            $formattedValues = [];

            foreach ($columns as $column) {
                $value = $row[$column['COLUMN_NAME']];

                if (strpos($column['COLUMN_TYPE'], 'decimal') !== false) {
                    // Format DECIMAL values
                    $formattedValues[] = sprintf('%.2f', $value);
                } else {
                    // Quote other values
                    $formattedValues[] = "'$value'";
                }
            }

            echo $insertQuery .= "(" . implode(', ', $formattedValues) . "), ";
					exit();
        }

        $insertQuery = rtrim($insertQuery, ', ') . ";";


        // Execute the SQL query
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            echo "Records inserted successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "No data found for the specified Employee_ID.";
    }
} else {
    echo "No common columns found between source_table and destination_table.";
}

$conn->close();
?>
