<?php

namespace App\Service;

class RequestService
{
    private $publicKey = "p0bl1ck3y";
    private $privateKey = "pr1v4tk34";
    private $root = "https://root/market/api/url";

    public function createTargetRequest($targetBody) {
        //Every request to this api need method, url and mb postParams, like targetBody, that was created in ItemService before 
        $method = 'POST';
        $url = '/target/create';
        return $this->createRequest($method, $url, $targetBody);
    }

    public function getOffersByTitleRequest($title, $limit = 100) {
        //Transform title to url
        $title = urlencode($title);
        $method = 'GET';
        $url = '/offers-by-title?Title=' . $title . '&Limit=' . $limit;
        return $this->createRequest($method, $url);
    }

    public function createRequest($method, $url, $postParams = []) {
        //Create timestamp to sign request
        $now = new \DateTime();
        $timestamp = $now->getTimestamp();
        $headers = [
            'X-Api-Key:' . $this->publicKey,
            'X-Sign-Date:' . $timestamp,
            'X-Request-Sign:' . $this->generateSignature($method, $url, $timestamp, $postParams),
            'Content-Type:' . 'application/json'
        ];
        
        //Make an request
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

        //Return result for debuging
        return $result;
    }

    private function generateSignature($method, $url, $timestamp, array $postParams = [])
    {
        //Every request to this api has to be signed
        if (!empty($postParams)) {
            $sign = $method . $url . json_encode($postParams) . $timestamp;
        } else {
            $sign = $method . $url . $timestamp;
        }
        //And sign has to be encripted with private key
        return sodium_bin2hex(sodium_crypto_sign_detached($sign, sodium_hex2bin($this->privateKey)));
    }
}
