<?php

require_once('vendor/stripe-php-3.5.0/init.php');
// require_once('Database.php');
require_once('Controller.php');
require_once('Payment.php');

//TEST
// \Stripe\Stripe::setApiKey("sk_test_jVjwaNKQpsEoX3yMeUHFgoYb");
//LIVE
\Stripe\Stripe::setApiKey("sk_live_nuOQVaQKkNcwlC5AYxjvdkeD");

// $db = new Database();
$app = new Controller();
$stripe = new Payment();

if (!$app->isStripeRequest()){
    echo "What do you want, exactly?";
    // die;
}
else {
    $token = $app->getToken();
    $email = $app->getEmail();
    $plan = $app->getSubscriptionPlan();  
    $stripe->saveToken($token, $email);


    if ($plan){
        $stripeResponse = $stripe->subscribe($token, $email, $plan);
    }
    else {
        $stripeResponse = $stripe->payInitiationFee($token, $email);
    }
}

?>