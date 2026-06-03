<?php

 
// Your ClickSend API credentials


// Admin credentials
$username = 'accounting_mail@icreatives.com';
$apiKey   = 'EF1467DA-FBAE-1181-7966-A75523EE11B4'; // this is the actual API key			
$email    = 'stevenc@icreatives.com'; // Subaccount to find

// Make the API request to list subaccounts
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://rest.clicksend.com/v3/subaccounts',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' . base64_encode("$username:$apiKey"),
        'Content-Type: application/json'
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);


// Handle the response
if ($err) {
    echo "cURL Error #: " . $err;
} else {
    $json = json_decode($response, true);

    if (isset($json['data']['data'])) {
        $subaccounts = $json['data']['data'];

        foreach ($subaccounts as $account) {
            if (isset($account['email']) && strtolower($account['email']) === strtolower($email)) {
                $api_key = $account['api_key'];
                echo "✅ API Key for {$email}: {$api_key}\n";
                break;
            }
        }
    } else {
        echo "❌ No subaccount data found.\n";
    }
}
// Send SMS using subaccount API key

$data = [
    'messages' => [
        [
            'source' => 'php',
            'from' => 'icreatives', // optional
            'body' => 'Hello from ClickSend via PHP!',
            'to' => '+19545296291',
            'schedule' => null
        ]
    ]
];

// Re-initialize curl for the second request
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://rest.clicksend.com/v3/sms/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic " . base64_encode($email . ':' . $api_key),
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error #: " . $err;
} else {
    echo $response;
}

?>