<?php
session_start();
require_once 'dbcon.php';

if(!empty($_SESSION["user_details"])) {
    $userId = $_SESSION["user_details"]["user_id"];
} else {
    ?>
    <script>
        alert("You must login first");
        window.location.href = "sign-in.php";
    </script>
    <?php
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $ratingId = $_POST["ratingId"];
    $comments = $_POST["comments"];
    $rating = $_POST["rating"];

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Update ratings table
        $updateRatingsQuery = "UPDATE ratings 
                               SET rate_ratings = :rating, 
                                   rate_feedback = :comments, 
                                   rate_status = 'completed' 
                               WHERE rate_id = :ratingId";
        $stmt = $conn->prepare($updateRatingsQuery);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comments', $comments);
        $stmt->bindParam(':ratingId', $ratingId);
        $stmt->execute();

        // Select ratings for further processing if needed
        $selectRatingQuery = "SELECT * FROM ratings WHERE rate_id = :ratingId";
        $stmtSelect = $conn->prepare($selectRatingQuery);
        $stmtSelect->bindParam(':ratingId', $ratingId);
        $stmtSelect->execute();
        $ratingData = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        // Update users table
        if ($ratingData) {
            $newRating = $ratingData['rate_ratings'];
            $partnerId = $ratingData['rate_partner_id'];
            $updateUserQuery = "UPDATE users 
                                SET user_rating = user_rating + :newRating, 
                                    user_rate_count = user_rate_count + 1 
                                WHERE user_id = :userId";
            $stmtUpdateUser = $conn->prepare($updateUserQuery);
            $stmtUpdateUser->bindParam(':newRating', $newRating);
            $stmtUpdateUser->bindParam(':userId', $partnerId);
            $stmtUpdateUser->execute();
        }

        // Commit transaction
        $conn->commit();

        // Echo success message
        header("Location: rating-history.php");
        exit;

    } catch (PDOException $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error updating rating: " . $e->getMessage();
    }
}
?>
