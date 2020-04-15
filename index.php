<?php
// SANITZED ////////////////////////////////////////////////////////////////////////////////////////////
// HTML VALIDATED



$sort = 'az';
if(filter_input(INPUT_GET,"sort", FILTER_SANITIZE_SPECIAL_CHARS)){      ////////////////////////////////////////////////////////////////////////////////////////////
  $sort=filter_input(INPUT_GET,"sort", FILTER_SANITIZE_SPECIAL_CHARS); ////////////////////////////////////////////////////////////////////////////////////////////
}
//LOAD
  session_start();
  require('connect.php');
  require('C:\Users\Owner\vendor\autoload.php');

  $query = "SELECT * FROM creator WHERE GenreID <> 1 AND username <> 'ADMIN' ";
  $statement = $db->prepare($query);
  $statement->execute();

  $genreListQuery = "SELECT genre FROM genre WHERE GenreID <> 1  ";
  $genreListStatement = $db->prepare($genreListQuery);
  $genreListStatement->execute();

  if($sort=='az'){
    $sortedPodcastQuery = "SELECT * FROM podcast WHERE GenreID <>1 ORDER BY Title ";
    $podcaststatement = $db->prepare($sortedPodcastQuery);
    $podcaststatement->execute();     //A-Z
  }
  elseif ($sort=='za') {
    $sortedPodcastQuery = "SELECT * FROM podcast WHERE GenreID <>1 ORDER BY Title DESC";
    $podcaststatement = $db->prepare($sortedPodcastQuery);
    $podcaststatement->execute();      //Z-A
  }
  elseif ($sort=='oldest') {
    $podcastquery = "SELECT * FROM podcast WHERE GenreID <>1 ORDER BY LastEdited ";
    $podcaststatement = $db->prepare($podcastquery);
    $podcaststatement->execute();   //Oldest
  }
  elseif ($sort=='newest') {
    $podcastquery = "SELECT * FROM podcast WHERE GenreID <>1 ORDER BY LastEdited DESC";
    $podcaststatement = $db->prepare($podcastquery);
    $podcaststatement->execute();   //Newest
  }
  elseif ($sort=='uploadedFirst') {
    $sortedPodcastQuery = "SELECT * FROM podcast WHERE GenreID <>1 ORDER BY UploadDate ";
    $podcaststatement = $db->prepare($sortedPodcastQuery);
    $podcaststatement->execute();
  }
  else{
    $sortedPodcastQuery = "SELECT * FROM podcast WHERE GenreID <>1 ORDER BY UploadDate DESC";
    $podcaststatement = $db->prepare($sortedPodcastQuery);
    $podcaststatement->execute();
  }









?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Podcast CSM</title>
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
          <h1 class="display-4">Network Name</h1>
          <p class="lead">We cover all of the topics, from News to Comedy to History. NETWORK NAME does it all</p>
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
        <div class="container">
          <div class="row">
            <div class="col-3">
              <h4>Creators</h4>
              <ul class="creators">
                <?php if ($statement -> rowCount()<1):?>
                    <h2>Error - No Creators found</h2>
                <?php else: ?>
                  <?php foreach ($statement as $user): ?>
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
                <h3>Podcasts</h3>


                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a class="nav-link <?php if($sort=='az'){echo "active";}?>" href="index.php?sort=az">A-Z</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if($sort=='za'){echo "active";}?>" href="index.php?sort=za">Z-A</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if($sort=='oldest'){echo "active";}?>" href="index.php?sort=oldest">Oldest-Newest</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if($sort=='newest'){echo "active";}?>" href="index.php?sort=newest">Newest-Oldest</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if($sort=='uploadedFirst'){echo "active";}?>" href="index.php?sort=uploadedFirst">First Uploaded</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if($sort=='uploadedLast'){echo "active";}?>" href="index.php?sort=uploadedLast">Last Uploaded</a>
                  </li>
                </ul>
                <div class="container">
                  <ul class="list-group">
                    <?php if ($podcaststatement -> rowCount()<1):?>
                        <h2>Error - No Podcasts found</h2>
                    <?php else: ?>
                      <?php foreach ($podcaststatement as $podcast): ?>
                        <li class="list-group-item">
                          <a href="podcast.php?podcastid=<?=$podcast['PodcastID']?>"><?=$podcast['Title']?></a>
                        </li>

                        <?php endforeach ?>
                    <?php endif ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="login">
            <?php if(!isset($_SESSION['username'])): ?>
              <form class="log" action="login.php" method="post">
                <label for="username">User Name: </label>
                <input id="username" type="text" name="username" value="">
                <label for="password">Password: </label>
                <input id="password" type="password" name="password" value="" autocomplete="off">
                <input type="submit" name="login" value="Log In">
              </form>
            <?php else : ?>
              <?php if ($_SESSION['username']=='ADMIN'): ?>
                <a href="adminhomepage.php">ADMIN</a>
                <a href="logout.php">Log Out</a>
              <?php else: ?>
                <a href="logout.php">Log Out</a>
              <?php endif; ?>
          <?php endif ?>
          </div>
        </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>
