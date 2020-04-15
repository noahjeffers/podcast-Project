<?php

// Sanitized //////////////////////////////////////////////////////////////
// HTML VALIDATED
// Styled

session_start();
require('connect.php');


if(!isset($_SESSION['userid'])){
  header('Location: Index.php');
}

$genreListQuery = "SELECT genre FROM genre WHERE GenreID <> 1  ";
$genreListStatement = $db->prepare($genreListQuery);
$genreListStatement->execute();
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
          $filename=$podcast_filename;//$podcastLocation;
          $date=date("Y/m/d");
          $PodcastID = $creatorid."-".$filename."-".$date;

          $Title = filter_input(INPUT_POST,'title',FILTER_SANITIZE_SPECIAL_CHARS);//sanitized\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          $Description =filter_input(INPUT_POST,'description',FILTER_SANITIZE_SPECIAL_CHARS);//sanitized\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          $SelectedGenreID =filter_input(INPUT_POST,'genre',FILTER_VALIDATE_INT);

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
    body{
      background-color: black;
    }
    .jumbotron{
      background-color: grey;
      text-align: center;
      margin-bottom: 0;
    }
    .card:nth-child(even){
      background-color: orange;
    }
    .container{
      background-color: white;
    }
    .col-9{
      background: #CDCDCD;
    }
    .col-3{
      background: orange;
    }
    </style>
      </head>
      <body>
        <div class="container">
          <div class="jumbotron jumbotron-fluid">
              <h1 class="display-4">Upload a Podcast</h1>
          </div>
          <div >
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only"></span></a>
                  </li>
                  <?php if (isset($_SESSION['userid'])): ?>
                    <?php if ($_SESSION['username']=='ADMIN'): ?>
                      <li class="nav-item active">
                        <a class="nav-link" href="adminhomepage.php">Admin Page <span class="sr-only"></span></a>
                      </li>
                    <?php else: ?>
                      <li class="nav-item active">
                        <a class="nav-link" href="editaccount.php">Edit your Account <span class="sr-only"></span></a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link" href="upload.php">Upload a Podcast <span class="sr-only"></span></a>
                      </li>
                    <?php endif ?>
                  <?php endif ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Sort by Genre
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <?php if ($genreListStatement -> rowCount()<1):?>
                          <a class="dropdown-item" href="#">No Genre Found</a>
                      <?php else: ?>
                        <?php foreach ($genreListStatement as $genreList): ?>
                          <a class="dropdown-item" href="genre.php?genre=<?=$genreList['genre']?>"><?=$genreList['genre']?></a>
                        <?php endforeach ?>
                      <?php endif ?>
                    </div>
                  </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="search.php" method="post">
                  <input id="search" class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
                  <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="submit" value="Search">
                </form>
              </div>
            </nav>
          </div>
    <a href="profile.php?creator=<?=$_SESSION['userid']?>">Back to my profile</a>
    <div class="container">
      <form method='post' enctype='multipart/form-data'>
          <label for='podcast'>Podcast Filename:</label>
          <input type='file' name='podcast' id='podcast'>

          <label for="title">Episode Title: </label>
          <input type="text" id="title" name="title" value="">

          <label for="description">Description:</label>
          <textarea name="description" id="description" rows="8" cols="80"></textarea>

          <div class="genres">
            <select class="genre" name="genre">
              <?php if ($genrestatement -> rowCount()<1):?>
                <option value="-1">No Genre Found</option>
                  <h2>Error - No Genre found</h2>
              <?php else: ?>
                <?php foreach ($genrestatement as $genre): ?>
                  <option value="<?=$genre['genreid']?>"><?= $genre['genre']?></option>
                <?php endforeach ?>
              <?php endif ?>
            </select>
            <br>
          </div>
          <input type='submit' name='submit' value='Upload File'>
      </form>
    </div>

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
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
