<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  $refNum = $_POST["refNum"];

  $paymentInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM payments
    WHERE payment_ref_num = :refNum",
    [
      ":refNum" => $refNum,
    ]
  );

  if($paymentInfo['payment_user_id'] != $_SESSION['user_details']['user_id']) {
    errorResponse("This Payment is not yours!");
  }

  if($paymentInfo['payment_status'] == 'paid') {
    errorResponse("This transaction is already done.");
  }

  $curl = curl_init();

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

  $res = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    // Decode JSON response to an associative array
    $responseArray = json_decode($res, true);

    $status = $responseArray['data'][0]['attributes']['status'];

    if($status == 'paid') {
      $paymentPaidAt = $responseArray['data'][0]['attributes']['payments'][0]['data']['attributes']['paid_at'];
      $paymentPaidAt = date("Y-m-d H:i:s", $paymentPaidAt);
      $paymentPaidType = $responseArray['data'][0]['attributes']['payments'][0]['data']['attributes']['source']['type'];
      $paymentRemarks = $responseArray['data'][0]['attributes']['remarks'];
    } else {
      exit();
    }
    
  }

  // print_r($_POST);
  // exit();


  try {

    $pdo->beginTransaction();
    // UPDATE PAYMENT
    updateQuery(
      $pdo,
      "UPDATE payments SET
      payment_status = :paymentStatus,
      payment_paid_at = :paymentPiadAt,
      payment_paid_type = :paymentPaidType
      WHERE payment_ref_num = :refNum",
      [
        ":paymentStatus" => $status,
        ":paymentPiadAt" => $paymentPaidAt,
        ":paymentPaidType" => $paymentPaidType,
        ":refNum" => $refNum,
      ]
    );

    // UPDATE ITEM || USER
    if(empty($paymentInfo['payment_item_id'])) {

      updateQuery(
        $pdo,
        "UPDATE users SET
        user_is_prem = :userIsPrem,
        user_prem_expire = :userPremExpire
        WHERE user_id = :userId",
        [
          ":userIsPrem" => "Yes",
          ":userPremExpire" => date('Y-m-d H:i:s', strtotime($paymentPaidAt . ' + '. $paymentRemarks .' months')),
          ":userId" => $_SESSION['user_details']['user_id'],
        ]
      );

      $_SESSION['user_details']['user_is_prem'] = "Yes";

    } else {
      updateQuery(
        $pdo,
        "UPDATE items SET
        item_boosted = :itemBoosted,
        item_boost_expire = :itemBoostExpire
        WHERE item_id = :itemId",
        [
          ":itemBoosted" => "Yes",
          ":itemBoostExpire" => date('Y-m-d H:i:s', strtotime($paymentPaidAt . ' + '. $paymentRemarks .' days')),
          ":itemId" => $paymentInfo['payment_item_id'],
        ]
      );
    }

    $pdo->commit();

    successResponse("Payment Successful");

  } catch(PDOException $e) {

    $pdo->rollBack();
    // errorResponse("Failed: " . $e->getMessage());
  }

}

?>