<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';

  $faqs = selectQuery(
    $pdo,
    "SELECT * FROM faqs",
    []
  );

  $totalFaqs = count($faqs);

  $cnt = 1;

  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | FAQs</title>

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
            <h1>FAQs</h1>
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-question-circle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total FAQs</span>
                <span class="info-box-number"><?= $totalFaqs ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
        </div>
        <!-- ./ Overview -->

        <!-- Tables Pending Verification -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button class="btn btn-sm btn-success"
                data-toggle="modal" data-target="#exampleModal"
                >
                  Add FAQ
                </button>
              </div>
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Question</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($faqs as $faq): ?>
                      <tr>
                        <td class="font-weight-bold"><?= $faq['faq_id'] ?></td>
                        <td><?= $faq['faq_question'] ?></td>
                        <td><?= date("M d, Y", strtotime($faq['faq_created_at']))?></td>
                        <td>
                          <button class="btn btn-sm btn-info viewFaq"
                            faq-question="<?= $faq['faq_question'] ?>"
                            faq-answer="<?= $faq['faq_answer'] ?>"
                            data-toggle="modal" data-target="#viewFaqModal"
                          >
                            view
                          </button>
                          <a href="faq-edit.php?f_id=<?= $faq['faq_id'] ?>" class="btn btn-sm btn-warning">edit</a>
                          <button class="btn btn-sm btn-danger deleteFaq"
                            faq-id="<?= $faq['faq_id'] ?>"
                          >
                            delete
                          </button>
                        </td>
                      </tr>
                    <?php endforeach?>
                  </tbody>
                </table>
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

  <!-- FAQ Add modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add FAQ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addFaqForm">
            <div class="form-group">
              <label for="question">Question</label>
              <input type="text" class="form-control" name="question" id="question" placeholder="add question" required>
            </div>
            <div class="form-group">
              <label for="answer">Answer</label>
              <textarea class="form-control" id="answer" name="answer" rows="3" required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="addFaqBtn" class="btn btn-success">Add FAQ</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /.FAQ Add modal -->

  <!-- FAQ view modal -->
  <div class="modal fade" id="viewFaqModal" tabindex="-1" aria-labelledby="viewFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewFaqModalLabel">View FAQ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="h5 font-weight-bold mb-4" id="questionView">Lorem ipsum dolor sit amet consectetur.?</p>
          <p id="answerView">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sint voluptatibus est, hic laudantium optio corporis tempora. Omnis eveniet quod ut dicta quos? Modi atque debitis, earum velit hic fugiat quis!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /.FAQ view modal -->

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
    $('#example2').DataTable({
      "paging": true,
      "ordering": false,
      "responsive": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');


    $(document).on('click', ".viewFaq", function() {
      let faqQuestion = $(this).attr('faq-question');
      let faqAnswer = $(this).attr('faq-answer');

      $('#questionView').html(faqQuestion);
      $('#answerView').html(faqAnswer);
    })

    $(document).on('click', ".deleteFaq", function() {

      let fId = $(this).attr('faq-id');

      Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this FAQ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: 'POST',
            url: "includes/ajax/faq-delete.inc.php",
            data: {
              fId: fId
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
      });


    })


    $(document).on('click', "#addFaqBtn", function(e) {
      e.preventDefault();
      
      Swal.fire({
        title: "Are you sure?",
        text: "You want to add this on FAQ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = $('#addFaqForm').serializeArray();
          $.ajax({
            method: 'POST',
            url: "includes/ajax/faq-add.inc.php",
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
