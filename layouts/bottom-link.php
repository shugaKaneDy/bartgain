<!-- BT Link script -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
<script src="js/bootstrap-5.3/bootstrap.bundle.min.js"></script>

<!-- JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- SweetAlert2 -->
<script src="js/sweetalert2/swal.js"></script>

<!-- LightBox jquery -->
<!-- <script src="lightbox-plus-jquery.js"></script> -->

<!-- Data Tables -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>

<!-- leaflet cdn -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- leaflet routing js -->
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<!-- QrScanner -->
<script src="js/qr-scanner/html5-qrcode.min.js"></script>

<!-- QrCode -->
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

<!-- Tooltip -->
<script>
  const tooltips = document.querySelectorAll('.tt');
  tooltips.forEach(t => {
    new bootstrap.Tooltip(t)
  });

  window.addEventListener("load", function () {
      var preloader = document.querySelector('.preloader');
      if (preloader) {
          preloader.classList.add('animation__move-up'); // Add move-up animation
          setTimeout(function() {
              preloader.style.display = 'none';  // Hide the preloader after animation
          }, 2500); // 2.5 seconds to allow the animation to finish
      }
  });

  $(document).ready(function() {
    function chatNotificaiton() {
      $.ajax({
        method: 'GET', 
        url: "includes/ajax/chat-notification.inc.php"
      }).done(res => {
        $('.chatNotification').append(res);
      })
    }

    function notificationNotification() {
      $.ajax({
        method: 'GET', 
        url: "includes/ajax/notification.inc.php"
      }).done(res => {
        $('.notificationNotification').append(res);
      })
    }

    chatNotificaiton();
    notificationNotification();

    setInterval(() => {
      chatNotificaiton();
      notificationNotification();
    }, 2500);
  })
  // this creates dynamic padding top
  let myNavHeight = $('.my-nav').outerHeight();
  $('body').css('padding-top', `${myNavHeight + 10}px`);

</script>