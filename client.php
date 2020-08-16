<?php

require_once 'HttpClient.php';
require_once 'DateFormatConversion.php';


$httpClient =  new HttpClient();
$httpClient->setBaseUrl('https://www.coredna.com');
$payload = '{
  "name": "Yingping Liu",
  "email": "lypk21@gmail.com",
  "url": "https://github.com/lypk21/http-client-app"
}';

try {
    //confuse: can't get token, event send OPTIONS request, the doc said: All requests require authentication with an API key unique to your centre. Contact your partner agency if you do not have an API key.
    $result = $httpClient->sendRequest('/assessment-endpoint.php');
    $result = DateFormatConversion::jsonToArray($result);
    $httpClient->setApiToken($result['apiKey']);
    $submitResult = $httpClient->sendRequest('/assessment-endpoint.php',DateFormatConversion::arrayToJson($payload));
    var_dump(DateFormatConversion::arrayToJson($submitResult));
} catch (Exception $e) {
    echo $e->getMessage();
}





