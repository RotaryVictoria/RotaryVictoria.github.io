<?php

require_once('vendor/medoo.php');

/**
 * Database Schema in the comments, 'cause why not?
 *
 * Emails aren't unique, but still our main way of resolving users.
 * 
 * CREATE TABLE Members (
 * id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 * firstName VARCHAR(30) NOT NULL,
 * lastName VARCHAR(30) NOT NULL,
 * email VARCHAR(50) NOT NULL,
 * reg_date TIMESTAMP,
 * hasPaidInitiationFee TINYINT(1) DEFAULT 0,
 * subscriptionPlan ENUM("monthly", "annual")
 * );
 *
 * alter table Members add stripeToken VARCHAR(255) after subscriptionPlan; //lol
 *
 *
 * alter table Members ADD stripeCustomerID VARCHAR(255) after stripeToken;
 * alter table Members ADD UNIQUE (stripeCustomerID);
 */

class Database {
    private $db;
    private $dbPassword = '3E5kD8Z98J';
    private $lastInsertId;
    private $dbName = "Members";

    function __construct() {
        $this->db = new medoo([
            'database_type' => 'mysql',
            'database_name' => 'rotary',
            'server' => 'localhost',
            'username' => 'root',
            'password' => $this->dbPassword,
            'charset' => 'utf8',
        ]);
    }

    public function recordPurchase($token, $email){
        $userExists = $this->getUserIdByEmail($email);

        if (!$userExists){
            return $this->addMember($token, $email);
        }
        else {
            return $this->updateMemberWithToken($token, $email);
        }

    }

    private function getUserIdByEmail($email){
        $userExists = $this->db->get($this->dbName, "id", [
            'email' => $email,
        ]);

        return $userExists;
    }

    private function addMember($token, $email){
        $this->lastInsertId = $this->db->insert($this->dbName, [
            // "firstName" => $firstName,
            // "lastName" => $lastName,
            // "subscriptionPlan" => $subscriptionPlan
            "email" => $email,
            'stripeToken' => $token,
        ]);
    }

    private function updateMemberWithToken($newToken, $email){
        //While we store tokens, since we use them immediately it shouldn't really matter.
        //So this is probably unnecssary
        $userID = $this->getUserIdByEmail($email);

        $this->db->update($this->dbName, 
            ['stripeToken' => $newToken],
            ['id' => $userID]);
    }

    public function userHasPaidInitiationFee($email){
        $userID = $this->getUserIdByEmail($email);
        $this->db->update($this->dbName, 
            ['hasPaidInitiationFee' => 1],
            ['id' => $userID]);
    }

    public function userSetPlan($email, $plan){
        $userID = $this->getUserIdByEmail($email);
        $this->db->update($this->dbName, 
            ['subscriptionPlan' => $plan],
            ['id' => $userID]);
    }

    public function getStripeIDFromEmail($email){
        return $this->db->get($this->dbName, "stripeCustomerID", [
            'email' => $email,
        ]);
    }

    public function setStripeIDFromEmail($email, $stripeCustomerID){        
        return $this->db->update($this->dbName, 
            ['stripeCustomerID' => $stripeCustomerID],
            ['email' => $email]);
    }

}

?>