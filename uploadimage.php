<?php

// NO POST TO SANITIZE ////////////////////////////////////////////////////////////////////////////////////////////
//HTML VALIDATED

session_start();
require('connect.php');
  require('C:\Users\Owner\vendor\Gumlet\php-image-resize\lib\ImageResize.php');
if (!isset($_SESSION['userid'])) {
  header("Location: index.php");
}

$profile="profile.php?creator=".$_SESSION['userid'];


$Query="SELECT Logo FROM Creator WHERE UserID=:userID";
$Statement=$db->prepare($Query);
$Statement->bindValue(':userID',$_SESSION['userid']);
$Statement->execute();
$oldImage=$Statement->fetch();

if ($_POST['submit']=='Upload Image') {
  function file_is_an_image($temporary_path, $new_path) {
      $allowed_mime_types      = ['image/gif', 'image/jpeg','image/jpg', 'image/png'];
      $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

      $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
      $actual_mime_type        = getimagesize($temporary_path)['mime'];

      $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
      $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

      return $file_extension_is_valid && $mime_type_is_valid;
  }

  $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
  $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);


  if ($image_upload_detected) {
      $image_filename        = $_FILES['image']['name'];
      $temporary_image_path  = $_FILES['image']['tmp_name'];


      $new_image_path        = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR. $image_filename;
      $image = 'images' . DIRECTORY_SEPARATOR. $image_filename;

      if (file_is_an_image($temporary_image_path, $new_image_path)) {
          move_uploaded_file($temporary_image_path, $new_image_path);
          if($Statement->rowCount()>0){
            //Deletes the previously stored Logo using the reference in the database
              unlink($oldImage['Logo']);
          }
          $imagequery = "UPDATE creator SET logo =:logo WHERE UserID = :userID";
          $imagestatement =$db->prepare($imagequery);
          $imagestatement->bindValue(':logo',$image);
          $imagestatement->bindValue('userID',$_SESSION['userid']);

          $imagestatement->execute();

          $resizeImage= new \Gumlet\ImageResize($image);
          $resizeImage->crop(300, 180);
          $resizeImage->save($image);
          header("Location: $profile");
      }
  }
}
if ($_POST['submit']=='Delete Image') {

  unlink($oldImage['Logo']);
  $updateImageQuery="UPDATE Creator SET Logo = NULL WHERE UserID=:userID";
  $updateImageStatement=$db->prepare($updateImageQuery);
  $updateImageStatement->bindValue(':userID',$_SESSION['userid']);
  $updateImageStatement->execute();
  header("Location: $profile");

}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ERROR</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      .jumbotron{
        text-align: center;
      }
    </style>
  </head>
  <body>
  <h2>Invalid file type please return and try again.</h2>
  <a href="editaccount.php">Edit your Account</a>
  </body>
</html>
