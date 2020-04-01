<?php
session_start();
require('connect.php');

if(!isset($_GET['genre'])){
  header("Location: index.php");
}

$genre = $_GET['genre'];
$genreQuery="SELECT * FROM Podcast JOIN Genre ON Podcast.GenreID = Genre.GenreID WHERE Genre=:genre";
$genreStatement = $db->prepare($genreQuery);
$genreStatement->bindValue(':genre',$genre);
$genreStatement->execute();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$genre?></title>
  </head>
  <body>
    <?php if ($genreStatement->rowCount()<1): ?>
      <h3>No Podcasts exist for this genre</h3>
    <?php else: ?>
      <ul>
        <?php foreach($genreStatement AS $podcast): ?>
        <li>
          <h4><?=$podcast['Title']?></h4>
        </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </body>
</html>
