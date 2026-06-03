<?php
// Create connection
$conn = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2', 'ck3b2t');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch column names excluding 'id' and 'deactivated'
$columns = [];
$sql_columns = "SHOW COLUMNS FROM ic_contacts";
$result_columns = mysqli_query($conn, $sql_columns);

if (!$result_columns) {
    die("Error fetching columns: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result_columns)) {
    if ($row['Field'] !== 'id' && $row['Field'] !== 'deactivated') {
        $columns[] = $row['Field'];
    }
}

// Check if columns array is empty
if (empty($columns)) {
    die("No columns found to group by.");
}

// Construct the GROUP BY clause dynamically
$group_by_clause = implode(", ", $columns);

// Find duplicates except for one per group
$sql_duplicates = "
    SELECT MIN(id) as keep_id, $group_by_clause
    FROM ic_contacts
    GROUP BY $group_by_clause
    HAVING COUNT(*) > 1
";
$result_duplicates = mysqli_query($conn, $sql_duplicates);

if (!$result_duplicates) {
    die("Error finding duplicates: " . mysqli_error($conn));
}

$ids_to_keep = [];
while ($row = mysqli_fetch_assoc($result_duplicates)) {
    $ids_to_keep[] = $row['keep_id'];
}

// If no duplicates found, no need to proceed further
if (empty($ids_to_keep)) {
    die("No duplicates found.");
}

// Convert arrays to comma-separated strings
$ids_to_keep_str = implode(",", $ids_to_keep);

// Update deactivated field for duplicates, excluding the ones to keep and those in ic_matches.portal_users
$sql_update = "
    UPDATE ic_contacts c
    SET deactivated = 1
    WHERE c.id NOT IN ($ids_to_keep_str)
    AND NOT EXISTS (
        SELECT 1 
        FROM ic_matches m
        WHERE m.portal_users LIKE CONCAT('%,', c.id, ',%')
        OR m.portal_users LIKE CONCAT(c.id, ',%')
        OR m.portal_users LIKE CONCAT('%,', c.id)
        OR m.portal_users = c.id
    )
    AND c.id IN (
        SELECT id 
        FROM (
            SELECT id 
            FROM ic_contacts 
            GROUP BY $group_by_clause 
            HAVING COUNT(*) > 1
        ) as subquery
    )
";
if (mysqli_query($conn, $sql_update)) {
    echo "Records updated successfully.";
} else {
    echo "Error updating records: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>
