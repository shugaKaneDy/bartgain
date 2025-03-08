<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.paymongo.com/v1/links",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'data' => [
        'attributes' => [
                'amount' => 100000,
                'description' => 'boost',
                'remarks' => '5'
        ]
    ]
  ]),
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Basic c2tfdGVzdF9YR0Q3RnlrRWlDeENBNTRpVW8yQzNGNWY6",
    "content-type: application/json"
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

  $paymentId = $responseArray['data']['id'];
  $paymentAmount = $responseArray['data']['attributes']['amount'] / 100;
  $paymentType = $responseArray['data']['attributes']['description'];
  $paymentNum = $responseArray['data']['attributes']['remarks'];
  $paymentStatus = $responseArray['data']['attributes']['status'];
  $paymentRefLink = $responseArray['data']['attributes']['checkout_url'];
  $paymentRefNum = $responseArray['data']['attributes']['reference_number'];

  echo $paymentId;
  echo "<br/>";
  echo $paymentAmount;
  echo "<br/>";
  echo $paymentType;
  echo "<br/>";
  echo $paymentNum;
  echo "<br/>";
  echo $paymentStatus;
  echo "<br/>";
  echo $paymentRefLink;
  echo "<br/>";
  echo $paymentRefNum;
  echo "<br/>";

  
  // Print the array in a readable format
  echo '<pre>';
  print_r($responseArray);
  echo '</pre>';
}