<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verification</title>
  
  <?php
    include "layouts/top-link.php";
  ?>

  <link rel="stylesheet" href="css/general.css">
  <link rel="stylesheet" href="css/itemplace.css">
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

  <main>
    <div class="container-xl ">
      <header>
        <h3 class="bg-success rounded py-3 px-4 text-center text-white shadow-sm">Verification</h3>
      </header>
      <form id="verificationForm" enctype="multipart/form-data" method="post" action="user/save_verification.php">

        <div class="row justify-content-center align-items-center">
          <!-- First Step -->
          <div class="firstSection col-12 col-md-7 py-3 p-md-5 border rounded bg-white shadow shadow-sm">
            <h3 class="text-center mb-5">Step 1</h3>
            <p class="fw-bold m-0">Complete the form</p>
            <div class="form-floating mb-3">
              <input name="fullname" type="text" class="form-control my-input" id="fullname" placeholder="Enter Fullname">
              <label for="fullname">Fullname</label>
            </div>
            <div class="form-floating mb-3">
              <input name="address" type="text" class="form-control my-input" id="address" placeholder="Enter Address">
              <label for="adress">Address</label>
            </div>
            <div class="form-floating mb-3">
              <input name="birthDate" type="date" class="form-control my-input" id="birthDate" placeholder="Birth date" required>
              <label for="birthDate">Date of Birth</label>
            </div>
            <div class="text-center">
              <button type="button" id="firstNext" class="btn btn-success btn-sm">Next</button>
            </div>
          </div>
          <!-- End of First Step -->

          <!-- Second Step -->
          <div class="secondSection col-12 col-md-7 py-3 p-md-5 border rounded bg-white shadow shadow-sm">
            <h3 class="text-center mb-5">Step 2</h3>
            <p class="fw-bold m-0">Complete the form</p>
            <div class="form-floating mb-3">
              <input name="frontId" type="file" class="form-control my-input" id="frontId" placeholder="Upload Front of ID" required>
              <label for="frontId">Upload Front of ID</label>
            </div>
            <div class="text-center mt-5">
              <button type="button" id="secondPrev" class="btn btn-success btn-sm">prev</button>
              <button type="button" id="secondNext" class="btn btn-success btn-sm">next</button>
            </div>
          </div>
          <!-- End of Second Step -->

          <!-- Third Step -->
          <div class="thirdSection col-12 col-md-7 py-3 p-md-5 border rounded bg-white shadow shadow-sm">
            <h3 class="text-center mb-5">Step 3</h3>
            <div class="d-flex- text-center justify-content-center align-items-center">
              <p>Show your face to take a photo</p>
              <button class="btn btn-outline-success">Access Camera</button>
            </div>
            <div class="text-center mt-5">
              <button type="button" id="thirdPrev" class="btn btn-success btn-sm">prev</button>
              <button type="submit" id="submitBtn" class="btn btn-success btn-sm">submit</button>
            </div>
          </div>
          <!-- End of Third Step -->

        </div>
      </form>
    </div>
  </main>

  
  <?php
    include "layouts/bottom-link.php";
  ?>

  <!-- Dyamic Height for input -->
  <script>
    let myheader = $(".this-header").outerHeight();
    let myFormMinHeight = (myNavHeight + 10) + myheader;
    $("form").css("min-height", `calc(100vh - ${myFormMinHeight}px)`);
  </script>

  <!-- Webcam -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.24/webcam.js"></script>

  <script>

    $(document).ready(function() {
      const firstSection = $(".firstSection");
      const secondSection = $(".secondSection");
      const thirdSection = $(".thirdSection");

      const firstNext = $("#firstNext");
      const secondNext = $("#secondNext");
      const secondPrev = $("#secondPrev");
      const thirdPrev = $("#thirdPrev");
      const submitBtn = $("#submitBtn");

      

      secondSection.hide();
      thirdSection.hide();

      // firstNext.on("click", function() {

      //   let fullname = $("#fullname").val();
      //   let address = $("#address").val();
      //   let birthDateInput = $("#birthDate").val();

      //   let birthDate = new Date(birthDateInput);
      //   let today = new Date();
      //   let age = today.getFullYear() - birthDate.getFullYear();
      //   let month = today.getMonth() - birthDate.getMonth();



      //   if (!fullname || !address || !birthDateInput) {
      //     Swal.fire({
      //       icon: 'error',
      //       title: 'Oops...',
      //       text: 'Please fill in all required fields!',
      //     });
      //     return;
      //   }
      //   // Adjust if the birth date hasn't occurred yet this year
      //   if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
      //     age--;
      //   }


      //   // Show SweetAlert if user is under 18
      //   if (age < 18) {
      //     Swal.fire({
      //       icon: 'error',
      //       title: 'Oops...',
      //       text: 'You must be at least 18 years old to proceed!',
      //     });
      //   } else {
      //     firstSection.hide();
      //     secondSection.show();
      //     thirdSection.hide();
      //   }
      // });

      secondPrev.on("click", function() {

        firstSection.show();
        secondSection.hide();
        thirdSection.hide();
      });

      secondNext.on("click", function() {

        firstSection.hide();
        secondSection.hide();
        thirdSection.show();
      });

      thirdPrev.on("click", function() {

        firstSection.hide();
        secondSection.show();
        thirdSection.hide();
      });




    })

  </script>
  
</body>
</html>