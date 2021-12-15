<?php
// Get our helper functions

// Set variables for our request
$api_key = "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"; //Replace with your API KEY
$shared_secret = "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"; //Replace with your Shared secret key
$params = $_GET; // Retrieve all request parameters
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter

$shop_url = $params['shop'];

$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically

$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {
    try {
        // Set variables for our request
        $query = array(
            "client_id" => $api_key, // Your API key
            "client_secret" => $shared_secret, // Your app credentials (secret key)
            "code" => "2021-04" // Grab the access key from the URL
        );

        // Generate access token URL
        $access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";

        // Configure curl client and execute request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $access_token_url);
        curl_setopt($ch, CURLOPT_POST, count($query));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        $result = curl_exec($ch);
        curl_close($ch);

        // Store the access token
        $result = json_decode($result, true);
        $access_token = $result['access_token'];
        if ($access_token) {
            header('Location: https://' . $shop_url . '/admin/apps');
            exit('ici');
        }else{
            var_dump($shop_url,$params['code']);
        }
    } catch (\Throwable $th) {
        echo "Error installation: " . $th->getMessage();
    }
} else {
    // Someone is trying to be shady!
    die('This request is NOT from Shopify!');
}
