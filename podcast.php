<?php
session_start();
require('connect.php');
//filter and sanitize the GET
if(!isset($_GET['podcastid'])){
  header('Location: index.php');
}
else{
  $podcastID = filter_input(INPUT_GET,'podcastid',FILTER_SANITIZE_SPECIAL_CHARS);
  $podcastquery = "SELECT * FROM podcast WHERE PodcastID = :podcastID";
  $podcaststatement = $db->prepare($podcastquery);
  $podcaststatement -> bindValue(':podcastID', $podcastID);
  $podcaststatement -> execute();
  $podcast=$podcaststatement -> fetch();

  $commentquery = "SELECT * FROM comment WHERE PodcastID =:podcastID ORDER BY PostDate DESC LIMIT 0,10";
  $commentstatement = $db->prepare($commentquery);
  $commentstatement -> bindValue(':podcastID', $podcastID);
  $commentstatement -> execute();

    $creatorID = substr($podcastID,0,stripos($podcastID,'-'));

  $creatorQuery="SELECT * FROM Creator WHERE UserID=:creatorID";
  $creatorStatement=$db->prepare($creatorQuery);
  $creatorStatement->bindValue(':creatorID',$creatorID);
  $creatorStatement->execute();
  $creator = $creatorStatement->fetch();

  $genreListQuery = "SELECT genre FROM genre WHERE GenreID <> 1  ";
  $genreListStatement = $db->prepare($genreListQuery);
  $genreListStatement->execute();


}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$podcast['Title']?></title>
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
          <h1 class="display-4">  <?=$podcast['Title'] ?></h1>
          <p class="lead">By: <?=$creator['UserName'] ?></p>
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
              <li class="nav-item active">
                <a class="nav-link" href="profile.php?creator=<?=$creator['UserID']?>"><?=$creator['UserName'] ?> <span class="sr-only"></span></a>
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
            <form class="form-inline my-2 my-lg-0" action="search.php" method="post">
              <input id="search"class="form-control mr-sm-2" name="search"type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>
        </nav>
      </div>

    <p><b>Episode Description:</b> <?=$podcast['Description']?></p>
    <?php if (isset($_SESSION['userid'])): ?>
      <?php if($_SESSION['userid']==$creatorID||$_SESSION['username']=='ADMIN'): ?>
        <form action="process.php" method="post">
          <input type="hidden" name="currentPodcast" value="<?=$podcast['PodcastID']?>">
          <input class="btn btn-primary " type="submit" name="submit" value="Edit Podcast">
        </form>
        <form action="process.php" method="post">
          <input type="hidden" name="currentPodcast" value="<?=$podcast['PodcastID']?>">
          <input class="btn btn-warning " type="submit" name="submit" value="Delete Podcast">
        </form>
      <?php endif ?>
    <?php endif ?>
    <div class="container">
      <form action="processcomment.php" method="post">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" name="name" id="name" placeholder="">
        </div>
        <div class="form-group">
          <label for="content">Comment:</label>
          <textarea class="form-control" id="content" name="content"rows="3"></textarea>
        </div>
        <input type="submit" name="submit" value="Post Comment">
        <input type="hidden" name="podcastID" value="<?=$podcast['PodcastID']?>">
        <input type="hidden" name="link" value="podcast.php?podcastid=<?=$podcast['PodcastID']?>">
      </form>



      <div class="container">
        <?php if($commentstatement->rowCount()<1):?>
          <div class="card">
            <div class="card-body">
              There are no comments yet.
            </div>
          </div>
        <?php else: ?>
          <?php foreach ($commentstatement as $comments): ?>
            <div class="card">
              <div class="card-body">
                <p><?=$comments['Content']?></p>
                <p>From: <?=$comments['Name']?></p>
                <small><?=$comments['PostDate']?></small>
                <?php if(isset($_SESSION['userid'])): ?>
                  <?php if($_SESSION['userid']==$creatorID || $_SESSION['username']=='ADMIN'): ?>
                  <small>
                    <a href="deletecomment.php?commentid=<?=$comments['CommentID']?>&podcastid=<?=$comments['PodcastID']?>">Delete Comment</a>
                  </small>
            <?php endif ?>
          <?php endif ?>
        </div>
      </div>
          <?php endforeach ?>
        <?php endif ?>

      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
