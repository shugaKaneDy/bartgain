<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Image Upload with Bootstrap File Input</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.2.5/css/fileinput.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
  <div class="container">
    <h2 class="mt-5">Multiple Image Upload with Bootstrap File Input</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="file-loading">
            <input id="file-input" name="images[]" type="file" multiple>
        </div>
        <button type="submit" class="btn btn-success mt-3">Upload</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.2.5/js/fileinput.min.js"></script>

  <script>
    $(document).ready(function() {
        $("#file-input").fileinput({
            theme: 'fa',
            uploadUrl: "upload.php", // you must set a valid URL here else you will get an error
            allowedFileExtensions: ['jpg', 'png', 'gif'],
            overwriteInitial: false,
            maxFileSize: 2000,
            maxFilesNum: 10,
            showUpload: false,
            showRemove: true,
            showCancel: false,
            showCaption: true,
            initialPreviewAsData: true,
            deleteUrl: "/site/file-delete", // you must set a valid delete URL here
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            fileActionSettings: {
                showUpload: false,
                showRemove: true,
                showZoom: false,
                removeIcon: '<i class="bi bi-trash"></i>',
                removeClass: 'btn btn-sm btn-kv btn-default btn-outline-secondary kv-file-remove',
                removeTitle: 'Remove file'
            },
            layoutTemplates: {
                actionDelete: '<button type="button" class="kv-file-remove btn btn-sm btn-kv btn-default btn-outline-secondary" title="{removeTitle}">{removeIcon}</button>'
            }
        });
    });
  </script>
</body>
</html>
