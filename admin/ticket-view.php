<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  if(isset($_GET['t_id'])) {
    if(!empty($_GET['t_id'])) {
      $tId = $_GET['t_id'];
      $ticketInfo = selectQueryFetch(
        $pdo,
        "SELECT * FROM tickets
        INNER JOIN users ON tickets.ticket_user_id = users.user_id
        WHERE tickets.ticket_random_id = :tId",
        [
          ":tId" => $tId,
        ]
      );

      if(empty($ticketInfo)) {
        header("location: tickets.php");
        exit;
      }
    } else {
      header("location: tickets.php");
      exit;
    }
  } else {
    header("location: tickets.php");
    exit;
  }

  $ticketResponses = selectQuery(
    $pdo,
    "SELECT * FROM ticket_responses
    WHERE t_response_ticket_id = :ticketId
    ORDER BY t_response_id DESC",
    [
      ":ticketId" => $ticketInfo['ticket_id']
    ]
  );

  // print_r($ticketResponses);
  // exit;


  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Ticket View</title>

  <?php
    include('layouts/top-link.php');
  ?>
  <link rel="stylesheet" href="css/verification.css">
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
            <h1>Ticket View</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        

        <!-- Tables Pending Verification -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between">
                  <div>
                    <a href="tickets.php" class="btn btn-sm btn-light">
                      <i class="fas fa-arrow-left"></i> Back
                    </a>
                  </div>
                  <div>
                    <button class="btn btn-sm btn-success"
                    data-toggle="modal" data-target="#exampleModal"
                    >
                      Ticket Details
                    </button>
                    <?php if($ticketInfo['ticket_status'] != "closed"): ?>
                      <button class="btn btn-sm btn-danger"
                      id="closedBtn"
                      >
                        Mark as closed
                      </button>
                    <?php endif ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <p class="h4 mb-3 font-weight-bold">
                  <?= $ticketInfo['ticket_subject'] ?>
                </p>

                <?php foreach($ticketResponses as $ticketResponse): ?>
                  <?php if($ticketResponse['t_response_type'] == "user"): ?>
                    <div class="p-2 border-top border-bottom mb-2">
                      <div class="d-flex gap-2">
                        <div class="mr-3">
                          <i class="fas fa-user-circle text-secondary" style="font-size: 32px"></i>
                        </div>
                        <div>
                          <p class="m-0"><?= $ticketInfo['fullname'] ?></p>
                          <p class="text-muted"><?= date("M d, Y h:i A", strtotime($ticketResponse['t_response_created_at'])) ?></p>
                          <p><?= $ticketResponse['t_response_message'] ?></p>
                        </div>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="p-2 border-top border-bottom mb-2">
                      <div class="d-flex gap-2">
                        <div class="mr-3">
                          <i class="fas fa-user-lock text-secondary" style="font-size: 32px"></i>
                        </div>
                        <div>
                          <p class="m-0">Admin Desk</p>
                          <p class="text-muted"><?= date("M d, Y h:i A", strtotime($ticketResponse['t_response_created_at'])) ?></p>
                          <p><?= $ticketResponse['t_response_message'] ?></p>
                        </div>
                      </div>
                    </div>
                  <?php endif ?>
                <?php endforeach ?>

                <div class="p-2 border-top border-bottom mb-2">
                  <div class="d-flex gap-2">
                    <div class="mr-3">
                      <i class="fas fa-user-circle text-secondary" style="font-size: 32px"></i>
                    </div>
                    <div>
                      <p class="m-0"><?= $ticketInfo['fullname'] ?></p>
                      <p class="small-text text-muted"><?= date("M d, Y h:i A", strtotime($ticketInfo['ticket_created_at'])) ?></p>
                      <p><?= $ticketInfo['ticket_description'] ?></p>
                    </div>
                  </div>
                </div>

                <?php if($ticketInfo['ticket_status'] != "closed"): ?>
                  <div class="mt-4">
                    <form id="responseForm">
                      <input type="hidden" name="tId" value="<?= $tId ?>">
                      <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control my-input" name="message" id="message" placeholder="Aa" rows="3"></textarea>
                      </div>
                      <div class="d-flex justify-content-end">
                        <button id="submitResponseBtn" class="btn btn-success" type="button">Submit</button>
                      </div>
                    </form>
                  </div>
                <?php endif ?>
              </div>
            </div>
          </div>
        </div>
        <!-- ./Tables Pending Verification -->

        
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Ticket Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ticket Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <p class="text-muted">Requestor</p>
            </div>
            <div class="col-8">
              <p><?= $ticketInfo['fullname'] ?></p>
            </div>
            <div class="col-4">
              <p class="text-muted">Created</p>
            </div>
            <div class="col-8">
              <p class="small-text"><?= date("M d, Y h:i A", strtotime($ticketInfo['ticket_created_at'])) ?></p>
            </div>

            <div class="col-12 border mb-3"></div>
            <div class="col-4">
              <p class="text-muted small-text">Id</p>
            </div>
            <div class="col-8">
              <p class="small-text">#<?= $ticketInfo['ticket_random_id'] ?></p>
            </div>
            <div class="col-4">
              <p class="text-muted small-text">Status</p>
            </div>
            <div class="col-8">
              <?php if($ticketInfo['ticket_status'] == "open"): ?>
                <span class="badge badge-success">open</span>
              <?php else: ?>
                <span class="badge badge-danger">closed</span>
              <?php endif ?>
            </div>

            <div class="col-4">
              <p class="text-muted small-text">Photos</p>
            </div>
            <div class="col-8">
              <?php
                if(!empty($ticketInfo['ticket_url_file'])) {
                  $urlFiles = explode(",", $ticketInfo["ticket_url_file"]);
                  $total = count($urlFiles);

                  for($i = 0; $i < $total; $i++) {
                    ?>
                    <div>
                      <a href="../ticket-uploads/<?= $urlFiles[$i] ?>"
                      target="_blank"
                      class="link link-success small-text">
                        <i class="text-secondary fas fa-paperclip"></i> <?= $urlFiles[$i] ?>
                      </a>
                    </div>
                    <?php
                  }
                }
              ?>
            </div>
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- /.Ticket Modal -->

  

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

    $('#submitResponseBtn').on('click', function(e) {
        e.preventDefault();

        let formData = $('#responseForm').serializeArray();
        

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to submit this response?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(result => {
          if(result.isConfirmed) {
            $.ajax({
              method: 'POST',
              url: "includes/ajax/ticket-response.inc.php",
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
        })

      });

    
      <?php if($ticketInfo['ticket_status'] != "closed"): ?>
        $('#closedBtn').on('click', function(e) {
  
          Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to closed this ticket?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
          }).then(result => {
            if(result.isConfirmed) {
              $.ajax({
                method: 'POST',
                url: "includes/ajax/ticket-closed.inc.php",
                data: {
                  tId : <?= $tId ?>
                },
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
          })
  
        });
      <?php endif ?>

  })
</script>

</body>
</html>
