<?php
// Get our helper functions

// Set variables for our request
$api_key = "e97e2bd0a49fb36acbd19e4f7a9fb842"; //Replace with your API KEY
$shop = $_GET['shop'];

$scopes = "read_orders,write_products";
$redirect_uri = "https://paki-shop.herokuapp.com/generate_token";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();
