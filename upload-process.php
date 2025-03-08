<?php

// function getSize($size) {

//   $kbSize = $size/1024;
//   $formatSize = number_format($kbSize, 2). ' KB';
//   return $formatSize;
// }

// // print_r($_FILES);


// // get the size
// // echo $_FILES['upload']['size'];

// // convert into kb
// echo getSize($_FILES['upload']['size']);
// echo "<br>";

// $path = 'uploads/' . $_POST['foldername'];

// $size = getSize($_FILES['upload']['size']);

// if($size < 12) {

//   if(!file_exists($path)) {

//     // make a folder
//     mkdir($path, 0777, true);
//   }

//   $tempFile = $_FILES['upload']['tmp_name'];

//   if($tempFile != "") {

//     $newFilepath = $path. "/" . $_FILES['upload']['name'];

//     if(move_uploaded_file($tempFile, $newFilepath)) {
//       echo "Success";
//     } else {
//       echo "Upload error encountered: " . $_FILES['upload']['error'];
//     }
//   }

// } else {

// }


// multi upload
// print_r($_FILES);

function getSize($size) {

  $kbSize = $size/1024;
  $formatSize = number_format($kbSize, 2). ' KB';
  return $formatSize;
}

$total = count($_FILES['upload']['name']);

for($i = 0; $i <$total; $i++) {

  $path = 'uploads/' . $_POST['foldername'];
  
  $size = getSize($_FILES['upload']['size'][$i]);
  
  if($size < 20) {
  
    if(!file_exists($path)) {
  
      // make a folder
      mkdir($path, 0777, true);
    }
  
    $tempFile = $_FILES['upload']['tmp_name'][$i];
  
    if($tempFile != "") {
  
      $newFilepath = $path. "/" . $_FILES['upload']['name'][$i];
  
      if(move_uploaded_file($tempFile, $newFilepath)) {
        echo "Success";
      } else {
        echo "Upload error encountered: " . $_FILES['upload']['error'];
      }
    }
  
  } else {
  
  }
}


