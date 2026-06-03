<?php

// Define the path to the log file
$log_file = 'webhook.log';

// Log each access to the webhook
$log_message = date('Y-m-d H:i:s') . " - Webhook accessed.\n";
file_put_contents($log_file, $log_message, FILE_APPEND);

// If the request method is POST, handle the incoming webhook request
 // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data sent by the API
    $data = file_get_contents('php://input');
    
    // Process the data (you'll need to customize this part based on your API)
    // For example, you might parse JSON data and extract specific information
    $parsed_data = json_decode($data, true);
    // Process $parsed_data as needed...

    // Log the incoming request and processed data to a file
    $log_message = date('Y-m-d H:i:s') . " - Webhook request received:\n" . $data . "\nProcessed data:\n" . print_r($parsed_data, true) . "\n\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);

    // Send a response back to the API to acknowledge receipt of the data
    http_response_code(200); // OK status
    echo 'Webhook received and processed successfully.';
	/*
} else {
    // If the request method is not POST, return a 405 Method Not Allowed status
    http_response_code(405);
    echo 'Method Not Allowed';

}
	*/
