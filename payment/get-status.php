<?php

$curl = curl_init();

$refNum = "1ZsjyNh";

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.paymongo.com/v1/links?reference_number=" . $refNum,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Basic c2tfdGVzdF9YR0Q3RnlrRWlDeENBNTRpVW8yQzNGNWY6"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  // Decode JSON response to an associative array
  $responseArray = json_decode($response, true);

  $paymentPaidAt = $responseArray['data'][0]['attributes']['payments'][0]['data']['attributes']['paid_at'];
  
  echo $responseArray['data'][0]['attributes']['checkout_url'];
  echo "<br/>";
  echo $responseArray['data'][0]['attributes']['status'];
  echo "<br/>";
  echo $responseArray['data'][0]['attributes']['remarks'];
  echo "<br/>";
  // echo date("Y-m-d H:i:s", 1730447902);
  echo date("Y-m-d H:i:s", $paymentPaidAt);
  echo "<br/>";
  echo $responseArray['data'][0]['attributes']['payments'][0]['data']['attributes']['source']['type'];


  // Print the array in a readable format
  echo '<pre>';
  print_r($responseArray);
  echo '</pre>';
}