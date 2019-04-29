<?php
namespace App\DomainModels;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalDomain{
    Private $client;


    public function __construct()
    {
        //this is my own paypal clientID secret for sandbox environtment, please change this according to ur account- 
        //contact me shaifulazhartalib@gmail.com for more information about integration with paypal
        $clientId = "AQPgENUIqjDMoIP50iqUQU0Ig4i1AwP5GYnnz6Sdb54QLyJnFel96cHkS6g6pzzsOH5XKfRcX3KIDFcF";
        $clientSecret = "EHebJslKf8XHFMutOXA4MkeDyrdEsSb8wINAvbsZzgpuJ98e61xHwtPcUKlcwWyF0HhrQH5H_Kfdrtsb";
        
        $environment = new SandBoxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($environment);
        
    }

    public function client(){
        return $this->client;
    }
}