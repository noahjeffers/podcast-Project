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
$creatorGenreQuery="SELECT * FROM Creator JOIN Genre ON Creator.GenreID = Genre.GenreID WHERE Genre=:genre";
$creatorGenreStatement = $db->prepare($creatorGenreQuery);
$creatorGenreStatement->bindValue(':genre',$genre);
$creatorGenreStatement->execute();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$genre?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      .jumbotron{
        text-align: center;
      }
    </style>
  </head>
  <body>
    <h2>Creators</h2>
    <?php if ($creatorGenreStatement->rowCount()<1): ?>
      <h4>There are no <?=$genre?> creators yet</h4>
    <?php else: ?>
      <ul>
        <?php foreach($creatorGenreStatement AS $creator): ?>
        <li>
          <h4><?=$creator['UserName']?></h4>
        </li>
      <?php endforeach ?>
      </ul>
    <?php endif ?>
    <h2>Podcasts</h2>
    <?php if ($genreStatement->rowCount()<1): ?>
      <h4>There are no <?=$genre?> podcasts yet</h4>
    <?php else: ?>
      <ul>
        <?php foreach($genreStatement AS $podcast): ?>
        <li>
          <h4><?=$podcast['Title']?></h4>
        </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
