<?php
session_start();
require('connect.php');
if(!isset($_SESSION['userid'])){
  header('Location: Index.php');
}
//populate into a dropdown list in The Future////////////////////////////////////////////////////////////////
$query = "SELECT genre,genreid FROM genre ";
$genrestatement = $db->prepare($query);
$genrestatement->execute();

if($_POST){

  $file_upload_detected = isset($_FILES['podcast']) && ($_FILES['podcast']['error'] === 0);
  $upload_error_detected = isset($_FILES['podcast']) && ($_FILES['podcast']['error'] > 0);

  function validate_file($temporary_path, $new_path) {
      $allowed_mime_types      = ['audio/mpeg','audio/mp3','audio/mpeg3'];
      $allowed_file_extensions = ['mp3'];

      $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
      $actual_mime_type        = mime_content_type($temporary_path);

      $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
      $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

      return $file_extension_is_valid && $mime_type_is_valid;
  }
  if ($file_upload_detected) {
      $podcast_filename        = $_FILES['podcast']['name'];
      $now=time();
      $podcast_filename=$now.'-^-'.$podcast_filename;
      $temporary_podcast_path  = $_FILES['podcast']['tmp_name'];


      $new_podcast_path= dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR. $podcast_filename;
      $podcastLocation = 'uploads' . DIRECTORY_SEPARATOR. $podcast_filename;

      if (validate_file($temporary_podcast_path, $new_podcast_path)) {
        if(move_uploaded_file($temporary_podcast_path, $new_podcast_path)){

          $creatorid=$_SESSION['userid'];
          $filename=$podcastLocation;
          $date=date("Y/m/d");
          $PodcastID = $creatorid."-".$filename."-".$date;

          $Title = $_POST['title'];//sanitized\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          $Description = $_POST['description'];//sanitized\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          $SelectedGenreID =$_POST['genre'];

          $uploadquery= "INSERT INTO podcast(PodcastID,Title,Description,GenreID) VALUES(:PodcastID,:Title,:Description,:GenreValue)";
          $uploadStatement = $db->prepare($uploadquery);
          $uploadStatement->bindValue(':PodcastID',$PodcastID);
          $uploadStatement->bindValue(':Title', $Title);
          $uploadStatement->bindValue(':Description', $Description);
          $uploadStatement->bindValue(':GenreValue', $SelectedGenreID);
          $uploadStatement->execute();
        }

      }
      else {
        $uploadquery="NOT A VALID FILE";

      }
    }
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
    <?php if($_POST): ?>
      <?php if ($upload_error_detected): ?>

          <p>Error Number: <?= $_FILES['podcast']['error'] ?></p>

      <?php elseif ($file_upload_detected): ?>

          <p>Client-Side Filename: <?= $_FILES['podcast']['name'] ?></p>
          <p>Apparent Mime Type:   <?= $_FILES['podcast']['type'] ?></p>
          <p>Size in Bytes:        <?= $_FILES['podcast']['size'] ?></p>
          <p>Temporary Path:       <?= $_FILES['podcast']['tmp_name'] ?></p>
          <p><?=$new_podcast_path?></p>
                    <p><?=$uploadquery?></p>
      <?php endif ?>
    <?php endif ?>
  </body>
</html>
