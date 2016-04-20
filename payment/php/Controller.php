<?php

class Controller {
    //these have to match the stripe dashboard
    public static $stripeMonth = 'membership_month_25';
    public static $stripeYear = 'membership_year';

    public function isStripeRequest(){
        return (strlen($this->getToken()) && strlen($this->getEmail()));
    }

    public function getSubscriptionPlan(){
        $plan = $this->getPost('subscriptionPlan');

        if ($plan === "year"){
            return Controller::$stripeYear;
        }
        else if ($plan === "month" && strlen($plan)){
            return Controller::$stripeMonth;
        }
    }

    public function getToken(){
        return $this->getPost('stripeToken');
    }

    public function getEmail(){
        return $this->getPost('email');
    }

    private function getPost($string){
        if (isset($_POST[$string]) && $_POST[$string] !== '')  {
            return $_POST[$string];
        }
    }
}

?>