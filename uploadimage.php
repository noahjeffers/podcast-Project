<?php
session_start();
require('connect.php');
  // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
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
          $imagequery = "UPDATE creator SET logo =:logo WHERE UserID = :userID";
          $imagestatement =$db->prepare($imagequery);
          $imagestatement->bindValue(':logo',$image);
          $imagestatement->bindValue('userID',$_SESSION['userid']);

          $imagestatement->execute();
      }
  }





?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php if ($upload_error_detected): ?>

        <p>Error Number: <?= $_FILES['image']['error'] ?></p>

    <?php elseif ($image_upload_detected): ?>

        <p>Client-Side Filename: <?= $_FILES['image']['name'] ?></p>
        <p>Apparent Mime Type:   <?= $_FILES['image']['type'] ?></p>
        <p>Size in Bytes:        <?= $_FILES['image']['size'] ?></p>
        <p>Temporary Path:       <?= $_FILES['image']['tmp_name'] ?></p>
        <p><?=$new_image_path?></p>

    <?php endif ?>
    <img src="<?=$image?>" alt="image">
  </body>
</html>
