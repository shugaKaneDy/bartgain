<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <?php
    include 'layouts/top-link.php'
  ?>

</head>
<body>

  <h1>File upload</h1>
  <form action="upload-process.php" method="post" enctype="multipart/form-data">

    <label for="">Folder name</label>
    <input type="text" name="foldername" id="foldername">

    <label for="">File Upload</label>
    <input type="file" name="upload[]" id="upload" multiple accept="image/*">

    <button type="submit">Submit</button>

  </form>
  

  <?php
    include 'layouts/bottom-link.php'
  ?>

  <script>
    
  </script>

</body>
</html>