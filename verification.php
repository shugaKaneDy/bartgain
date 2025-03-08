<?php
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'functions.php';

  if(!isset($_SESSION['user_details'])) {
    header("location: logout.php");
    exit;
  }
  if($_SESSION['user_details']['verified'] == "Y") {
    header("location: itemplace.php");
    exit;
  }

  $pendingVerification = selectQueryFetch(
    $pdo,
    "SELECT * FROM verification
    WHERE verification_user_id = :userId
    AND verification_status = :verificationStatus
    ORDER BY verification_id DESC",
    [
      ":userId" => $_SESSION['user_details']['user_id'],
      ":verificationStatus" => 'pending'
    ]
  );

  if($pendingVerification) {
    header("Location: verification-pending.php?ver_id=". $pendingVerification['verification_random_id'] );
  }

  $primary_ids = [
    'Passport',
    'Driver\'s License',
    'National ID',
    'SSS ID', // Social Security System ID
    'GSIS ID', // Government Service Insurance System ID
    'PhilHealth ID', // Philippine Health Insurance Corporation ID
    'Postal ID',
    'Voter\'s ID',
    'PRC ID' // Professional Regulation Commission ID
  ];

  $supporting_documents = [
    'Utility Bill', // e.g., electricity, water, internet
    'Bank Statement',
    'Lease Agreement',
    'Company ID',
    'School ID', // For students
    'Birth Certificate',
    'Barangay Clearance', // Local community clearance
    'NBI Clearance' // National Bureau of Investigation clearance
  ];

  $csrfToken = bin2hex(string: random_bytes(32));
  $_SESSION['csrf_token_id_verification'] = $csrfToken;

?>

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
  
  <!-- pre load -->
  <?php
    include "layouts/preload.php"
  ?>

  <main>
    <div class="container-xl ">
      <header>
        <h3 class="bg-success rounded py-3 px-4 text-center text-white shadow-sm">Verification</h3>
      </header>
      <form id="verificationForm" enctype="multipart/form-data" method="post" action="user/save_verification.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <div class="row justify-content-center align-items-center">
          <!-- First Step -->
          <div class="firstSection col-12 col-md-7 py-3 p-md-5 border rounded bg-white shadow shadow-sm">
            <h3 class="text-center mb-5">Step 1</h3>
            <p class="fw-bold m-0">Complete the form</p>
            <!-- <div class="form-floating mb-3">
              <input name="fullname" type="text" class="form-control my-input" id="fullname" placeholder="Enter Fullname">
              <label for="fullname">Fullname</label>
            </div> -->
            <div class="form-floating mb-3">
              <input name="lastname" type="text" class="form-control my-input" id="lastname" placeholder="Enter Last Name">
              <label for="lastname">Last Name</label>
            </div>
            <div class="form-floating mb-3">
              <input name="firstname" type="text" class="form-control my-input" id="firstname" placeholder="Enter Fullname">
              <label for="firstname">First Name</label>
            </div>
            <div class="form-floating mb-3">
              <input name="middlename" type="text" class="form-control my-input" id="middlename" placeholder="Enter Fullname">
              <label for="middlename">Middle Name (Optional)</label>
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
                <select class="form-select my-input" id="primaryIdSelect" name="primary_id" required>
                    <option value="" disabled selected>Choose a Primary ID</option>
                    <?php foreach ($primary_ids as $id): ?>
                        <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="primaryIdSelect">Primary ID</label>
            </div>
            <div class="form-floating mb-3">
              <input name="uploadId" type="file" class="form-control my-input" id="uploadId" placeholder="Upload ID" accept="image/*" required>
              <label for="uploadId">Upload Front of ID</label>
            </div>
            <div class="mb-3">
              <img id="uploaded-photo" src="" alt="Uploaded Photo" class="img-fluid d-none" style="max-height: 200px;">
              <p class="face-confidence mt-2 d-none">Face Visibilty: <span class="confidence-value"></span></p>
            </div>
            <div class="form-floating mb-3">
              <input name="uploadBackId" type="file" class="form-control my-input" id="uploadBackId" placeholder="Upload Back ID" accept="image/*" required>
              <label for="uploadBackId">Upload Back of ID</label>
            </div>
            <div class="form-floating mb-3">
              <select class="form-select my-input" id="supportingDocumentSelect" name="supporting_document">
                  <option value="" disabled selected>Choose a Supporting Document</option>
                  <?php foreach ($supporting_documents as $doc): ?>
                    <option value="<?= htmlspecialchars($doc) ?>"><?= htmlspecialchars($doc) ?></option>
                  <?php endforeach; ?>
              </select>
              <label for="supportingDocumentSelect">Supporting Document</label>
            </div>
            <div class="form-floating mb-3">
              <input name="supportDocPic" type="file" class="form-control my-input" id="supportDocPic" placeholder="Upload ID" accept="image/*" required>
              <label for="supportDocPic">Upload Supporting Documents</label>
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
              <p>Show your face to verify</p>
              <div class="card">
                <div class="card-header">
                  Verifying
                </div>
                <div class="card-body px-0">
                  <p class="small-text text-danger">* Keep your expression <strong>neutral</strong> and match percentage to <strong>70%</strong> up for 3 seconds to verify</p>
                  <p class="counter text-success fw-bold">0</p>
                  <div id="myCamera" class="mx-auto"></div>
                  <input type="hidden" name="captured_image_data" id="captured_image_data">
                  <input type="hidden" name="percentage" id="percentage">
                  <p class="matchPercentage">Match Percentage</p>
                  <p>Expression: <span class="expression fw-bold"></span></p>
                </div>
              </div>
              <!-- <button class="btn btn-outline-success">Access Camera</button> -->
            </div>
            <div class="text-center mt-5">
              <button type="button" id="thirdPrev" class="btn btn-success btn-sm">prev</button>
            </div>
          </div>
          <!-- End of Third Step -->

        </div>
      </form>
    </div>
  </main>

  <?php
    include "layouts/spinner-overlay.php";
  ?>

  
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
  <script src="face-api-starter/public/plugins/webcamjs/webcam.min.js"></script>
  <script src="face-api-starter/public/face-api.min.js"></script>

  <script>
    $(document).ready(function() {
        const firstSection = $(".firstSection");
        const secondSection = $(".secondSection");
        const thirdSection = $(".thirdSection");

        const firstNext = $("#firstNext");
        const secondPrev = $("#secondPrev");
        const secondNext = $("#secondNext");
        const thirdPrev = $("#thirdPrev");

        var faceVisibility = 0;

        let matchCounter = 0;
        let matchThreshold = 3; // User needs to hold 40% match for 3 seconds

        // firstSection.hide();
        secondSection.hide();
        thirdSection.hide();

        // First section starts

        firstNext.on("click", function() {

          let lastname = $("#lastname").val();
          let firstname = $("#firstname").val();
          let address = $("#address").val();
          let birthDateInput = $("#birthDate").val();

          let birthDate = new Date(birthDateInput);
          let today = new Date();
          let age = today.getFullYear() - birthDate.getFullYear();
          let month = today.getMonth() - birthDate.getMonth();



          if (!lastname || !firstname || !address || !birthDateInput) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Please fill in all required fields!',
            });
            return;
          }
          // Adjust if the birth date hasn't occurred yet this year
          if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
            age--;
          }


          // Show SweetAlert if user is under 18
          if (age < 18) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'You must be at least 18 years old to proceed!',
            });
          } else {
            firstSection.hide();
            secondSection.show();
            thirdSection.hide();
          }
        });

        // First section ends


        secondPrev.on("click", function() {
            firstSection.show();
            secondSection.hide();
            thirdSection.hide();
        });

        secondNext.on("click", function() {
            if(faceVisibility < 70) {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Face visibilty must be 70% and up!',
              });
            } else {
              firstSection.hide();
              secondSection.hide();
              thirdSection.show();
            }
        });

        const loadModels = async () => {
            await faceapi.nets.ssdMobilenetv1.loadFromUri('./face-api-starter/public/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('./face-api-starter/public/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('./face-api-starter/public/models');
        };

        loadModels();

        const setResponsiveWebcam = async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                const videoTrack = stream.getVideoTracks()[0];
                const settings = videoTrack.getSettings();
                const aspectRatio = settings.width / settings.height;

                let webcamWidth = 320;
                let webcamHeight = webcamWidth / aspectRatio;

                Webcam.set({
                    width: webcamWidth,
                    height: webcamHeight,
                    image_format: 'jpeg',
                    jpeg_quality: 90
                });

                Webcam.attach('#myCamera');
            } catch (err) {
                Swal.fire({
                    icon: "error",
                    title: "Ooops...",
                    text: "Please allow camera access",
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        };

        const run = async (detections) => {
            await setResponsiveWebcam();

            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri('./face-api-starter/public/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('./face-api-starter/public/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('./face-api-starter/public/models'),
                faceapi.nets.faceExpressionNet.loadFromUri('./face-api-starter/public/models')
            ]);

            const faceMatcher = new faceapi.FaceMatcher(detections);

            const cardHeader = $('.card-header');
            const matchPercentage = $('.matchPercentage');
            const expression = $('.expression');

            cardHeader.html("Loading...");

            const faceScanInterval = setInterval(async function () {
                Webcam.snap(async function (dataUri) {
                    const img = new Image();
                    img.src = dataUri;

                    img.onload = async function () {
                        const faceAIData = await faceapi.detectAllFaces(img)
                            .withFaceLandmarks().withFaceDescriptors().withFaceExpressions();

                        if (faceAIData.length === 0) {
                            cardHeader.html("No faces detected");
                            matchPercentage.html(`No face`);
                            matchCounter = 0; // Reset counter if below 40%
                            $(".counter").html(matchCounter);
                        } else {
                            $.each(faceAIData, function (index, face) {
                                const { descriptor, expressions } = face;

                                let label = faceMatcher.findBestMatch(descriptor);
                                let threshold = 0.6;
                                let similarityPercentage = Math.max(0, (1 - (label.distance / threshold)) * 100) + 30;

                                if (label.label === 'unknown') {
                                    matchPercentage.html(`<span class="text-danger">Unknown User!!!</span>`);
                                    cardHeader.html(label.label);
                                    matchCounter = 0; // Reset counter if below 40%
                                    $(".counter").html(matchCounter);
                                } else {
                                    matchPercentage.html(`Match Percentage: <span class="fw-bold">${similarityPercentage.toFixed(2)}%</span>`);
                                    cardHeader.html("Recognized");

                                    // Check if the match percentage is above 40%
                                    if (similarityPercentage >= 70) {
                                        if(similarityPercentage > 100) {
                                          similarityPercentage = 100;
                                        }
                                        matchCounter++; // Increment counter if condition met
                                        $(".counter").html(matchCounter);
                                        $("#captured_image_data").val(dataUri);
                                        $("#percentage").val(similarityPercentage);
                                    } else {
                                        matchCounter = 0; // Reset counter if below 40%
                                        $(".counter").html(matchCounter);
                                    }

                                    // If counter reaches threshold (3 seconds), show success SweetAlert
                                    if (matchCounter >= matchThreshold) {
                                        clearInterval(faceScanInterval); // Assuming you're using setInterval for scanning

                                        // let formData = $('#verificationForm').serializeArray();
                                        let formData = new FormData($('#verificationForm')[0]);

                                        $.ajax({

                                          method: 'POST',
                                          url: "includes/ajax/user-verification.php?function=verify",
                                          data: formData,
                                          processData: false,
                                          contentType: false,
                                          dataType: "JSON",
                                          beforeSend: function() {
                                            $(".spinner-overlay").removeClass("d-none");
                                          }

                                        }).done(function (data) {

                                          if(data.status == "error") {
                                            $(".spinner-overlay").addClass("d-none");

                                            Swal.fire({
                                              icon: data.status,
                                              title: data.title,
                                              showConfirmButton: true
                                            });
                                          } else {
                                            $(".spinner-overlay").addClass("d-none");

                                            Swal.fire({
                                              icon: 'success',
                                              title: 'Verification Submitted!',
                                              text: data.title,
                                              confirmButtonText: 'Okay'
                                            }).then((result) => {
                                              if (result.isConfirmed) {
                                                matchCounter = 0; // Reset the counter after successful verification
                                                location.reload();
                                              }
                                            });
                                          }

                                        })

                                    }
                                }
                                

                                const highestExpression = Object.entries(expressions).reduce((highest, current) => {
                                    return current[1] > highest[1] ? current : highest;
                                });

                                const [expressionName] = highestExpression;
                                expression.text(expressionName);

                                if(expressionName != "neutral") {
                                  matchCounter = 0;
                                }
                            });
                        }
                    }
                });
            }, 1000);
        };

        $('#uploadId').on('change', async function(event) {
            $(".spinner-overlay").removeClass("d-none");
            const file = event.target.files[0];
            if (!file) return;

            const img = $('#uploaded-photo')[0];
            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');

            img.onload = async function() {
                const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();

                if (detections) {
                    const confidence = (detections.detection.score * 100).toFixed(2);
                    faceVisibility = confidence;
                    $('.confidence-value').text(`${confidence}%`);
                    $('.face-confidence').removeClass('d-none');
                    run(detections);
                } else {
                    faceVisibility = 0;
                    $('.confidence-value').text("No face detected.");
                    $('.face-confidence').removeClass('d-none');
                }
                $(".spinner-overlay").addClass("d-none");
            };
        });

        thirdPrev.on("click", function() {
            firstSection.hide();
            secondSection.show();
            thirdSection.hide();
        });
    });
</script>

  
</body>
</html>