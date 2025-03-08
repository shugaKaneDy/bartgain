<?php
  require_once '../dbcon.php';
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User</title>
  <link rel="icon" href="../B.png">
  <!-- Top Links -->
  <?php
    include("layout/top-link.php");
  ?>

  <!-- Style -->
  <?php
    include("layout/style.php");
  ?>
</head>
<body>

  <!-- navbar -->
  <?php
    include("layout/navbar.php");
  ?>

  <!-- sidebar -->
  <?php
    include("layout/sidebar.php");
  ?>

  <!-- Main content -->
  <main class="main-content pt-3">
    <div class="container main-title mb-5">
      <h3 >Add User</h3>
      <div>
        <nav class="nav">
          <a class="nav-link text-dark text-sm" href="users.php">User Accounts</a>
          <a class="nav-link text-dark text-sm" href="admin-accounts.php">Admin Accounts</a>
          <a class="nav-link text-sm" href="add-user.php">Add User</a>
        </nav>
      </div>
      <div class="boreder rounded shadow p-5">
        <div class="row justify-content-center">
          <div class="col-12 col-md-6 border rounded py-3 px-3">
            <form id="addUserForm" action="add-user-account.php" method="POST">
              <p class="text-center">Add User</p>
              <!-- Role ID -->
              <div class="mb-3">
                <label for="roleId" class="form-label">Role ID</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="roleId" id="roleId1" value="1" required>
                  <label class="form-check-label" for="roleId1">1</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="roleId" id="roleId2" value="2" required>
                  <label class="form-check-label" for="roleId2">2</label>
                </div>
              </div>
              <!-- Fullname -->
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname" required>
                <label for="fullname">Fullname</label>
              </div>
              <!-- Birthdate -->
              <div class="form-floating mb-3">
                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                <label for="birthdate">Birthdate</label>
              </div>
              <!-- Email -->
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
              </div>
              <!-- Password -->
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
              </div>
              <!-- Email Verification -->
              <div class="mb-3">
                <label for="emailVerification" class="form-label">Email Verification</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="emailVerification" id="emailVerification0" value="0" required>
                  <label class="form-check-label" for="emailVerification0">0</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="emailVerification" id="emailVerification1" value="1" required>
                  <label class="form-check-label" for="emailVerification1">1</label>
                </div>
              </div>
              <!-- Verified -->
              <div class="mb-3">
                <label for="verified" class="form-label">Verified</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="verified" id="verifiedY" value="Y" required>
                  <label class="form-check-label" for="verifiedY">Y</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="verified" id="verifiedN" value="N" required>
                  <label class="form-check-label" for="verifiedN">N</label>
                </div>
              </div>
              <button id="saveBtn" type="button" class="btn btn-primary w-100 mt-3">Add User</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Bottom Links -->
  <?php
    include("layout/bottom-link.php");
  ?>


<script src="../js/plugins/sweetalert2/swal.js"></script>
<script>
  $(document).ready( function () {
    $('#myTable'). DataTable({
      ordering: false
    });

    $('#saveBtn').click(function () {
      // Show SweetAlert confirmation dialog
      Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to add this user. Proceed?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, add user',
        cancelButtonText: 'No, cancel'
      }).then((result) => {
        // If user confirms, submit the form
        if (result.isConfirmed) {
          $('#addUserForm').submit(); // Submit the form
        }
      });
    });
  });

  
  
  

  
</script>
  
</body>
</html>