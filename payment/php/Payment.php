<?php
require_once('Database.php');

class Payment {

    private $db;
    private $INITIATION_FEE = 3000; //cents
    
    public function __construct(){
        $this->db = new Database();
    }

    public function subscribe($token, $email, $plan){
        $customer = $this->processSubscription($token, $email, $plan);
        $this->saveCustomer($email, $customer);
        $this->db->userSetPlan($email, $plan);

        return $customer;
    }

    public function payInitiationFee($token, $email){
        $customer = $this->processInitiationFee($token, $email);
        $this->saveCustomer($email, $customer);
        $this->db->userHasPaidInitiationFee($email);

        return $customer;
    }

    public function saveToken($token, $email){
        return $this->db->recordPurchase($token, $email);
    }

    private function saveCustomer($email, $customer){
        if (!is_null($customer)){
            return $this->db->setStripeIDFromEmail($email, $customer->id);    
        }      
        
        return false;
    }

    private function processSubscription($token, $email, $plan){
      $stripeCustomerID = $this->db->getStripeIDFromEmail($email);

      if (is_null($stripeCustomerID)){
        //new user
        $customer = \Stripe\Customer::create(array(
          "source" => $token,
          "plan" => $plan,
          "email" => $email
        ));
      }
      else {
        echo "CustomerAlready exists! \n";
        //email already exists, e.g played initiation fee first
        $customer = \Stripe\Customer::retrieve($stripeCustomerID);
        $customer->subscriptions->create(array("plan" => $plan));
      }
      

      return $customer;
    }

    private function processInitiationFee($token, $email){
      try {
          $stripeCustomerID = $this->db->getStripeIDFromEmail($email);

          if (is_null($stripeCustomerID)){
            $customer = \Stripe\Customer::create(array(
                "source" => $token,
                "email" => $email,
            ));
            $stripeCustomerID = $customer->id;
          }
          else {
            $customer = \Stripe\Customer::retrieve($stripeCustomerID);
          }

          $charge = \Stripe\Charge::create(array(
            "amount"   => $this->INITIATION_FEE,
            "currency" => "cad",
            "customer" => $customer->id,
            "description" => "Initiation Fee"
          ));

          return $customer;

      } catch(\Stripe\Error\Card $e) {
        // The card has been declined
        echo json_encode(['error' => 'card_declined']);
        die;
      }
    }
}

?>