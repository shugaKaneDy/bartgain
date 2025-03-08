<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  // Sanitize input values
  $contact = filter_var($_POST['contact'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $emergencyContact = filter_var($_POST['emergencyContact'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // If sanitized input is empty, use session values
  $contact = !empty($contact) ? $contact : $_SESSION['user_details']['user_contact'];
  $emergencyContact = !empty($emergencyContact) ? $emergencyContact : $_SESSION['user_details']['user_contact_emergency'];
  $address = !empty($address) ? $address : $_SESSION['user_details']['address'];
  
  try {
    
    $pdo->beginTransaction();
    updateQuery(
      $pdo,
      "UPDATE users SET
      user_contact = :userContact,
      user_contact_emergency = :userContactEmergency,
      address = :userAddress
      WHERE user_id = :userId",
      [
        ":userContact" => $contact,
        ":userContactEmergency" => $emergencyContact,
        ":userAddress" => $address,
        ":userId" => $_SESSION['user_details']['user_id'],
      ]
    );
    
    $pdo->commit();
    $_SESSION['user_details']['user_contact'] = $contact;
    $_SESSION['user_details']['user_contact_emergency'] = $emergencyContact;
    $_SESSION['user_details']['address'] = $address;
    successResponse("Profile information updated successfully");


  } catch(PDOException $e) {

    $pdo->rollBack();
    errorResponse("Failed: " . $e->getMessage());
  }



  
  
  

}