<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Bartgain</title>

  <!-- Favicon -->
  <link rel="icon" href="assets/logo.png">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/index.css">
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="container-fluid bg-white shadow-sm row align-items-center fixed-top my-nav p-0 m-0">
    <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center gap-1">
      <a class="navbar-brand d-flex align-items-center gap-1" href="#">
        <img class="rounded my-logo" src="assets/logo.png" alt="">
        <span class="fw-bold my-text-logo text-success p-0 m-0">BartGain</span>
      </a>
    </div>
    <div class="col-12 col-md-6">
      <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-3 mb-2 mb-md-0">
        <a href="signin.php" class="text-decoration-none text-success">Sign in</a>
        <a href="signup.php" class="btn btn-success rounded-pill">Join now</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <main class="container-xxl mt-5 pt-5">
    <div class="row justify-content-center my-5">
      <div class="col-md-8 text-center">
        <h1 class="fw-bolder">TRADE, DONATE YOUR</h1>
        <h1 class="fw-bolder">STUFF EASILY,</h1>
        <h1 class="fw-bolder">WITHIN YOUR REACH.</h1>
        <p class="text-muted fs-4">Barter for a Better Tomorrow!</p>
        <a href="itemplace.php" class="btn btn-success bg-gradient">
          Find your trading partner <i class="bi bi-caret-right-fill"></i>
        </a>
      </div>
    </div>
  </main>

  <!-- About Section -->
  <section class="container my-5">
    <div class="my-main-header row align-items-center">
      <div class="col-md-6">
        <img src="assets/barterGraphic.png" class="img-fluid rounded" alt="About BartGain">
      </div>
      <div class="col-md-6">
        <h2 class="fw-bold text-success">About BartGain</h2>
        <p>BartGain is a platform designed to connect people within a local community. It enables users to trade or donate items they no longer need, encouraging sustainability and building stronger connections. Whether it’s furniture, gadgets, or clothes, BartGain makes it simple to find someone nearby to trade with.</p>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="container my-5">
    <h2 class="fw-bold text-center text-success">Why Choose BartGain?</h2>
    <div class="row text-center mt-4">
      <div class="col-md-4">
        <i class="bi bi-recycle fs-1 text-success"></i>
        <h5 class="fw-bold mt-3">Eco-Friendly</h5>
        <p>Minimize waste by giving your unused items a new purpose.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-people fs-1 text-success"></i>
        <h5 class="fw-bold mt-3">Community-Centered</h5>
        <p>Foster connections and collaboration with others nearby.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-clock fs-1 text-success"></i>
        <h5 class="fw-bold mt-3">Convenient</h5>
        <p>Trade easily with a platform designed for your needs.</p>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="bg-success text-white text-center py-5">
    <h2 class="fw-bold">Ready to Barter?</h2>
    <p class="mb-4">Join BartGain today and start trading locally!</p>
    <a href="signup.php" class="btn btn-light text-success fw-bold">Sign Up Now</a>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white py-4 text-center">
    <p>&copy; 2024 BartGain. All rights reserved.</p>
  </footer>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
