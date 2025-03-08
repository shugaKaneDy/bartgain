<!-- navbar -->
<?php
  if($_SESSION["user_details"]["role_id"] == 1) {
    ?>
      <script>
        alert("You are not elligable to access this page");
        window.location.href = "../index.php";
      </script>
    <?php
    exit;
  }
?>
<nav class="navbar bg-white shadow-sm">
  <div class="container-fluid">
    <div class="d-flex gap-2">
      <button id="forToggle" class="btn btn-white d-md-none"><i class="bi bi-list"></i></button>
      <img src="../B.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
      <a class="navbar-brand fw-bold text-success">BartGain</a>
    </div>
    <div class="pe-3">
      <button class="btn btn-white dropdown-toggle">
          Hello, Admin
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="../index.php">Home</a></li>
        <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>