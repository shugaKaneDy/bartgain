<?php

if(isset($_SESSION['user_details'])) {
  if($_SESSION['user_details']['role_id'] != 2) {
    header("Location: ../itemplace.php");
  }
} else {
  header("Location: ../signin.php");
}
?>