<?php
session_start();
define("ENCRYPTION_KEY", "manatalencryptkey"); // Change this to a strong random key

// Database configuration
$host = "localhost"; // Change if necessary
$username = "re0nm8"; // Change to your MySQL username
$password = "50h8r6WNvB!ozVY2"; // Change to your MySQL password
$database = "ck3b2t"; // Change if necessary

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle login
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    echo $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "<p style='color:red;'>Email and password are required.</p>";
    } else {
        $stmt = $conn->prepare("SELECT id, user_pass, admin FROM ic_subscriptions WHERE ap_email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $admin);
            $stmt->fetch();
            $encrypted_pass = $hashed_password;
			
			echo $encrypted_pass ."--".$hashed_password."XXX";
			
			
            if ($admin != 1) {
                $message = "<p style='color:red;'>Access denied. You must be an admin.</p>";
           // } elseif (password_verify($password, $hashed_password)) 			   
			   
            } elseif ($encrypted_pass === $hashed_password) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_email'] = $email;
                $_SESSION['is_admin'] = $admin;
                  // Encrypt user_pass before storing
				$_SESSION['user_pass'] = openssl_encrypt($hashed_password, "AES-128-ECB", "9f86d081884c7d659a2feaa0c55ad014");
                
                // Redirect using PHP
                header("Location: manatal_manage_subscription.php");
                exit();
            } else {
                $message = "<p style='color:red;'>Invalid password.</p>";
            }
        } else {
            $message = "<p style='color:red;'>No user found with this email.</p>";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?= $message; ?>
    <form action="manatal_admin_login.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <hr>
    <h3>Forgot Password?</h3>
    <form action="manatal_manage_subscription.php" method="POST">
        <label>Enter your email:</label>
        <input type="email" name="email" required><br>
		<input type="hidden" name = "password" value="">
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
