$(document).ready(function() {
  function updateLocation() {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition((position) => {
              const bdcAPI = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${position.coords.latitude}&longitude=${position.coords.longitude}`;
              getAPI(bdcAPI);
          }, (err) => {
              console.error("Geolocation error: " + err.message);
          });
      } else {
          console.error("Geolocation is not supported by this browser.");
      }
  }

  function getAPI(bdcAPI) {
      $.ajax({
          url: bdcAPI,
          method: 'GET',
          success: function(results) {
              const address = results.city + ', ' + results.localityInfo.administrative[2].name;
              const latitude = results.latitude;
              const longitude = results.longitude;
              sendLocationData(address, latitude, longitude);
          },
          error: function(xhr, status, error) {
              console.error("API error: " + status + " - " + error);
          }
      });
  }

  function sendLocationData(address, latitude, longitude) {
      $.ajax({
          url: 'update-location.php',
          type: 'POST',
          data: {
              address: address,
              latitude: latitude,
              longitude: longitude
          },
          success: function(response) {
              console.log("Location updated successfully: " + response);
          },
          error: function(xhr, status, error) {
              console.error("Update error: " + status + " - " + error);
          }
      });
  }

  // Update location every 5 minutes (300000 milliseconds)
  setInterval(updateLocation, 30000);

  // Update location immediately when the page loads
  updateLocation();
});
