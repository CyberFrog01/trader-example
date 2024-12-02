<?php

namespace App\Service;

class RequestService
{
    private $publicKey = "publik_key";
    private $privateKey = "private_key";
    private $root = "https://market/api";

    //User
    public function getBalance() {
        $method = 'GET';
        $url = '/account/balance';
        return $this->createRequest($method, $url);
    }

    public function getTargets() {
        $method = 'GET';
        $url = '/account/targets';
        return $this->createRequest($method, $url);
    }


    //Items
    public function createTargetRequest($targetBody) {
        $method = 'POST';
        $url = '/exchange/v1/target/create';
        return $this->createRequest($method, $url, $targetBody);
    }

    public function getItemHistory($gameId, $title, $limit = 50, $type="") {
        $title = urlencode($title);
        $method = 'GET';
        if ($type) {
            $url = '/trade-aggregator/v1/last-sales?gameId=' . $gameId . '&title=' . $title . '&txOperationType=' . $type . '&limit=' . $limit;
        } else {
            $url = '/trade-aggregator/v1/last-sales?gameId=' . $gameId . '&title=' . $title . '&limit=' . $limit;
        }
        
        return $this->createRequest($method, $url);
    }

    //Basic request function, this function is different for each market
    public function createRequest($method, $url, $postParams = []) {
        //Get current timestamp
        $now = new \DateTime();
        $timestamp = $now->getTimestamp();
        
        //Fill request headers, 
        $headers = [
            'X-Api-Key:' . $this->publicKey,
            'X-Sign-Date:' . $timestamp,
            'X-Request-Sign:' . $this->generateSignature($method, $url, $timestamp, $postParams),
            'Content-Type:' . 'application/json'
        ];
        
        //Creating request, it can be created using HTTP Client, but I didn't use it in this example
        $curlRequest = curl_init();
        curl_setopt($curlRequest, CURLOPT_URL, $this->root . $url);
        curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, $method);
        if ($postParams) {
            curl_setopt($curlRequest, CURLOPT_POSTFIELDS, json_encode($postParams));
        }
        curl_setopt($curlRequest, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($curlRequest);
        
        curl_close($curlRequest);

        return $result;
    }

    //Generate signature for authtentification
    private function generateSignature($method, $url, $timestamp, array $postParams = [])
    {
        //Create signature
        if (!empty($postParams)) {
            $text = $method . $url . json_encode($postParams) . $timestamp;
        } else {
            $text = $method . $url . $timestamp;
        }
        //Return encoded version
        return sodium_bin2hex(sodium_crypto_sign_detached($text, sodium_hex2bin($this->privateKey)));
    }
}
