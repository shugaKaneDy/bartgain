$(document).ready(function() {

  // Load the necessary FaceAPI models
  const loadModels = async () => {
    await faceapi.nets.ssdMobilenetv1.loadFromUri('./models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('./models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('./models');
  };

  loadModels();

  // Function to handle file input
  $('#upload-photo').on('change', async function(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Display uploaded image
    const img = $('#uploaded-photo')[0];
    img.src = URL.createObjectURL(file);
    img.classList.remove('d-none');

    // Wait for the image to load
    img.onload = async function() {
      // Detect faces in the uploaded image
      const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();

      if (detections) {
        // Display confidence level
        const confidence = (detections.detection.score * 100).toFixed(2); // Convert to percentage
        $('.confidence-value').text(`${confidence}%`);
        $('.face-confidence').removeClass('d-none');
      } else {
        // No face detected
        $('.confidence-value').text("No face detected.");
        $('.face-confidence').removeClass('d-none');
      }
    };
  });

});
