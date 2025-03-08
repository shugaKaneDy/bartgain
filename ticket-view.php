<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }
  if($_SESSION['user_details']['verified'] == "N") {
    header("location: itemplace.php");
    exit;
  }

  $csrfToken = bin2hex(string: random_bytes(32));
  $_SESSION['csrf_token_ticket_response'] = $csrfToken;

  if(isset($_GET['t_id'])) {
    if(!empty($_GET['t_id'])) {
      $tId = $_GET['t_id'];
      $ticketInfo = selectQueryFetch(
        $pdo,
        "SELECT * FROM tickets
        WHERE ticket_random_id = :tId
        AND ticket_user_id = :userId",
        [
          ":tId" => $tId,
          ":userId" => $_SESSION['user_details']['user_id'],
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


  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets</title>

  <?php
    require_once 'layouts/top-link.php';
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/ticket-view.css">


</head>
<body class="bg-light">

  <!-- nav -->
  <?php
    include "layouts/nav.php"
  ?>


  <!-- Offcanvas -->
  <?php
    include "layouts/aside.php"
  ?>

  <!-- pre load -->
  <?php
    include "layouts/preload.php"
  ?>

  <main>
    <div class="container-xl">
      <div class="d-flex justify-content-between gap-2">
        <h3><?= $ticketInfo['ticket_subject'] ?></h3>
        <?php if($ticketInfo['ticket_status'] != "closed"): ?>
          <div>
            <button class="btn btn-danger btn-sm text-nowrap small-text" id="closedBtn">Mark as closed</button>
          </div>
        <?php endif ?>

      </div>
      <div class="row">
        <div class="col-12 col-md-5 mb-3">
          <div class="accordion shadow-sm" id="accordionExample">
            <div class="accordion-item">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Ticket Details
              </button>
            </div>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
              <div class="accordion-body border bg-white">
                <div class="row">
                  <div class="col-4">
                    <p class="text-muted small-text">Requestor</p>
                  </div>
                  <div class="col-8">
                    <p class="small-text"><?= $_SESSION['user_details']['fullname'] ?></p>
                  </div>
                  <div class="col-4">
                    <p class="text-muted small-text">Created</p>
                  </div>
                  <div class="col-8">
                    <p class="small-text"><?= date("M d, Y h:i A", strtotime($ticketInfo['ticket_created_at'])) ?></p>
                  </div>
                  <hr>
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
                      <span class="badge text-bg-success">open</span>
                    <?php else: ?>
                      <span class="badge text-bg-danger">closed</span>
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
                            <a href="ticket-uploads/<?= $urlFiles[$i] ?>"
                            target="_blank"
                            class="link link-success small-text">
                              <i class="text-secondary bi bi-paperclip"></i><?= $urlFiles[$i] ?>
                            </a>
                          </div>
                          <?php
                        }
                      }
                    ?>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-7">
          <?php foreach($ticketResponses as $ticketResponse): ?>
            <?php if($ticketResponse['t_response_type'] == "user"): ?>
              <div class="p-2 border border-start-0 border-end-0 mb-2">
                <div class="d-flex gap-2">
                  <div>
                    <i class="bi bi-person-circle h2 text-muted"></i>
                  </div>
                  <div>
                    <p class="small-text m-0"><?= $_SESSION['user_details']['fullname'] ?></p>
                    <p class="small-text text-muted"><?= date("M d, Y h:i A", strtotime($ticketResponse['t_response_created_at'])) ?></p>
                    <p><?= $ticketResponse['t_response_message'] ?></p>
                  </div>
                </div>
              </div>
            <?php else: ?>
              <div class="p-2 border border-start-0 border-end-0 mb-2">
                <div class="d-flex gap-2">
                  <div>
                    <i class="bi bi-person-fill-lock h2 text-muted"></i>
                  </div>
                  <div>
                    <p class="small-text m-0">Admin Desk</p>
                    <p class="small-text text-muted"><?= date("M d, Y h:i A", strtotime($ticketResponse['t_response_created_at'])) ?></p>
                    <p><?= $ticketResponse['t_response_message'] ?></p>
                  </div>
                </div>
              </div>
            <?php endif ?>
          <?php endforeach ?>
          <div class="p-2 border border-start-0 border-end-0 mb-2">
            <div class="d-flex gap-2">
              <div>
                <i class="bi bi-person-circle h2 text-muted"></i>
              </div>
              <div>
                <p class="small-text m-0"><?= $_SESSION['user_details']['fullname'] ?></p>
                <p class="small-text text-muted"><?= date("M d, Y h:i A", strtotime($ticketInfo['ticket_created_at'])) ?></p>
                <p><?= $ticketInfo['ticket_description'] ?></p>
              </div>
            </div>
          </div>

          <?php if($ticketInfo['ticket_status'] != "closed"): ?>
            <div class="mt-4">
              <form id="responseForm">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
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

  </main>

  
  <?php
    require_once 'layouts/bottom-link.php';
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
                  csrf_token : <?= json_encode($csrfToken) ?>,
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