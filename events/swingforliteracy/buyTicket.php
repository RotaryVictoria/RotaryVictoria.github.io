<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/payment/php/stripe_simple.php');

$token  = $_POST['stripeToken'];
$email = $_POST['email'];
$description = $_POST['description'];


if (!(isset($token) && isset($email))){
	echo "Get out of here man.";
}
else {
	// echo "you're in";
	// die;
	$customer = \Stripe\Customer::create(array(
	    'email' => $email,
	    'card'  => $token
	));

	$charge = \Stripe\Charge::create(array(
	    'customer' => $customer->id,
	    'amount'   => 2000,
	    'currency' => 'cad',
	    'description' => $description,
	));
}

