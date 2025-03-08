<?php
session_start();
require_once 'dbcon.php';

if (!empty($_SESSION["user_details"])) {
    $userId = $_SESSION["user_details"]["user_id"];
} else {
    echo "<script>
            alert('You must login first');
            window.location.href = 'sign-in.php';
          </script>";
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profilePicture"])) {
    $targetDir = "profile-picture/";
    $fileType = pathinfo($_FILES["profilePicture"]["name"], PATHINFO_EXTENSION);
    $fileName = uniqid() . "." . $fileType;
    $targetFilePath = $targetDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>
                alert('File is not an image.');
                window.location.href = 'profile-edit.php';
              </script>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profilePicture"]["size"] > 5000000) { // 5MB max
        echo "<script>
                alert('Sorry, your file is too large.');
                window.location.href = 'profile-edit.php';
              </script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>
                alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
                window.location.href = 'profile-edit.php';
              </script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>
                alert('Sorry, your file was not uploaded.');
                window.location.href = 'profile-edit.php';
              </script>";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFilePath)) {
            // Check if there is an existing profile picture
            $query = "SELECT profile_picture FROM users WHERE user_id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $currentProfilePicture = $stmt->fetchColumn();

            // Delete the existing profile picture if it's not null
            if ($currentProfilePicture && file_exists($targetDir . $currentProfilePicture)) {
                unlink($targetDir . $currentProfilePicture);
            }

            // Update user's profile picture in the database
            $query = "UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':profile_picture', $fileName, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Profile picture updated successfully.');
                        window.location.href = 'profile-edit.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error updating profile picture in the database.');
                        window.location.href = 'profile-edit.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Sorry, there was an error uploading your file.');
                    window.location.href = 'profile-edit.php';
                  </script>";
        }
    }
} else {
    echo "<script>
            alert('No file was uploaded.');
            window.location.href = 'profile-edit.php';
          </script>";
}
?>
