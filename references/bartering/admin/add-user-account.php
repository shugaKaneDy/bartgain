<?php
// add-user-account.php

// Include your database connection file
require_once '../dbcon.php';

// Start session if not already started
session_start();

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $roleId = $_POST['roleId'];
    $fullname = htmlspecialchars($_POST['fullname']);
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $emailVerification = $_POST['emailVerification'];
    $verified = $_POST['verified'];

    $thisDate = date('Y-m-d H:i:s');

    // Validate and prepare the data (not shown here for brevity, should be done to prevent SQL injection)

    // Insert data into database using PDO prepared statements
    $sql = "INSERT INTO users (role_id, fullname, birth_date, email, password, email_verification, verified, created_at, updated_at) 
            VALUES (:role_id, :fullname, :birthdate, :email, :password, :email_verification, :verified, :createdAt, :updatedAt)";
    
    try {
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':role_id', $roleId);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->bindParam(':email_verification', $emailVerification);
        $stmt->bindParam(':verified', $verified);
        $stmt->bindParam(':createdAt', $thisDate);
        $stmt->bindParam(':updatedAt', $thisDate);
        
        // Execute the statement
        $stmt->execute();
        
        // Set success message and redirect
        $_SESSION['message'] = [
            'status' => 'success',
            'title' => 'User added successfully!'
        ];

        if($roleId == 1) {
          header('Location: users.php');
        } else {
          header('Location: admin-accounts.php');
        }

        exit();
        
    } catch (PDOException $e) {
        // Set error message if insertion fails
        $_SESSION['message'] = [
            'status' => 'error',
            'title' => 'Error!',
            'message' => 'Failed to add user: ' . $e->getMessage()
        ];
        header('Location: add-user.php');
        exit();
    }
} else {
    // Redirect if not a POST request
    header('Location: add-user.php');
    exit();
}
?>
