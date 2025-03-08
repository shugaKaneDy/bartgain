<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  // print_r($_POST);

  $option = htmlspecialchars($_POST['option']);

  $cost = 0;

  if($option == 1) {
    $cost = 300;
  } else if($option == 3) {
    $cost = 855;
  } else if($option == 12) {
    $cost = 3060;
  } else {
    errorResponse("Invalid Input");
  }

  $cost *= 100;

  // cURL

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
                  'amount' => $cost,
                  'description' => 'premium',
                  'remarks' => $option
          ]
      ]
    ]),
    CURLOPT_HTTPHEADER => [
      "accept: application/json",
      "authorization: Basic c2tfdGVzdF9YR0Q3RnlrRWlDeENBNTRpVW8yQzNGNWY6",
      "content-type: application/json"
    ],
  ]);

  $res = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    errorResponse("cURL Error #:" . $err);
  } else {
    // Decode JSON response to an associative array
    $responseArray = json_decode($res, true);

    $paymentId = $responseArray['data']['id'];
    $paymentAmount = $responseArray['data']['attributes']['amount'] / 100;
    $paymentType = $responseArray['data']['attributes']['description'];
    $paymentNum = $responseArray['data']['attributes']['remarks'];
    $paymentStatus = $responseArray['data']['attributes']['status'];
    $paymentRefLink = $responseArray['data']['attributes']['checkout_url'];
    $paymentRefNum = $responseArray['data']['attributes']['reference_number'];
  }



  try {

    $pdo->beginTransaction();

    insertQuery(
      $pdo,
      "INSERT INTO payments
      (
        payment_user_id,
        payment_link_id,
        payment_ref_link,
        payment_ref_num,
        payment_amount,
        payment_type,
        payment_num,
        payment_created_at
      ) VALUES
      (
        :paymentUserId,
        :paymentLinkId,
        :paymentRefLink,
        :paymentRefNum,
        :paymentAmount,
        :paymentType,
        :paymentNum,
        :paymentCreatedAt
      )",
      [
          ':paymentUserId' => $_SESSION['user_details']['user_id'],
          ':paymentLinkId' => $paymentId,
          ':paymentRefLink' => $paymentRefLink,
          ':paymentRefNum' => $paymentRefNum,
          ':paymentAmount' => $paymentAmount,
          ':paymentType' => $paymentType,
          ':paymentNum' => $paymentNum,
          ':paymentCreatedAt' => $currentTime
      ]
    );

    $pdo->commit();

    // successResponse("Pay your boosting.");

    $respond = [
      'status' => 'success',
      'title' => "Pay your premium.",
      'ref_num' => $paymentRefNum
    ];
    echo json_encode($respond);
    exit;


  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }

}

?>