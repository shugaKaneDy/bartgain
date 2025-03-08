const run = async () => {


  // Start video stream
  const stream = await navigator.mediaDevices.getUserMedia({
    video: true,
    audio: false,
  });

  const videoFeedEl = document.getElementById("video-feed");
  videoFeedEl.srcObject = stream;

  // Load the models
  await Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri('./models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('./models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('./models'),
    faceapi.nets.faceExpressionNet.loadFromUri('./models')
  ]);

  // Reference face for matching
  const refFace = await faceapi.fetchImage('images/lebron.jpg');
  const refFaceAiData = await faceapi.detectAllFaces(refFace).withFaceLandmarks().withFaceDescriptors();
  const faceMatcher = new faceapi.FaceMatcher(refFaceAiData);

  // document

  const cardHeader = document.querySelector('.card-header');
  const matchPercentage = document.querySelector('.matchPercentage');
  const expression = document.querySelector('.expression');

  // Detect and log faces every 200ms
  setInterval(async () => {
    // Get facial data from video feed
    let faceAIData = await faceapi.detectAllFaces(videoFeedEl).withFaceLandmarks().withFaceDescriptors().withFaceExpressions();

    // console.log(faceAIData);


    // Perfect
    // faceAIData.forEach(face => {
    //   const {descriptor, expressions } = face;

    //   // Face match
    //   let label = faceMatcher.findBestMatch(descriptor);
    //   let matchLabel = label.label.includes("unknown") ? "Unknown subject..." : "Lebron";
    //   let matchPercentage = (1 - label.distance) * 100;  // Convert distance to percentage

    //   // Log results to console
    //   console.log(`Face detected: ${matchLabel}`);
    //   console.log(`Match confidence: ${matchPercentage.toFixed(2)}%`);

    //   // Get the highest expression
    //   const highestExpression = Object.entries(expressions).reduce((highest, current) => {
    //     return current[1] > highest[1] ? current : highest;
    //   });

    //   const [expressionName, confidence] = highestExpression;
    //   console.log(`Highest expression: ${expressionName} (${(confidence * 100).toFixed(2)}%)`);
    // });


    // Draft
    faceAIData.forEach(face => {
      const {descriptor, expressions } = face;

      // Face match
      let label = faceMatcher.findBestMatch(descriptor);


      // Face data
      // console.log("label: " + label.label);
      // console.log("match percentage: " + label.distance.toFixed(2) + "%");


      // Get the highest expression
      const highestExpression = Object.entries(expressions).reduce((highest, current) => {
        return current[1] > highest[1] ? current : highest;
      });

      const [expressionName] = highestExpression;

      // console.log("Expression: " + expressionName);


    });
  }, 1000);
}

run();