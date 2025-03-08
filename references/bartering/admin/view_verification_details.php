<?php
require_once '../dbcon.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verificationId = $_POST['verificationId'];
    $query = "SELECT * FROM verifications INNER JOIN users ON verifications.user_id = users.user_id WHERE verification_id = :verificationId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':verificationId', $verificationId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        ?>
        <div class="mb-3">
          <p class="m-0">Verification ID: <?= $result['verification_id'] ?></p>
          <p class="m-0">Fullname: <?= $result['fullname'] ?></p>
          <p class="m-0">Application Date: <?= $result['verification_created_at'] ?></p>
          <p class="m-0">Birth Date: <?= $result['verification_birth_date'] ?></p>
          <p class="m-0">Status: <?= $result['verification_status'] ?></p>
          <p class="m-0">Reject Reasion: <?= $result['verification_reject_reason'] ?></p>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 mb-3">
            <p class="m-0">Your captured image:</p>
            <img src="../<?= $result['capture_image_path'] ?>" alt="Captured Image" style="max-width: 300px; max-height: 150px;">
          </div>
          <div class="col-12 col-md-6 mb-3">
            <p class="m-0">Your Valid ID:</p>
            <img src="../<?= $result['id_picture_path'] ?>" alt="Valid ID" style="max-width: 300px; max-height: 150px;">
          </div>
        </div>
        <div class="float-end">
          <button class="acceptVerificationBtn btn btn-sm btn-primary" value="<?= $result['verification_id'] ?>">Accept</button>
          <button class="rejectVerificationBtn btn btn-sm btn-danger" value="<?= $result['verification_id'] ?>">Reject</button>
        </div>
        <?php
    } else {
        echo "No verification details found.";
    }
} else {
    echo "Invalid request.";
}
?>
