<?php
session_start();
require_once '../includes/dbh.inc.php';
require_once '../functions.php';
require_once 'includes/admin-validation.php';

// Get the start and end date from the form if available
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build the WHERE clause dynamically based on the date range
$dateCondition = "WHERE 1=1"; // Start with a basic WHERE clause (for flexible addition of conditions)

if ($startDate && $endDate) {
    $dateCondition .= " AND expense_created_at BETWEEN :start_date AND :end_date";
} elseif ($startDate) {
    $dateCondition .= " AND expense_created_at >= :start_date";
} elseif ($endDate) {
    $dateCondition .= " AND expense_created_at <= :end_date";
}

// Prepare the query with dynamic date filtering
$sql = "SELECT * FROM expenses
        $dateCondition
        ORDER BY expense_id DESC"; // You can order by other columns if needed

// Execute the query with parameters if date range is provided
$params = [];
if ($startDate && $endDate) {
    $params = ['start_date' => $startDate, 'end_date' => $endDate];
} elseif ($startDate) {
    $params = ['start_date' => $startDate];
} elseif ($endDate) {
    $params = ['end_date' => $endDate];
}

// Execute the query and fetch the result
$expenses = selectQuery($pdo, $sql, $params);

$expenseAmount = 0;

foreach ($expenses as $expense) {
  $expenseAmount += $expense['expense_amount'];
}

$totalExpense = count($expenses);

// print_r($expenses);
// exit;

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
  <title>Adm | Expense</title>

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
            <h1>EXPENSE</h1>
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
          
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-wallet"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Expense Availed</span>
                <span class="info-box-number"><?= $totalExpense ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-money-bill-wave-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Expense</span>
                <span class="info-box-number font-weight-bold text-danger">₱ <?= $expenseAmount ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          
        </div>
        <!-- ./ Overview -->

        <!-- Date Filter Form -->
        <form method="GET" class="mb-3">
          <div class="row">
            <div class="col-md-3">
              <label for="start_date">Start Date</label>
              <input type="date" class="form-control" id="start_date" name="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>">
            </div>
            <div class="col-md-3">
              <label for="end_date">End Date</label>
              <input type="date" class="form-control" id="end_date" name="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>">
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Filter</button>
              <a href="expense.php" class="btn btn-secondary" style="margin-top: 30px;">Reset</a>
            </div>
          </div>
        </form>

        <!-- Tables -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button class="btn btn-success btn-sm"
                data-toggle="modal" data-target="#addExpense"
                >
                  Add Expense
                </button>
              </div>
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Category</th>
                      <th>Amount</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($expenses as $expense): ?>
                      <tr>
                        <td><?= $expense['expense_id'] ?></td>
                        <td><?= $expense['expense_category'] ?></td>
                        <td class="text-danger font-weight-bold">₱ <?= $expense['expense_amount'] ?></td>
                        <td><?= date("M d, Y", strtotime($expense['expense_created_at'])) ?></td>
                        <td>
                          <button class="btn btn-sm btn-light expenseDetails"
                          data-toggle="modal" data-target="#detailModal"
                          expenseCat="<?= $expense['expense_category'] ?>"
                          expenseDetails="<?= $expense['expense_details'] ?>"
                          expenseAmount="<?= $expense['expense_amount'] ?>"
                          expenseCreatedAt="<?= date("M d, Y", strtotime($expense['expense_created_at'])) ?>"
                          >
                            Details
                          </button>
                          <a href="expense-edit.php?expenseId=<?= $expense['expense_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                          <button class="btn btn-sm btn-danger deleteExpense"
                          expenseId="<?= $expense['expense_id'] ?>"
                          >Delete</button>
                        </td>
                      </tr>
                    <?php endforeach ?>
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

  <!-- Add Modal -->
  <div class="modal fade" id="addExpense" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addExpenseModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addExpenseForm">
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
              <textarea class="form-control" name="expenseDetails" id="expenseDetails" row="3" placeholder="Describe the expense"></textarea>
            </div>

            <div class="form-group mb-3">
              <label for="expenseAmount">Expense Amount</label>
              <input type="number" class="form-control" name="expenseAmount" id="expenseAmount" placeholder="Amount in PHP" step="0.01" required>
            </div>

            <div class="form-group mb-3">
              <label for="expenseDate">Expense Date</label>
              <input type="date" class="form-control" name="expenseDate" id="expenseDate" required>
            </div>
          </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger" id="addExpenseBtn">Add Expense</button>
        </div>
      </div>
    </div>
  </div>
  <!-- ./Add Modal -->

  <!-- Details Modal -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Category: <span class="text-muted" id="catCon"></span></p>
          <p>Amount: <span class="text-muted" id="amtCon"></span></p>
          <p>Date: <span class="text-muted" id="dateCon"></span></p>
          <p>Details: <span class="text-muted" id="detCon"></span></p>
        </div>
      </div>
    </div>
  </div>
  <!-- ./Details Modal -->



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

    $('#example2').DataTable({
      "paging": true,
      "ordering": false,
      "responsive": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
  });
</script>

<script>
  $(document).ready(function() {
    $('#addExpenseBtn').on('click', function(e) {
      e.preventDefault();

      let formData = $('#addExpenseForm').serializeArray();

      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to add this expense?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
      }).then(result => {
        if(result.isConfirmed) {
          $.ajax({
            method: 'POST',
            url: "includes/ajax/expense-add.inc.php",
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
  });
</script>

<script>
  $(document).ready(function() {
    $(document).on('click', '.deleteExpense', function(e) {
      let expenseId = $(this).attr('expenseId');

      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this expense?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
      }).then(result => {
        if(result.isConfirmed) {
          $.ajax({
            method: 'POST',
            url: "includes/ajax/expense-delete.inc.php",
            data: {
              expenseId : expenseId
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
  });
</script>

<script>
  $(document).on('click', '.expenseDetails', function() {
    let expenseCat = $(this).attr('expenseCat');
    let expenseDetails = $(this).attr('expenseDetails');
    let expenseAmount = $(this).attr('expenseAmount');
    let expenseCreatedAt = $(this).attr('expenseCreatedAt');

    $('#detCon').html(expenseDetails);
    $('#amtCon').html(`₱ ${expenseAmount}`);
    $('#dateCon').html(expenseCreatedAt);
    $('#detCon').html(expenseDetails);

    
  })
</script>

</body>
</html>
