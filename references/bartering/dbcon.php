<?php
date_default_timezone_set('Asia/Manila');
$servername = "localhost";
$username = "root";
$password = "";
$database = "bart_gain";

// $currentTimestamp = date('Y-m-d H:i:s');
// echo "Current Timestamp (Manila Time): " . $currentTimestamp;

try {

  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected Successfully";

} catch (PDOException $e) {
  die("Connection Failed" . $e->getMessage());
}

function notifyUserOfNewOffer($userId, $offerId) {
  global $conn;
  $message = "You have received a new offer on your item. Offer ID: " . $offerId;
  $type = "new-offer";
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type) VALUES (:user_id, :message, :type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type]);
}

function notifyUserOfOfferAcceptance($userId, $offerId) {
  global $conn;
  $message = "Your offer with ID " . $offerId . " has been accepted.";
  $type = "offer-accepted";
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type) VALUES (:user_id, :message, :type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type]);
}

function notifyUserOfOfferRejection($userId, $offerId) {
  global $conn;
  $message = "Your offer with ID " . $offerId . " has been rejected.";
  $type = "offer-rejected";
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type) VALUES (:user_id, :message, :type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type]);
}

function notifyUserOfOfferCancellation($userId, $offerId) {
  global $conn;
  $message = "Offer with ID " . $offerId . " has been cancelled.";
  $type = "offer-cancelled";
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type) VALUES (:user_id, :message, :type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type]);
}




function notifyUserOfMeetUp($userId, $meetUpDetails) {
  global $conn;
  $message = "Your meet-up is confirmed: " . $meetUpDetails;
  $type = "meet-up";
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type) VALUES (:user_id, :message, :type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type]);
}

function notifyUserOfItemReceived($userId) {
  global $conn;
  $message = "Your item has been marked as received.";
  $type = "item-received";
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type) VALUES (:user_id, :message, :type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type]);
}

function notifyUserOfUserRating($userId, $rating) {
  global $conn;
  $message = "You have received a new rating: " . $rating . " stars.";
  $type = "user-rating";
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type) VALUES (:user_id, :message, :type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type]);
}

function getUserNotifications($userId) {
  global $conn;
  $sql = "SELECT * FROM user_notifications WHERE user_notification_user_id = :user_id ORDER BY user_notification_created_at DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId]);
  return $stmt->fetchAll();
}

function markNotificationAsRead($notificationId) {
  global $conn;
  $sql = "UPDATE user_notifications SET user_notification_is_read = 1 WHERE user_notification_id = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':id' => $notificationId]);
}

function notifyUserOfVerificationRejection($userId, $verificationId, $reason) {
  global $conn;
  $message = "Your verification request with ID " . $verificationId . " has been rejected. Reason: " . $reason;
  $type = "verification-rejected";
  $currentDate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type, 	user_notification_created_at) VALUES (:user_id, :message, :type, :currentDate)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type, ':currentDate' => $currentDate]);
}

function notifyUserOfVerificationAcceptance($userId, $verificationId) {
  global $conn;
  $message = "Your verification request with ID " . $verificationId . " has been accepted.";
  $type = "verification-accepted";
  $currentDate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO user_notifications (user_notification_user_id, user_notification_message, user_notification_type, user_notification_created_at) VALUES (:user_id, :message, :type, :currentDate)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':user_id' => $userId, ':message' => $message, ':type' => $type, ':currentDate' => $currentDate]);
}