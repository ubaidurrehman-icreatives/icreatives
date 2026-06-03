<?php
// Define the login URL and target URL
$loginUrl = "https://app.manatal.com/login"; // Replace with the actual login endpoint
$targetUrl = "https://app.manatal.com/candidates/5060482?tab=more";

// Define login credentials
$username = "stevenc@icreatives.com";
$password = "Agile1Soft!";

// Initialize cURL session
$ch = curl_init();

// Create a cookie file to store session
$cookieFile = "cookies.txt";

// Step 1: Log in to the website
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => $username,
    'password' => $password,
]));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Execute the login request
$loginResponse = curl_exec($ch);

// Check if login was successful
if (strpos($loginResponse, "Invalid username or password") !== false) {
    die("Login failed! Check your credentials.");
}

// Step 2: Access the target URL
curl_setopt($ch, CURLOPT_URL, $targetUrl);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);

// Execute the request to the target page
$pageContent = curl_exec($ch);

// Close the cURL session
curl_close($ch);


$url = "https://app.manatal.com/candidates/5060482?tab=more";

// Fetch the content from the URL and store it in a variable
echo $string = htmlspecialchars(file_get_contents($url));


exit();
// Step 3: Display or process the scraped content
if ($pageContent) {
    echo "Page Content:\n";
    // echo htmlspecialchars($pageContent); // Display HTML content safely
    echo $pageContent; // Display HTML content safely
} else {
    echo "Failed to retrieve content from $targetUrl.";
}
?>
