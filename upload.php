<?php
session_start();
require('connect.php');

$query = "SELECT genre FROM genre WHERE genre <> 'undefined'";
$genrestatement = $db->prepare($query);
$genrestatement->execute();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload</title>
  </head>
  <body>
    <div class="container">
      <form class="upload" action="process.php" method="post">
        <label for="filename">File Name:</label>
        <input type="text" name="filename" value="">
        <label for="title">Episode Title: </label>
        <input type="text" name="title" value="">
        <label for="description">Description:</label>
        <input type="text" name="description" value="">
        <div class="genres">
          <?php if ($genrestatement -> rowCount()<1):?>
              <h2>Error - No Genre found</h2>
          <?php else: ?>
            <?php foreach ($genrestatement as $genre): ?>
                <input type="radio" id="<?=$genre['genre']?>" name="genre" value="<?=$genre['genre']?>">
                <label for="<?=$genre['genre']?>"><?=$genre['genre']?></label>
              <?php endforeach ?>
          <?php endif ?>
          <br><input type="submit" name="upload" value="Upload File">
        </div>
      </form>
    </div>
  </body>
</html>
