<?php
  session_start();
  require_once '../includes/dbh.inc.php';
  require_once '../functions.php';
  require_once 'includes/admin-validation.php';


  // print_r($salesOvertime);
  // exit;

  if(!isset($_GET['f_id']) || empty($_GET['f_id'])) {
    header('location: faqs.php');
  }

  $fId = $_GET['f_id'];
  
  $faqInfo = selectQueryFetch(
    $pdo,
    "SELECT * FROM faqs WHERE faq_id = :fId",
    [
      ":fId" => $fId
    ]
  );

  if(empty($faqInfo)) {
    header('location: faqs.php');
  }

  // print_r($userInfo);
  // exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Edit FAQ</title>

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
            <h1>EDIT FAQ</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <a href="faqs.php" class="btn btn-sm btn-light">
              <i class="fas fa-arrow-left"></i> Back
            </a>
          </div>
          <div class="card-body">
            <form id="editFaqForm">
              <input type="hidden" name="fId" value="<?= $fId ?>">
              <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                  <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" class="form-control" name="question" id="question" placeholder="add question" required value="<?= $faqInfo['faq_question'] ?>">
                  </div>
                  <div class="form-group">
                    <label for="answer">Answer</label>
                    <textarea class="form-control" id="answer" name="answer" rows="3" required><?= $faqInfo['faq_answer'] ?></textarea>
                  </div>
                </div>
              </div>
              
              <div class="d-flex justify-content-end">
                <button class="btn btn-success" id="editFaqBtn" type="button">
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
    $(document).on('click', "#editFaqBtn", function(e) {
      e.preventDefault();
      
      Swal.fire({
        title: "Are you sure?",
        text: "You want to edit this user?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = $('#editFaqForm').serializeArray();
          $.ajax({
            method: 'POST',
            url: "includes/ajax/faq-edit.inc.php",
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
