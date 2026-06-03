<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = "localhost";
$username = "re0nm8";
$password = "50h8r6WNvB!ozVY2";
$database = "ck3b2t";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/SMTP.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/class-phpmailer.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
// Handle password reset request
if (isset($_POST['reset_password']) && isset($_POST['reset_password'])) {
    $email = trim($_POST['email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Invalid email format.</p>";
    } else {
        $stmt = $conn->prepare("SELECT id FROM ic_subscriptions WHERE ap_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $reset_token = bin2hex(random_bytes(8));
            $hashed_token = password_hash($reset_token, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE ic_subscriptions SET user_pass=? WHERE ap_email=?");
            $stmt->bind_param("ss", $hashed_token, $email);
            $stmt->execute();
            
            // Send reset email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.1and1.com'; // Change to your mail server
                $mail->SMTPAuth = true;
                $mail->Username = 'exchange@icreatives.co'; // SMTP username
                $mail->Password = 'Call1888icreate!'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('reset@icreatives.com', 'icreatives');
                $mail->addAddress($email);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Your new temporary password is: $reset_token\nPlease login and change your password immediately.";
		
				// DKIM Setup
				$mail->DKIM_domain = 'icreatives.com';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-co-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.co'; // Typically same as From			
				
                
                $mail->send();
                echo "<p style='color:green;'>Password reset email has been sent.</p>";
            } catch (Exception $e) {
                echo "<p style='color:red;'>Email could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
            }
        } else {
            echo "<p style='color:red;'>No user found with this email.</p>";
        }
        
        $stmt->close();
    }
	exit();
}

// Handle adding or updating a record
if (isset($_SESSION['user_pass']) && isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 1) {
 echo "XXXXXDD";

 if (isset($_POST['company_name'])) {$company_name = trim($_POST['company_name']);}
     if (isset($_POST['company_id'])) {$company_id = trim($_POST['company_id']);}
     if (isset($_POST['api_token'])) {$api_token = trim($_POST['api_token']);}
     if (isset($_POST['ap_email'])) {$ap_email = trim($_POST['ap_email']);}
    $admin = isset($_POST['admin']) ? 1 : 0;
    if (isset($_POST['user_pass'])) {$user_pass = trim($_POST['user_pass']);}

    // Validate inputs

    if (empty($company_name) || empty($company_id) || empty($api_token) || empty($ap_email)) {
        echo "<p style='color:red;'>All fields are required.</p>";
    } elseif (!filter_var($ap_email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Invalid email format.</p>";
    } else {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
;
            // Fetch existing password if no new password is entered
            $id = $_POST['id'];
            $stmt = $conn->prepare("SELECT user_pass FROM ic_subscriptions WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($existing_pass);
            $stmt->fetch();
            $stmt->close();
            // Only encrypt if a new password was entered
            if ($existing_pass !== $_POST['user_pass']) {
				
               echo  $user_pass = openssl_encrypt($_POST['user_pass'], "AES-128-ECB", "9f86d081884c7d659a2feaa0c55ad014");
            }
            // Update record
            $stmt = $conn->prepare("UPDATE ic_subscriptions SET company_name=?, company_id=?, api_token=?, ap_email=?, admin=?, user_pass=? WHERE id=?");
            $stmt->bind_param("ssssisi", $company_name, $company_id, $api_token, $ap_email, $admin, $user_pass, $id);
        } else {
            // Encrypt new password before saving
             $user_pass = openssl_encrypt($_POST['user_pass'], "AES-128-ECB", "9f86d081884c7d659a2feaa0c55ad014");

            // Insert new record
            $stmt = $conn->prepare("INSERT INTO ic_subscriptions (company_name, company_id, api_token, ap_email, admin, user_pass) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssis", $company_name, $company_id, $api_token, $ap_email, $admin, $user_pass);
        }

        $stmt->execute();
        $stmt->close();
        header("Location: manatal_manage_subscription.php");
        exit;
    }
}
}
// Fetch all records
$result = $conn->query("SELECT * FROM ic_subscriptions");

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscriptions</title>
</head>
<body>
    <h2>Manage IC Subscriptions</h2>
    <form action="" method="POST">
        <input type="hidden" name="id" id="id">
        <label>Company Name:</label>
        <input type="text" name="company_name" id="company_name" required><br>
        <label>Company ID:</label>
        <input type="text" name="company_id" id="company_id" required><br>
        <label>API Token:</label>
        <input type="text" name="api_token" id="api_token" required><br>
        <label>AP Email:</label>
        <input type="email" name="ap_email" id="ap_email" required><br>
        <label>Admin:</label>
        <input type="checkbox" name="admin" id="admin"><br>
        <label>User Password:</label>
        <input type="text" name="user_pass" id="user_pass"><br>
        <button type="submit">Save</button>
    </form>
    <hr>
    <h3>Existing Records</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Company Name</th>
            <th>Company ID</th>
            <th>API Token</th>
            <th>AP Email</th>
            <th>Admin</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['company_name'] ?></td>
                <td><?= $row['company_id'] ?></td>
                <td><?= $row['api_token'] ?></td>
                <td><?= $row['ap_email'] ?></td>
                <td><?= $row['admin'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="javascript:void(0);" onclick="editRecord('<?= $row['id'] ?>', '<?= $row['company_name'] ?>', '<?= $row['company_id'] ?>', '<?= $row['api_token'] ?>', '<?= $row['ap_email'] ?>', '<?= $row['admin'] ?>', '<?= $row['user_pass'] ?>')">Edit</a> |
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php }
			?>
    </table>

    <script>
        function editRecord(id, company_name, company_id, api_token, ap_email, admin, user_pass) {
            document.getElementById('id').value = id;
            document.getElementById('company_name').value = company_name;
            document.getElementById('company_id').value = company_id;
            document.getElementById('api_token').value = api_token;
            document.getElementById('ap_email').value = ap_email;
            document.getElementById('admin').checked = admin == '1';
            document.getElementById('user_pass').value = user_pass;
        }
    </script>
</body>
</html>