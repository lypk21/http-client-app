<?php


class HttpClient
{
    private  $baseUrl;
    private $apiToken;

    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function getApiToken() {
        return $this->apiToken;
    }

    public function setApiToken($apiToken) {
        $this->apiToken = $apiToken;
    }

    public function sendRequest($subUrl, $method = 'get', $payloads = [], $header = "") {
       $url = $this->getBaseUrl() ? $this->getBaseUrl().$subUrl : $subUrl;
       if(empty($header)) {
           $header = [
               'Accept: application/json'
           ];
       }
       if($this->getApiToken()) $header = [
           'Authorization: Bearer '.$this->getApiToken()
       ];

       if('get' === strtolower($method)) return $this->sendRequestByGet($url, $payloads, $header);
       if('post' === strtolower($method)) return $this->sendRequestByPost($url, $payloads, $header);

        throw  new Exception('invalid method param');
    }

    private function sendRequestByGet($url, $payloads = [],$header = []) {
        if(count($payloads) > 0) {
            $url += '?';
            foreach ($payloads as $ind => $val) {
                if(!empty($val)) {
                    $url += $ind.'='.$val.'&';
                }
            }
            $url = rtrim($url,'&');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->handleRequestCode($httpCode);
        $data = curl_exec($ch);
        if(curl_errno($ch)){
            throw new Exception('Curl error: '.curl_error($ch));
        }
        curl_close($ch);

        return $data;
    }

    private function sendRequestByPost($url, $payloads = [],$header = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payloads);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->handleRequestCode($httpCode);
        $data = curl_exec($ch);
        if(curl_errno($ch)){
            throw new Exception('Curl error: '.curl_error($ch));
        }
        curl_close($ch);
        return $data;
    }

    private function handleRequestCode($httpCode) {
        switch ($httpCode) {
            case '400':
                throw new Exception('Bad Request');
            case '401':
                throw new Exception('Unauthorized');
            case '404':
                throw new Exception('Not Found');
            case '405':
                throw new Exception('Method Not Allowed');
            case '500':
                throw new Exception('Internal Server Error');
        }
    }
}
