<?php

// This is the URL you want to shorten
$longUrl = 'http://www.bizalimtalk.kr/post/43'; // 여기에 POST로 받은 변수를 넣는다.

// Get API key from : http://code.google.com/apis/console/
$apiKey = 'AIzaSyB3XU-UXVXQ-iXflDjQpbqmQFeCs3P7_94'; //API를 활성화 하면서 Browser Key를 발급받아 여기에 적어넣는다.

$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
$jsonData = json_encode($postData);

$curlObj = curl_init();

curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlObj, CURLOPT_HEADER, 0);
curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
curl_setopt($curlObj, CURLOPT_POST, 1);
curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

$response = curl_exec($curlObj);


echo $response;
// Change the response json string to object
$json = json_decode($response);


//echo $json. "<p>";


curl_close($curlObj);

echo 'Shortened URL is: '.$json->id; // $json->id가 우리가 원하는 짧은 주소. 
?>