<?php
  if($_SESSION["user_details"]["verified"] != "Y") {
    ?>
      <script>
        alert("You don't have permission to access this page. Verify your account first");
        window.location.href = "verification.php";
      </script>
    <?php
    exit;
  }
?>