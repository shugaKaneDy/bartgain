// Error Location
function errorLocation(error) {

  // Handle different error cases
  if (error.code === error.PERMISSION_DENIED) {
    // User denied geolocation
    Swal.fire({
      title: "Permission Denied",
      text: "Please allow access to location services to proceed.",
      icon: "warning",
    });
  } else {
    // Other errors (position unavailable, timeout, etc.)
    Swal.fire({
      title: "Error",
      text: "Error getting location: " + error.message,
      icon: "error",
    });
  }
  console.log("Error getting location: " + error.message);

}