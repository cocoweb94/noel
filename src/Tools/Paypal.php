<?php
// src/Tools/Paypal.php
namespace App\Tools;

require __DIR__ . '/../../vendor/autoload.php';
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class Paypal
{
    private $clientId = "AXdUJ7_N2EIN9RPizdCQqASBy2DTLBeFKK8t1AyFwMRgm50pbUFn8EYxE_yMex0DwWjw-NYtQjqyDBmf";
    private $clientSecret = "EFv3Mz06xJgVZAwqy4FLj1jY6dH6RTmgs5COBm92AAo9ixI_Ku323OK8dPeIJHTv2-yqxdvnhtGhzsk1";
    private $client;
    private $referenceId = "HK5FDBM745ZDC";

    public function __construct() {
        // Creating an environment
        $environment = new ProductionEnvironment($this->clientId, $this->clientSecret);
        $this->client = new PayPalHttpClient($environment);
    }

    public function order($prix){

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $this->referenceId,
                "amount" => [
                    "value" => $prix,
                    "currency_code" => "EUR"
                ]
            ]],
            "application_context" => [
                "cancel_url" => "https://easycabservices.com/easycab/public/index.php/home#reservation",
                "return_url" => "https://easycabservices.com/easycab/public/index.php/home#reservation"
            ]
        ];

        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return $response->result;
        }catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
        }
    }

    public function capture($id){
        // Here, OrdersCaptureRequest() creates a POST request to /v2/checkout/orders
        // $response->result->id gives the orderId of the order created above
        $request = new OrdersCaptureRequest($id);
        $request->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return $response->result;
        }catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
            return false;
        }
    }

}