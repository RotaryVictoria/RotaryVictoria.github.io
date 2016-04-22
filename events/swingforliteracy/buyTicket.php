<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/payment/php/stripe_simple.php');

$token  = $_POST['stripeToken'];
$email = $_POST['email'];
$identifier = $_POST['identifier'];
$description = $_POST['description'];


if (!(isset($token) && isset($email) && isset($identifier) )){
	echo "Get out of here man.";
	die;
}
else {
	$customer = \Stripe\Customer::create(array(
	    'email' => $email,
	    'card'  => $token
	));

	switch ($identifier) {
		case "swing_for_literacy_single":
			$amount = 2000;
			break;
		case "swing_for_literacy_couple":
			$amount = 3500;
			break;
		case "swing_for_literacy_student":
			$amount = 1500;
			break;
		case "swing_for_literacy_rhs":
			$amount = 1500;
			break;
		default:
			$amount = 2000;
	}

	$charge = \Stripe\Charge::create(array(
	    'customer' => $customer->id,
	    'amount'   => $amount,
	    'currency' => 'cad',
	    'description' => $description,
	));

	echo json_encode([
		'success' => 'true',
		'identifier' => $identifier,
	]);

	return;
}

