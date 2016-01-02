<?php

require_once('stripe-php-3.5.0/init.php');

\Stripe\Stripe::setApiKey("sk_test_uYBT7d1BoCfHEqsLURYGYZJE");
// TODO - Set to live key

\Stripe\Plan::create(array(
  "amount" => 2000,
  "interval" => "month",
  "name" => "Amazing Gold Plan",
  "currency" => "cad",
  "id" => "gold")
);

var_dump('fieajp');

// phpinfo();

?>