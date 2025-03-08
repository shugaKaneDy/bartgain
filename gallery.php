<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <?php
    include 'layouts/top-link.php'
  ?>

  

</head>
<body>

  <h1>Gallery</h1>

  <div class="row">
    <a href="assets/logo.png" class="col-md-12 col-lg-4 img-fluid py-2" data-toggle="lightbox" data-gallery="photo_gallery">
      <img src="assets/logo.png" class="img-fluid" alt="random img">
    </a>
    <a href="assets/logo-2.png" class="col-md-12 col-lg-4 img-fluid py-2" data-toggle="lightbox" data-gallery="photo_gallery">
      <img src="assets/logo-2.png" class="img-fluid" alt="random img">
    </a>
    <a href="assets/bg-picture.png" class="col-md-12 col-lg-4 img-fluid py-2" data-toggle="lightbox" data-gallery="photo_gallery">
      <img src="assets/bg-picture.png" class="img-fluid" alt="random img">
    </a>
  </div>


  <?php
    include 'layouts/bottom-link.php'
  ?>

  <script>
    
  </script>

</body>
</html>