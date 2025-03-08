$(document).ready(function() {

  Webcam.set({
    width: 320,
    height: 240
  })

  $("#accessCamera").on("click", function() {
    Webcam.resert();
    Webcam.on('error', function() {
      $('#photoModal').modal('hide');
      Swal.fire({
        title: "Warning",
        text: 'Please give permission to access the camera',
        icon: 'Warning'
      });
    });
    Webcam.attach('#myCamera');
  });
});