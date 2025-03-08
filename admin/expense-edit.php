<?php
session_start();
require_once '../includes/dbh.inc.php';
require_once '../functions.php';
require_once 'includes/admin-validation.php';

$expenseId = $_GET['expenseId'];

$expenseInfo = selectQueryFetch(
  $pdo,
  "SELECT * FROM expenses WHERE expense_id = :expenseId",
  [
    ":expenseId" => $expenseId,
  ]
);

$expenseCategories = [
  "Office Supplies",
  "Marketing",
  "Transportation",
  "Utilities",
  "Salaries & Wages",
  "Rent",
  "Equipment & Maintenance",
  "Travel Expenses",
  "Insurance",
  "Professional Fees",
  "Miscellaneous",
  "Taxes",
  "Inventory",
  "Advertising",
  "Shipping & Delivery",
  "Subscriptions",
  "IT Services",
  "Legal Fees",
  "Hosting",
  "Other"
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adm | Expense Edit</title>

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
            <h1>EXPENSE EDIT</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-12 col-md-6">
            <div class="card">
              <div class="card-body">
                <form id="editExpenseForm">
                  <input type="hidden" name="expenseId" value="<?= $expenseId ?>">
                  <div class="form-group mb-3">
                    <label for="expenseCategory">Expense Category</label>
                    <select class="form-control select2" name="expenseCategory" id="expenseCategory" style="width: 100%;">
                      <?php foreach ($expenseCategories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group mb-3" id="otherCategoryDiv"  style="display:none;">
                    <label for="otherCategory">Other Category</label>
                    <input type="text" class="form-control" name="otherCategory" id="otherCategory" placeholder="Other">
                  </div>

                  <div class="form-group mb-3">
                    <label for="expenseDetails">Expense Details</label>
                    <textarea class="form-control" name="expenseDetails" id="expenseDetails" row="3" placeholder="Describe the expense"><?= $expenseInfo['expense_details'] ?></textarea>
                  </div>

                  <div class="form-group mb-3">
                    <label for="expenseAmount">Expense Amount</label>
                    <input type="number" class="form-control" name="expenseAmount" id="expenseAmount" placeholder="Amount in PHP" step="0.01" required value="<?= $expenseInfo['expense_amount'] ?>">
                  </div>

                  <div class="form-group mb-3">
                    <label for="expenseDate">Expense Date</label>
                    <input type="date" class="form-control" name="expenseDate" id="expenseDate" required value="<?=  date("Y-m-d", strtotime($expenseInfo['expense_created_at']))  ?>">
                  </div>

                  <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-success" id="editBtn">
                      Submit
                    </button>
                  </div>
                </form>
              </div>
            </div>
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

    //Initialize Select2 Elements
    $('.select2').select2()

    $('#expenseCategory').on('change', function() {
      if ($(this).val() === 'Other') {
        $('#otherCategoryDiv').show();
      } else {
        $('#otherCategoryDiv').hide();
      }
    });

    
  });
</script>

<script>
  $(document).on('click', '#editBtn', function() {

    let formData = $('#editExpenseForm').serializeArray();

    
    Swal.fire({
      title: 'Are you sure?',
      text: "Do you want to edit this expense?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then(result => {
      if(result.isConfirmed) {
        $.ajax({
          method: 'POST',
          url: "includes/ajax/expense-edit.inc.php",
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
                window.location.href = "expense.php";
              }
            });
          }

        })
      }
    })
  })
</script>


</body>
</html>
