<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';


  // print_r($salesOvertime);
  // exit;

  if(!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header('location: users.php');
  }

  $userId = $_GET['user_id'];
  
  $userInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM users WHERE user_random_id = :userId",
    [
      ":userId" => $userId
    ]
  );

  if(empty($userInfo)) {
    header('location: users.php');
  }

  // print_r($userInfo);
  // exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Edit User</title>

  <?php
    include('layouts/top-link.php');
  ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake rounded" src="../assets/logo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php
    include('layouts/nav.php');
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
    include('layouts/aside.php');
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <h1>EDIT USER</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <a href="users.php" class="btn btn-sm btn-light">
              <i class="fas fa-arrow-left"></i> Back
            </a>
          </div>
          <div class="card-body">
            <form id="editForm">
              <input type="hidden" name="userId" value="<?= $userId ?>">
              <div class="row mb-3">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="fullname">Fullname</label>
                    <input type="text" class="form-control" name="fullname" id="fullname" value="<?= $userInfo['fullname'] ?>">
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= $userInfo['email'] ?>">
                  </div>
                  <div class="form-group">
                    <label for="pwd">Password <span class="text-muted font-weight-normal">(optional)</span></label>
                    <input type="password" class="form-control" name="pwd" id="pwd">
                  </div>
                  <div class="form-group">
                    <label for="cpwd">Confirm Password <span class="text-muted font-weight-normal">(optional)</span></label>
                    <input type="password" class="form-control" name="cpwd" id="cpwd">
                  </div>
                  <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?= $userInfo['address'] ?>">
                  </div>
                  <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" class="form-control" name="contact" id="contact" value="<?= $userInfo['user_contact'] ?>">
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="birthDate">Birth Date</label>
                    <input type="date" class="form-control" name="birthDate" id="birthDate" value="<?= $userInfo['birth_date'] ?>">
                  </div>
                  <div class="form-group">
                    <p class="font-weight-bold">Role?</p>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="role" id="role1" value="1" checked>
                      <label class="form-check-label" for="role1">User</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="role" id="role2" value="2" <?= $userInfo['role_id'] == 2 ? "checked" : "" ?>>
                      <label class="form-check-label" for="role2">Admin</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <p class="font-weight-bold">Verified?</p>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="verified" id="verified1" value="Y" checked>
                      <label class="form-check-label" for="verified1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="verified" id="verified2" value="N" <?= $userInfo['verified'] == 'N' ? "checked" : "" ?>>
                      <label class="form-check-label" for="verified2">No</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <p class="font-weight-bold">Email Verified?</p>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="emailVerified" id="emailV1" value="1" checked>
                      <label class="form-check-label" for="emailV1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="emailVerified" id="emailV2" value="0" <?= $userInfo['email_verification'] == 0 ? "checked" : "" ?>>
                      <label class="form-check-label" for="emailV2">No</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <p class="font-weight-bold">Status?</p>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" id="status1" value="active" checked>
                      <label class="form-check-label" for="emailV1">Active</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" id="status2" value="inactive" <?= $userInfo['user_status'] == "inactive" ? "checked" : "" ?>>
                      <label class="form-check-label" for="emailV2">Inactive</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-end">
                <button class="btn btn-success" id="editBtn" type="button">
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php
 include("layouts/bottom-link.php")
?>

<script>
  $(document).ready(function() {
    $(document).on('click', "#editBtn", function(e) {
      e.preventDefault();
      
      Swal.fire({
        title: "Are you sure?",
        text: "You want to edit this user?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = $('#editForm').serializeArray();
          $.ajax({
            method: 'POST',
            url: "includes/ajax/user-edit.inc.php",
            data: formData,
            dataType: "JSON",
          }).done(function (res) {

            if(res.status == 'error') {
              Swal.fire({
                icon: res.status,
                title: res.title,
                showConfirmButton: true
              });
            }

            if(res.status == 'success') {
              Swal.fire({
                icon: res.status,
                title: res.title,
                showConfirmButton: true
              }).then(result => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
            }

          })
        }
      });
    })

  })
</script>

</body>
</html>
