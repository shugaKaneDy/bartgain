
const run = async()=>{
    //we need to load our models
    //loading the models is going to use await
    const stream = await navigator.mediaDevices.getUserMedia({
        video: true,
        audio: false,
    });

    const videoFeedEl = document.getElementById("video-feed");
    videoFeedEl.srcObject = stream;

    // we need to load our models
    // pre-trained machine learning for our facial detection
    await Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri('./models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('./models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('./models'),
        faceapi.nets.faceExpressionNet.loadFromUri('./models')
    ]);
    
    // make the canvas the same size and the same location
    // as our video feed
    const canvas = document.getElementById('canvas');
    canvas.style.left = videoFeedEl.offsetLeft;
    canvas.style.top = videoFeedEl.offsetTop;
    canvas.height = videoFeedEl.height;
    canvas.width = videoFeedEl.width;

    /////OUR FACIAL RECOGNITION DATA
    // we know who this is
    const refFace = await faceapi.fetchImage('images/lebron.jpg');

    //we grab the reference image, and hand it to detectAllFaces method
    let refFaceAiData = await faceapi.detectAllFaces(refFace).withFaceLandmarks().withFaceDescriptors();
    let faceMatcher = new faceapi.FaceMatcher(refFaceAiData);

    // facial detection with points
    setInterval(async () => {
        // get the video feed and hand it to detectAllFaces method
        let faceAIData = await faceapi.detectAllFaces(videoFeedEl).withFaceLandmarks().withFaceDescriptors().withFaceExpressions();
        // console.log(faceAIData);
        // we have a ton of good facial detection data in faceAIData
        // faceAIData is an array, one element for each face

        // draw on our face/canvas
        // first clear the canvas
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

        // draw our bounding box
        faceAIData = faceapi.resizeResults(faceAIData, videoFeedEl);
        faceapi.draw.drawDetections(canvas, faceAIData);
        faceapi.draw.drawFaceExpressions(canvas, faceAIData);

        faceAIData.forEach(face => {

            const {detection, descriptor} = face;
            let label = faceMatcher.findBestMatch(descriptor);
            // console.log(label.distance, label.label);
            let options = {label: "Lebron"};
            
            if(label.label.includes("unknown")){
                options = {label: "Unknown subject..."}
            }

            const drawBox = new faceapi.draw.DrawBox(detection.box, options);
            drawBox.draw(canvas);
        })

    }, 200);

}

run()