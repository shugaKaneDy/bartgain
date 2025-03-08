<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Bartgain</title>

  <!-- logo -->
  <link rel="icon" href="assets/logo.png">

  <!-- BT  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/index.css">
</head>
<body class="bg-light">

  <!-- nav -->
  <nav class="container-fluid m-0 bg-white shadow-sm row align-items-center fixed-top w-100 my-nav">
    <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center gap-1 ">
      <a class="navbar-brand d-flex align-items-center gap-1" href="#">
        <img class="rounded my-logo" src="assets/logo.png" alt="">
        <span class="fw-bold my-text-logo text-success p-0 m-0">BartGain</span>
      </a>
    </div>
    <div class="col-12 col-md-6 p-0">
      <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-3">
        <a href="signin.php" class="text-decoration-none text-success">
          Sign in
        </a>
        <a href="signup.php" class="btn btn-success rounded-pill">
          Join now
        </a>
      </div>
    </div>
  </nav>

  <main class="container-xxl">
    <div class="my-main-header row g-0">
      <div class="col-12 col-md-2 border border-danger">
        Ad Space
      </div>
      <div class="col-12 col-md-8 border border-primary">
        <header class="d-flex justify-content-center align-items-center flex-column h-100">
          <h1 class="fw-bolder responsive-heading text-center m-0">
            TRADE, DONATE YOUR
          </h1>
          <h1 class="fw-bolder responsive-heading text-center m-0">
            STUFF ANYTIME,
          </h1>
          <h1 class="fw-bolder responsive-heading text-center m-0">
            ANYWHERE.
          </h1>
          <p class="text-muted fs-4 responsive-p">Barter for a Better Tomorrow !</p>
          <a href="itemplace.php" class="btn btn-success bg-gradient">
            Find your trading partner 
            <i class="bi bi-caret-right-fill"></i>
          </a>
        </header>
      </div>
      <div class="col-12 col-md-2 border border-danger">
        Ad Space
      </div>
    </div>
  </main>
  


  <!-- BT Link script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Tooltip -->
  <script>
    const tooltips = document.querySelectorAll('.tt');
    tooltips.forEach(t => {
      new bootstrap.Tooltip(t)
    });
  </script>

  <script src="js/index.js"></script>
  
</body>
</html>