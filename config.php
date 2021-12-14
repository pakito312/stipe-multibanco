<?php

    $currency = getenv("CURRENCY");
    $lang = getenv("LANG");
    $brandName = getenv("BRANDNAME");

    //Your Shopify App Credentials
    $app_id = getenv("SHOPIFY_APPID");
    $app_pass = getenv("SHOPIFY_APPPASS");
    $store_url = getenv("SHOPIFY_DOMAIN");

    //Your Stripe Api Keys
    $live_public = getenv("STRIPE_LIVE_PUBLIC");
    $live_secret = getenv("STRIPE_LIVE_SECRET");
    $test_public = getenv("STRIPE_TEST_PUBLIC");
    $test_secret = getenv("STRIPE_TEST_SECRET");

    $stripe_webhookSecret_live = getenv("STRIPE_LIVE_WEBHOOK_SECRET");
    $stripe_webhookSecret_test = getenv("STRIPE_TEST_WEBHOOK_SECRET");

    

    //Enable Test or not
    $enableTest = getenv("ENABLE_TEST") === 'true'? true: false;

    //Store URLs
    $cancel_url = getenv("SHOPIFY_URL_CANCEL");
    $return_url = getenv("SHOPIFY_URL_RETURN") . "?id=";

?>