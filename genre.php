<?php
session_start();
require('connect.php');

if(!isset($_GET['genre'])){
  header("Location: index.php");
}

  $genreListQuery = "SELECT genre FROM genre WHERE GenreID <> 1  ";
  $genreListStatement = $db->prepare($genreListQuery);
  $genreListStatement->execute();


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
    </style>
  </head>
  <body>
    <div class="container">
      <div class="jumbotron jumbotron-fluid">
          <h1 class="display-4"><?=$genre?></h1>
      </div>
      <div >
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
              </li>
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
            <form class="form-inline my-2 my-lg-0">
              <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>
        </nav>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-3">
            <h4>Creators</h4>
            <ul class="creators">
              <?php if ($creatorGenreStatement -> rowCount()<1):?>
                  <h2>Error - No Creators found</h2>
              <?php else: ?>
                <?php foreach ($creatorGenreStatement as $user): ?>
                  <li>
                    <a href="profile.php?creator=<?=$user['UserID']?>">
                      <?=$user['UserName']?>
                    </a>
                  </li>
                  <?php endforeach ?>
              <?php endif ?>
            </ul>
          </div>
          <div class="col-9">

            <div class="Podcasts">
              <h3>Most Recent Podcasts</h3>
              <ul class="list-group">
                <?php if ($genreStatement -> rowCount()<1):?>
                    <h2>Error - No Podcasts found</h2>
                <?php else: ?>
                  <?php foreach ($genreStatement as $podcast): ?>
                    <li class="list-group-item">
                      <a href="podcast.php?podcastid=<?=$podcast['PodcastID']?>"><?=$podcast['Title']?></a>
                    </li>

                    <?php endforeach ?>
                <?php endif ?>
              </ul>
            </div>
          </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
