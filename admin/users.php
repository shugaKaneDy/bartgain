<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  $totalMembers = selectQueryFetch(
    $pdo,
    "SELECT COUNT(user_id) as total_user FROM users
    WHERE role_id = :roleId",
    [
      ":roleId" => 1
    ]
  );

  $totalAdmin = selectQueryFetch(
    $pdo,
    "SELECT COUNT(user_id) as total_admin FROM users
    WHERE role_id = :roleId",
    [
      ":roleId" => 2
    ]
  );

  $users = selectQuery(
    $pdo,
    "SELECT * FROM users
    ORDER BY user_id DESC",
    []
  );


  // print_r($salesOvertime);
  // exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Users</title>

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
            <h1>USERS</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <!-- Overview -->
        <div class="row">
          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Members</span>
                <span class="info-box-number"><?= $totalMembers['total_user'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-lock"></i></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Admins</span>
                <span class="info-box-number"><?= $totalAdmin['total_admin'] ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- ./ Overview -->

        <!-- Tables -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button class="btn btn-sm btn-success"
                data-toggle="modal" data-target=".bd-example-modal-xl"
                >
                  Add User
                </button>
              </div>
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fullname</th>
                      <th>Status</th>
                      <th>Role</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($users as $user):?>
                      <tr>
                        <td><?= $user['user_random_id'] ?></td>
                        <td><?= $user['fullname'] ?></td>
                        <td>
                          <?php if($user['user_status'] == "active"): ?>
                            <span class="badge badge-success">active</span>
                          <?php else: ?>
                            <span class="badge badge-danger">inactive</span>
                          <?php endif ?>
                        </td>
                        <td>
                          <?php if($user['role_id'] == 1): ?>
                            <span class="badge badge-success">user</span>
                          <?php else: ?>
                            <span class="badge badge-warning">admin</span>
                          <?php endif ?>
                        </td>
                        <td>
                          <button class="viewProfile btn btn-sm btn-info"
                          data-toggle="modal" data-target="#viewProfileModal"
                          data-view-randId="<?= $user['user_random_id'] ?>"
                          data-view-email="<?= $user['email'] ?>"
                          data-view-fullname="<?= $user['fullname'] ?>"
                          data-view-rating="<?= $user['user_rating'] ?>"
                          data-view-rated="<?= $user['user_rate_count'] ?>"
                          data-view-userContact="<?= $user['user_contact'] ?>"
                          data-view-userEmergencyContact="<?= $user['user_contact_emergency'] ?>"
                          data-view-address="<?= $user['address'] ?>"
                          data-view-currentLocation="<?= $user['current_location'] ?>"
                          data-view-userStatus="<?= $user['user_status'] ?>"
                          data-view-userCreatedAt="<?= date("M d, Y", strtotime($user['user_created_at']))  ?>"
                          >view
                          </button>
                          <a href="user-edit.php?user_id=<?= $user['user_random_id'] ?>" class="btn btn-sm btn-warning">edit</a>
                        </td>
                      </tr>
                    <?php endforeach?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ./Tables -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Add User Modal -->
  <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addUserForm">
            <input type="hidden" name="userAddLat" id="userAddLat" >
            <input type="hidden" name="userAddLng" id="userAddLng" >
            <input type="hidden" name="userAddCurrentLoc" id="userAddCurrentLoc" >
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="fullname">Fullname</label>
                  <input type="text" class="form-control" name="fullname" id="fullname">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" name="email" id="email">
                </div>
                <div class="form-group">
                  <label for="pwd">Password</label>
                  <input type="password" class="form-control" name="pwd" id="pwd">
                </div>
                <div class="form-group">
                  <label for="cpwd">Confirm Password</label>
                  <input type="password" class="form-control" name="cpwd" id="cpwd">
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" class="form-control" name="address" id="address">
                </div>
                <div class="form-group">
                  <label for="contact">Contact</label>
                  <input type="text" class="form-control" name="contact" id="contact">
                </div>
                <div class="form-group">
                  <label for="birthDate">Birth Date</label>
                  <input type="date" class="form-control" name="birthDate" id="birthDate">
                </div>
                <div class="form-group">
                  <p class="font-weight-bold">Role?</p>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role1" value="1" checked>
                    <label class="form-check-label" for="role1">User</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role2" value="2">
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
                    <input class="form-check-input" type="radio" name="verified" id="verified2" value="N">
                    <label class="form-check-label" for="verified2">No</label>
                  </div>
                </div>
                <div class="form-group">
                  <p class="font-weight-bold">Email Verified?</p>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="emailVerified" id="emailV1" value="Yes" checked>
                    <label class="form-check-label" for="emailV1">Yes</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="emailVerified" id="emailV2" value="No">
                    <label class="form-check-label" for="emailV2">No</label>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="addUserBtn" type="button" class="btn btn-success">Add User</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /.Add User Modal -->

  <!-- View Profile Modal -->
  <div class="modal fade" id="viewProfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Widget: user widget style 1 -->
          <div class="card card-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-success">
              <h3 class="widget-user-username viewFullname">Kane Gerickson Tagay</h3>
              <h5 class="widget-user-desc">ID: <span class="viewRandomId"></span> </h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle elevation-2" src="../profile-uploads/default.jpg" alt="User Avatar">
            </div>
            <p>
            <p class="text-center pt-4 font-weight-bold h4 m-0"><span class="viewRating">5</span> <i class="text-warning fas fa-star"></i></p>
            <p class="text-muted text-center"><span class="viewRated">1</span> persons rated</p>
            <div class="px-3 pb-3">
              <div class="row pt-3 px-2">
                <div class="col-1 text-center">
                  <p class="h5">
                    <i class="fas fa-envelope"></i>
                  </p>
                </div>
                <div class="col-11 text-start">
                  <span class="viewEmail text-muted h6 ml-2"></span>
                </div>
                <div class="col-1 text-center">
                  <p class="h5">
                    <i class="fas fa-home"></i>
                  </p>
                </div>
                <div class="col-11 text-start">
                  <span class="viewAddress text-muted h6 ml-2"></span>
                </div>
                <div class="col-1 text-center">
                  <p class="h5">
                    <i class="fas fa-map-marker-alt"></i>
                  </p>
                </div>
                <div class="col-11 text-start">
                  <span class="viewLocation text-muted h6 ml-2"></span>
                </div>
                <div class="col-1 text-center">
                  <p class="h5">
                    <i class="fas fa-phone-alt"></i>
                  </p>
                </div>
                <div class="col-11 text-start">
                  <span class="viewContact text-muted h6 ml-2"></span>
                </div>
                <div class="col-1 text-center">
                  <p class="h5">
                    <i class="fas fa-phone-volume"></i>
                  </p>
                </div>
                <div class="col-11 text-start">
                  <span class="viewEmergencyContact text-muted h6 ml-2"></span>
                </div>
              </div>
            </div>
            <div class="card-footer pt-2">
              <div class="d-flex flex-column justify-content-end align-items-end">
                <p class="m-0">Status: <span class="viewStatus text-muted"></span></p>
                <p class="m-0">Join at: <span class="viewCreatedAt text-muted"></span></p>
              </div>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /.View Profile Modal -->

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

    // $("#viewProfileModal").modal('show');

    $('.viewProfile').on('click', function() {
      // Retrieve data attributes
      const randId = $(this).data('view-randid');
      const email = $(this).data('view-email');
      const fullname = $(this).data('view-fullname');
      const rating = $(this).data('view-rating');
      const rated = $(this).data('view-rated');
      const userContact = $(this).data('view-usercontact');
      const userEmergencyContact = $(this).data('view-useremergencycontact');
      const address = $(this).data('view-address');
      const currentLocation = $(this).data('view-currentlocation');
      const userStatus = $(this).data('view-userstatus');
      const userCreatedAt = $(this).data('view-usercreatedat');

      // Display the data in the modal or console (for debugging)
      console.log("Full Name:", fullname);
      console.log("Random Id:", randId);
      console.log("Rating:", rating);
      console.log("Rated by:", rated);
      console.log("User Contact:", userContact);
      console.log("Emergency Contact:", userEmergencyContact);
      console.log("Address:", address);
      console.log("Current Location:", currentLocation);
      console.log("User Status:", userStatus);
      console.log("Created At:", userCreatedAt);

      $('.viewFullname').html(fullname);
      $('.viewRandomId').html(randId);
      if(rated > 0) {
        totalRate = rating/rated;
        $('.viewRating').html(totalRate);
        $('.viewRated').html(rated);
      } else {
        $('.viewRating').html(rated);
        $('.viewRated').html(rated);
      }

      $('.viewEmail').html(email);
      $('.viewAddress').html(address);
      $('.viewLocation').html(currentLocation);
      $('.viewContact').html(userContact);
      $('.viewEmergencyContact').html(userEmergencyContact);
      $('.viewStatus').html(userStatus);
      $('.viewCreatedAt').html(userCreatedAt);

    });

    $('#example2').DataTable({
      "paging": true,
      "ordering": false,
      "responsive": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

    $(document).on('click', "#addUserBtn", function(e) {
      e.preventDefault();
      
      Swal.fire({
        title: "Are you sure?",
        text: "You want to add this user?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = $('#addUserForm').serializeArray();
          $.ajax({
            method: 'POST',
            url: "includes/ajax/add-user.inc.php",
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

  });
</script>

</body>
</html>
