<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Multiple Images</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .image-preview {
      display: flex;
      flex-wrap: wrap;
    }
    .image-preview img {
      max-width: 100px;
      margin: 5px;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Upload Multiple Images</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="formFile" class="form-label">Select Images</label>
        <input class="form-control" type="file" id="formFile" name="images[]" multiple onchange="previewImages(event)">
      </div>
      <div class="image-preview" id="imagePreviewContainer"></div>
      <button type="submit" class="btn btn-primary">Upload</button>
    </form>
  </div>

  <script>
    function previewImages(event) {
      const imagePreviewContainer = document.getElementById('imagePreviewContainer');
      imagePreviewContainer.innerHTML = ''; // Clear any existing previews

      for (let i = 0; i < event.target.files.length; i++) {
        const file = event.target.files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          imagePreviewContainer.appendChild(img);
        };

        reader.readAsDataURL(file);
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
