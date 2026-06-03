<?php

/*
Below is a simplified PHP example of how you might add invoices and clients to QuickBooks Online 
using the QuickBooks Online API.

Please note that this example assumes you have already obtained your OAuth 2.0 credentials 
(client ID and client secret) from the QuickBooks Developer Dashboard and have the necessary authorization tokens.



*/

// require 'vendor/autoload.php'; // Include the QuickBooks API SDK
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;

// Configure OAuth 2.0 credentials
$clientID = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';
$accessToken = 'YOUR_ACCESS_TOKEN';
$refreshToken = 'YOUR_REFRESH_TOKEN';
$realmID = 'YOUR_REALM_ID';

// Set up the QuickBooks API DataService
$dataService = DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => $clientID,
    'ClientSecret' => $clientSecret,
    'accessTokenKey' => $accessToken,
    'refreshTokenKey' => $refreshToken,
    'QBORealmID' => $realmID,
    'baseUrl' => "Development" // Use "Production" for production environment
));

// Create a customer (client) if not already exists
$customer = Customer::create([
    "DisplayName" => "New Client",
    // Add other customer details here
]);
$customer = $dataService->Add($customer);

// Create an invoice
$invoice = Invoice::create([
    "Line" => [
        [
            "Amount" => 100.00,
            "DetailType" => "SalesItemLineDetail",
            "SalesItemLineDetail" => [
                "ItemRef" => [
                    "value" => 1 // Reference to the item ID
                ]
            ]
        ]
    ],
    "CustomerRef" => [
        "value" => $customer->Id // Assign the customer ID from the created customer
    ],
    // Add other invoice details here
]);
$invoice = $dataService->Add($invoice);

// Display the created invoice details
echo "Invoice created successfully. Invoice ID: " . $invoice->Id;

?>
