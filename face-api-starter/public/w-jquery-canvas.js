$(document).ready(function() {

  const run = async () => {

    // Show loading indicator
    $(".spinner-overlay").removeClass("d-none");

    // Start video stream
    try {
      const stream = await navigator.mediaDevices.getUserMedia({
        video: true,
        audio: false,
      });

      $('#video-feed')[0].srcObject = stream;

      // Load the models
      await Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri('./models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('./models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('./models'),
        faceapi.nets.faceExpressionNet.loadFromUri('./models')
      ]);

      // Hide the loading indicator after models are loaded
      $(".spinner-overlay").addClass("d-none");

      // Reference face for matching
      const refFace = await faceapi.fetchImage('images/my-face.jpg');
      const refFaceAiData = await faceapi.detectAllFaces(refFace)
        .withFaceLandmarks().withFaceDescriptors();
      const faceMatcher = new faceapi.FaceMatcher(refFaceAiData);

      // jQuery selectors for DOM elements
      const cardHeader = $('.card-header');
      const matchPercentage = $('.matchPercentage');
      const expression = $('.expression');
      const vidHeight = $('.vidHeight');
      const vidWidth = $('.vidWidth');

      cardHeader.html("Loading...");

      // Detect and log faces every 1000ms (1 second)
      setInterval(async function() {
        const videoElement = $('#video-feed')[0];

        // Get the current rendered height and width (responsive dimensions)
        vidHeight.html(`Height: ${videoElement.offsetHeight}`);
        vidWidth.html(`Width: ${videoElement.offsetWidth}`);

        const faceAIData = await faceapi.detectAllFaces(videoElement)
          .withFaceLandmarks().withFaceDescriptors().withFaceExpressions();

        // Check if no faces were detected
        if (faceAIData.length === 0) {

          cardHeader.html("No faces detected");
          cardHeader.removeClass("text-success fw-bold");
          matchPercentage.html(`No face`);
        } else {

          $.each(faceAIData, function(index, face) {
            const { descriptor, expressions } = face;
  
            // Face match
            let label = faceMatcher.findBestMatch(descriptor);
            let threshold = 0.6;
            let similarityPercentage = Math.max(0, (1 - (label.distance / threshold)) * 100);

            if(label.label === 'unknown') {
              matchPercentage.html(`<span class = "text-danger">Unknown User!!!</span>`);
              cardHeader.html(label.label);
              cardHeader.removeClass("text-success fw-bold");

            } else {
              matchPercentage.html(`Match Percentage: <span class = "fw-bold">${similarityPercentage.toFixed(2)}%</span>`);
              cardHeader.html("Kane");
              cardHeader.addClass("text-success fw-bold");
            }
  
            // Get the highest expression
            const highestExpression = Object.entries(expressions).reduce((highest, current) => {
              return current[1] > highest[1] ? current : highest;
            });
  
            const [expressionName] = highestExpression;
  
            // Update expression in the DOM
            expression.text(expressionName);
          });
          
        }
        
      }, 1000);

      // Capture photo when the "Take Photo" button is clicked
      $('#take-photo').on('click', function() {
        const videoElement = $('#video-feed')[0];
        const canvas = document.createElement('canvas');
        
        // Set the canvas size to the video size
        canvas.width = videoElement.videoWidth;
        canvas.height = videoElement.videoHeight;

        // Draw the current frame of the video on the canvas
        canvas.getContext('2d').drawImage(videoElement, 0, 0, canvas.width, canvas.height);

        // Get the image data from the canvas
        const imageDataUrl = canvas.toDataURL('image/png');

        // Set the captured image source and display it
        $('#captured-photo').attr('src', imageDataUrl).removeClass('d-none');
      });
      
    } catch (error) {
      console.error('Error starting video stream:', error);
    }
  }

  run();
});
