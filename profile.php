<?php
require('connect.php');
session_start();
$GETCreator = ' ';

if(!isset($_GET['creator'])){
  header("Location: index.php");
}
$genreListQuery = "SELECT genre FROM genre WHERE GenreID <> 1  ";
$genreListStatement = $db->prepare($genreListQuery);
$genreListStatement->execute();
//
// $GETCreator = filter_input(INPUT_GET, 'creator', FILTER_SANITIZE_SPECIAL_CHARS);
$GETCreator = $_GET['creator'];
if($GETCreator=='9016'){
  header("Location: index.php");
}
else{
  $creatorQuery = "SELECT username,userid,logo,description FROM creator WHERE UserID = :userId ";
  $creatorStatement = $db->prepare($creatorQuery);
  $creatorStatement->bindValue(':userId',$GETCreator);
  $creatorStatement->execute();
  $creator = $creatorStatement->fetch();
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Only loads the podcasts of the Creator supplied in the $_GET
$podcastquery = "SELECT title, description,PodcastID FROM Podcast WHERE podcastID LIKE  :profilepodcast AND GenreID<>1 ";
$podcaststatement = $db->prepare($podcastquery);
$userWwildcard =$creator['userid'].'%';
$podcaststatement ->bindValue(':profilepodcast',$userWwildcard);
$podcaststatement->execute();
if (isset($_SESSION['userid'])) {
  $SessionID=$_SESSION['userid'];
  $PageID=$creator['userid'];

  $podcastquery = "SELECT title, description,PodcastID FROM Podcast WHERE podcastID LIKE  :profilepodcast  ";
  $podcaststatement = $db->prepare($podcastquery);
  $userWwildcard =$creator['userid'].'%';
  $podcaststatement ->bindValue(':profilepodcast',$userWwildcard);
  $podcaststatement->execute();
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$creator['username']?> Profile</title>
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
          <h1 class="display-4"><?=$creator['username']?></h1>
          <p class="lead"><?=$creator['description']?></p>
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
                <?php if($SessionID==$PageID):?>
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
              <input id="search"class="form-control mr-sm-2" name="search"type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>
        </nav>
      </div>
    <div class="Podcasts">
      <ul>
        <?php if ($podcaststatement -> rowCount()<1):?>
          <li>
            <h2>No podcasts have been uploaded yet.</h2>
            </li>
        <?php else: ?>
          <?php foreach ($podcaststatement as $podcast): ?>
            <li>
              <h5><a href="podcast.php?podcastid=<?=$podcast['PodcastID']?>"><?=$podcast['title']?></a></h5>
              <p><?=$podcast['description']?></p>
            </li>
            <?php endforeach ?>
        <?php endif ?>
      </ul>
    </div>
    <?php if ($creator['logo']!=""): ?>
        <img src="<?=$creator['logo']?>" alt="<?=$creator['logo']?>">
    <?php endif ?>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
