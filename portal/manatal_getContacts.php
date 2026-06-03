<?php
// Connect to your MySQL database
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


if ($link->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch contacts based on the input
$input = $_GET['q'];
$sql = "SELECT * FROM ic_contacts WHERE display_name LIKE '%$input%'";
$result = mysqli_query($link,$sql);


// Display the suggestions
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo '<div>' . $row['display_name'] . '</div>';
    }
} else {
    echo '<div>No results found</div>';
}

$link->close();
?>
