<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <!-- BT  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


</head>
<body>


  <section class="bg-light">
    <div class="container-fluid">
      <div class="row text-center align-items-center justify-content-center vh-100" >
        <div class="col-12 col-md-6 mx-auto">
          <h1 class="mb-5">
            capture photo from a web cam using jquery and upload that photo to folder using php
          </h1>
          <button class="btn btn-success" id="accessCamera" data-bs-toggle="modal" data-bs-target="#photoModal">
            Capture Photo
          </button>
        </div>
      </div>
    </div>
  </section>


  <!-- Modal -->
  <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModal" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Capture Photo</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div>
            <div id="myCamera" class="d-block mx-auto rounded overflow-hidden"></div>
          </div>
          <div id="results" class="d-none"></div>
          <form action="" method="post" id="photoForm">
            <input type="hidden" id="photoStore" name="photoStore">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark border border-dark mx-auto" id="takePhoto">
            <i class="bi bi-camera"></i>
          </button>
          <button type="button" class="btn btn-success d-none" id="retakePhoto">Retake</button>
          <button type="submit" class="btn btn-success d-none" id="uploadPhoto" form="photoForm">Upload</button>
        </div>
      </div>
    </div>
  </div>

  
  <!-- BT Link script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Swal Alert -->
  <script src="../../js/sweetalert2/swal.js"></script>

  <!-- Web Cam -->
  <script src="web-cam.js"></script>


</body>
</html>