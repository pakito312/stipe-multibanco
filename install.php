.wp-block-code{border:0;padding:0}.wp-block-code>div{overflow:auto}.shcb-language{border:0;clip:rect(1px,1px,1px,1px);-webkit-clip-path:inset(50%);clip-path:inset(50%);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px;word-wrap:normal;word-break:normal}.hljs{box-sizing:border-box}.hljs.shcb-code-table{display:table;width:100%}.hljs.shcb-code-table>.shcb-loc{color:inherit;display:table-row;width:100%}.hljs.shcb-code-table .shcb-loc>span{display:table-cell}.wp-block-code code.hljs:not(.shcb-wrap-lines){white-space:pre}.wp-block-code code.hljs.shcb-wrap-lines{white-space:pre-wrap}.hljs.shcb-line-numbers{border-spacing:0;counter-reset:line}.hljs.shcb-line-numbers>.shcb-loc{counter-increment:line}.hljs.shcb-line-numbers .shcb-loc>span{padding-left:.75em}.hljs.shcb-line-numbers .shcb-loc::before{border-right:1px solid #ddd;content:counter(line);display:table-cell;padding:0 .75em;text-align:right;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;white-space:nowrap;width:1%}.hljs>mark.shcb-loc{background-color:#3F3F3F}
<?php
// Get our helper functions
require_once("config.php");

// Set variables for our request
$api_key = $app_id; //Replace with your API KEY
$shared_secret = $app_pass; //Replace with your Shared secret key
$params = $_GET; // Retrieve all request parameters
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter

$shop_url = $params['shop'];

$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically

$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {

	// Set variables for our request
	$query = array(
		"client_id" => $api_key, // Your API key
		"client_secret" => $shared_secret, // Your app credentials (secret key)
		"code" => $params['code'] // Grab the access key from the URL
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

	// Show the access token (don't do this in production!)
	//echo $access_token;
	if( $access_token) {
		header('Location: https://' . $shop_url . '/admin/apps');
		exit();
	} else {
		echo "Error installation: " . mysqli_error( $conn );
	}

} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}