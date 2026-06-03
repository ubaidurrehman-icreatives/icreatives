<?php

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2', 'ck3b2t') or die("Error: " . mysqli_error($link));

session_start();

$isLoggedIn = false;

// Debug: Show session and cookie details
echo "<pre>";
echo "Session Variables:\n";
print_r($_SESSION);
echo "Cookies:\n";
print_r($_COOKIE);
echo "</pre>";

if (!empty($_SESSION['user_id'])) {
    // User is already logged in via session
    $isLoggedIn = true;

    if (!isset($_SESSION['recruiter'])) {
        $_SESSION['recruiter'] = 0;
    }

    if ($_SESSION['recruiter'] == 1) {
        // Check if the recruiter has access
        $query = "SELECT id FROM ic_contacts WHERE email = '" . $_SESSION['user_id'] . "' AND icreativesportalaccess = 1";
        $result = mysqli_query($link, $query);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount > 0) {
            $row = mysqli_fetch_array($result);
            $contactID = $row['id'];
            $_SESSION['contactID'] = $contactID;
        } else {
            // Redirect to job selection for recruiters without access
            header("Location: /portal-choose-job/?user=" . $_SESSION['user_id']);
            exit;
        }
    }
} else if (!empty($_COOKIE['client_login']) && !empty($_COOKIE["token"]) && !empty($_COOKIE["selector"])) {
    // User has cookies, validate them
    $isTokenVerified = false;
    $isExpiryDateVerified = false;

    // Debug: Show cookie values
    echo "<pre>";
    echo "client_login: " . $_COOKIE['client_login'] . "\n";
    echo "token: " . $_COOKIE['token'] . "\n";
    echo "selector: " . $_COOKIE['selector'] . "\n";
    echo "</pre>";

    // Query to validate token and check expiration
    $query = "SELECT id, contact_id, token, expires_at, CASE WHEN expires_at > CURRENT_TIMESTAMP THEN 1 ELSE 0 END AS valid
              FROM ic_client_login_tickets
              WHERE selector = '" . $_COOKIE['selector'] . "'";

    $result = mysqli_query($link, $query);
    $rowCount = mysqli_num_rows($result);

    echo "<pre>Query Result Count: $rowCount</pre>";

    if ($rowCount > 0) {
        $row = mysqli_fetch_array($result);
        $ticket_id = $row['id'];
        $contactID = $row['contact_id'];

        // Validate token and expiration
        if (password_verify($_COOKIE['token'], $row['token'])) {
            $isTokenVerified = true;
        } else {
            echo "<pre>Token verification failed.</pre>";
        }

        if ($row['valid']) {
            $isExpiryDateVerified = true;
        } else {
            echo "<pre>Token expired.</pre>";
        }

        if ($isTokenVerified && $isExpiryDateVerified) {
            // Token is valid and not expired, log in the user
            $isLoggedIn = true;

            // Retrieve user details
            $query = "SELECT email, firstname, lastname, companyname FROM ic_contacts WHERE id = '$contactID'";
            $result = mysqli_query($link, $query);
            $details = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION['user_id'] = htmlspecialchars($details['email']);
            $_SESSION['contactID'] = htmlspecialchars($contactID);
            $_SESSION['first_name'] = htmlspecialchars($details['firstname']);
            $_SESSION['last_name'] = htmlspecialchars($details['lastname']);
            $_SESSION['company_name'] = htmlspecialchars($details['companyname']);

            // Extend cookie expiration
            $cookie_expiration_time = time() + (3 * 30 * 24 * 60 * 60); // 90 days from now
            setcookie("client_login", $_SESSION['user_id'], $cookie_expiration_time, "/");
            setcookie("selector", $_COOKIE['selector'], $cookie_expiration_time, "/");
            setcookie("token", $_COOKIE['token'], $cookie_expiration_time, "/");

            // Debug: Indicate successful login via cookies
            echo "<pre>Login via cookies successful. Session and cookies extended.</pre>";
        } else {
            // Invalidate expired or invalid token
            $query = "UPDATE ic_client_login_tickets SET is_expired = 1 WHERE id='" . $ticket_id . "'";
            mysqli_query($link, $query);
        }
    } else {
        echo "<pre>No matching token found in the database.</pre>";
    }
}

if (!$isLoggedIn) {
    // Clear cookies and redirect unauthenticated users
    setcookie("selector", '', time() - 3600, "/");
    setcookie("token", '', time() - 3600, "/");

    if (!empty($_COOKIE['client_login'])) {
        $user = $_COOKIE['client_login'];
        header("Location: /client-portal-home/?user=$user" . (!empty($_REQUEST['o']) ? "&o=" . $_REQUEST['o'] : ""));
        exit;
    }

    header("Location: /portals");
    exit;
}

?>
