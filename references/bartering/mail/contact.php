<?php
  session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    
    <div class="container">
      <div class="card">
        <div class="card-header">
          <h1 class="text-center">How to send mail on php</h1>
        </div>
        <div class="card-body">
          <form action="sendmail.php" method="post">
            <div class="mb-3">
              <label for="email_address">Email Address</label>
              <input type="email" name="email" id="email_address" class="form-control" required>
            </div>
            <div class="mb-3">
              <button type="button" name="submitContact" class="btn btn-primary">Send Mail</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      <?php if(isset($_SESSION['status'])): ?>
        Swal.fire({
          title: "Thank You",
          text: "<?= $_SESSION['status']; ?>",
          icon: "success"
        });
        <?php unset($_SESSION['status']); ?>
      <?php endif; ?>
    </script>

  </body>
</html>
