<?php
session_start();
require('connect.php');
if(!isset($_SESSION['userid'])){
  header('Location: Index.php');
}
//populate into a dropdown list in The Future////////////////////////////////////////////////////////////////
$query = "SELECT genre,genreid FROM genre WHERE genre <> 'undefined'";
$genrestatement = $db->prepare($query);
$genrestatement->execute();

if($_POST){
  // function file_is_an_image($temporary_path, $new_path) {
  //     $allowed_mime_types      = ['image/gif', 'image/jpeg','image/jpg', 'image/png'];
  //     $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
  //
  //     $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
  //     $actual_mime_type        = getimagesize($temporary_path)['mime'];
  //
  //     $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
  //     $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
  //
  //     return $file_extension_is_valid && $mime_type_is_valid;
  // }
  //
  // $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
  // $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
  //
  // if ($image_upload_detected) {
  //     $image_filename        = $_FILES['image']['name'];
  //     $temporary_image_path  = $_FILES['image']['tmp_name'];
  //
  //
  //     $new_image_path        = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR. $image_filename;
  //     $image = 'images' . DIRECTORY_SEPARATOR. $image_filename;
  //
  //     if (file_is_an_image($temporary_image_path, $new_image_path)) {
  //         move_uploaded_file($temporary_image_path, $new_image_path);
  //         $imagequery = "UPDATE creator SET logo =:logo WHERE UserID = :userID";
  //         $imagestatement =$db->prepare($imagequery);
  //         $imagestatement->bindValue(':logo',$image);
  //         $imagestatement->bindValue('userID',$_SESSION['userid']);
  //
  //         $imagestatement->execute();
  //     }
  // }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload</title>
  </head>
  <body>
    <div class="container">
      <form method='post' enctype='multipart/form-data'>
          <label for='podcast'>Podcast Filename:</label>
          <input type='file' name='podcast' id='podcast'>

          <label for="title">Episode Title: </label>
          <input type="text" name="title" value="">

          <label for="description">Description:</label>
          <textarea name="description" rows="8" cols="80"></textarea>

          <div class="genres">
            <select class="genre" name="genre">
              <?php if ($genrestatement -> rowCount()<1):?>
                <option value="-1">No Genre Found</option>
                  <h2>Error - No Genre found</h2>
              <?php else: ?>
                <?php foreach ($genrestatement as $genre): ?>
                  <option value="<?=$genre['genreid']?>"><?= $genre['genre']?></option>
                <?php endforeach; ?>
              <?php endif ?>
            </select>
            <br>
          </div>


          <input type='submit' name='submit' value='Upload File'>
      </form>
    </div>


    <p><?=$_POST['title']?></p>
    <p><?=$_POST['description']?></p>
    <p><?=$_POST['genre']?></p>

  </body>
</html>
