<!DOCTYPE html>
<html>
<head>
    <title>CSV File Upload</title>
</head>
<body>
<!--
Yes, you can read and process a file from memory in PHP without actually saving it to the server's 
filesystem. This can be done using the file_get_contents function to read the file contents 
from the user's uploaded file directly into a variable. 
Here's how you can modify the previous example to achieve this:
-->
    <h2>Upload CSV File</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="csvFile" accept=".csv">
        <input type="submit" name="submit" value="Upload">
    </form>

    <?php
    // Database connection details
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database_name";

    // Create a database connection
require_once __DIR__ . '/../db/db.php';
$conn = db();   


    if (isset($_POST["submit"])) {
        // Check if a file was uploaded
        if ($_FILES["csvFile"]["error"] == UPLOAD_ERR_OK) {
            // Read the uploaded file contents into a variable
            $csvData = file_get_contents($_FILES["csvFile"]["tmp_name"]);

            // Convert CSV data to an array of lines
            $csvLines = explode("\n", $csvData);

            // Loop through each line of the CSV data
            foreach ($csvLines as $line) {
                // Split the line into an array using a comma as the delimiter
                $data = str_getcsv($line);

                if (count($data) == 3) {
                    // Extract data from the CSV
                    $invoiceNumber = $data[0];
                    $paidDate = $data[1];
                    $paidAmount = $data[2];

                    // Prepare and execute the SQL query to update the invoice as paid
                    $sql = "UPDATE invoices SET paid_date='$paidDate', paid_amount='$paidAmount' WHERE invoice_number='$invoiceNumber'";
                    $result = $conn->query($sql);

                    if ($result) {
                        echo "Invoice #$invoiceNumber updated as paid with $paidAmount on $paidDate.<br>";
                    } else {
                        echo "Error updating invoice #$invoiceNumber: " . $conn->error . "<br>";
                    }
                }
            }
        }
    }

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
