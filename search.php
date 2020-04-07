<?php
require('connect.php');
session_start();
$error=false;
$errorMessage="Nothing was found, please try a different search.";
$genreListQuery = "SELECT genre FROM genre WHERE GenreID <> 1  ";
$genreListStatement = $db->prepare($genreListQuery);
$genreListStatement->execute();
// if(!isset($_POST['search'])){
//   $error=true;
// }
$postSearch = strtolower($_POST['search']);
$search='%'.$postSearch.'%';
$creatorQuery="SELECT * FROM Creator WHERE GenreID <> 1 AND LOWER(UserName) LIKE :searchName OR  GenreID <> 1 AND LOWER(Description) LIKE :searchDescription";
$creatorStatement=$db->prepare($creatorQuery);
$creatorStatement->bindValue(':searchName',$search);
$creatorStatement->bindValue(':searchDescription',$search);
$creatorStatement->execute();

$podcastQuery="SELECT * FROM Podcast WHERE GenreID <> 1 AND LOWER(Title) LIKE :searchTitle OR  GenreID <> 1 AND LOWER(Description) LIKE :searchPodcastDescription";
$podcastStatement=$db->prepare($podcastQuery);
$podcastStatement->bindValue(':searchTitle',$search);
$podcastStatement->bindValue(':searchPodcastDescription',$search);
$podcastStatement->execute();

$commentQuery ="SELECT * FROM Comment WHERE LOWER(Content) LIKE :searchContent OR LOWER(Name) LIKE :searchName";
$commentStatement=$db->prepare($commentQuery);
$commentStatement->bindValue(':searchContent',$search );
$commentStatement->bindValue(':searchName',$search);
$commentStatement->execute();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Search Results</title>
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
          <h1 class="display-4">Search Results</h1>
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
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>
        </nav>
      </div>
      <ul class="nav justify-content-end">
        <li class="nav-item">
          <p class="nav-link active">Refine your Search by Genre</p>
        </li>
        <li class="nav-item">
          <select class="nav-link" name="">
            <option value="1">All</option>
          </select>
        </li>
      </ul>
      <?php if($creatorStatement->rowCount()>0): ?>
        <h3>Creator Search Results</h3>
        <ul>
          <?php foreach ($creatorStatement as $creator):?>
            <li>
              <p><?=$creator['UserName']?></p>
              <p><?=$creator['Description']?></p>
              <a href="profile.php?creator=<?=$creator['UserID']?>">Link to Creator</a>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>

      <?php if($podcastStatement->rowCount()>0): ?>
        <h3>Podcast Search Results</h3>
        <ul>
          <?php foreach ($podcastStatement as $podcast):?>
            <li>
              <p><?=$podcast['Title']?></p>
              <p><?=$podcast['Description']?></p>
              <a href="podcast.php?podcastid=<?=$podcast['PodcastID']?>">Link to Podcast</a>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>

      <?php if($commentStatement->rowCount()>0): ?>
        <h3>Comment Search Results</h3>
        <ul>
          <?php foreach ($commentStatement as $comment):?>
            <li>
              <p><?=$comment['Content']?></p>
              <p><?=$comment['Name']?></p>
              <a href="podcast.php?podcastid=<?=$comment['PodcastID']?>">Link to Comment</a>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>


    </div>
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>
