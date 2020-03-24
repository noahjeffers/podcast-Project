<?php
// All Pages need to have input sanitized and validated

// Podcast Upload needs to have Genre put into DropDown List

// Podcast needs a back to profile link

// ADMIN needs to be able to delete any comments















  session_start();
  require('connect.php');

  $query = "SELECT username,userid FROM creator ";
  //$query = "SELECT username FROM creator WHERE username <> 'ADMIN' ";
  $query = "SELECT username FROM creator WHERE GenreID <> 1 AND username <> 'ADMIN' ";
  $statement = $db->prepare($query);
  $statement->execute();

  $query = "SELECT genre FROM genre WHERE genre <> 'undefined'  ";
  $genrestatement = $db->prepare($query);
  $genrestatement->execute();

  $podcastquery = "SELECT * FROM podcast";
  $podcaststatement = $db->prepare($podcastquery);
  $podcaststatement->execute();




?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Podcast CSM</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style>
.nav-link{
  width: 200px;
  border: 1px solid black;
}

.log{
  flex-wrap: wrap;
}
</style>
  </head>
  <body>
    <div class="container">
      <div class="page-header">
        <h2>NETWORK NAME</h2>
        <?php if(!isset($_SESSION['username'])): ?>
          <form class="log" action="login.php" method="post">
            <label for="username">User Name: </label>
            <input type="text" name="username" value="">
            <label for="password">Password: </label>
            <input type="password" name="password" value="" autocomplete="off">
            <input type="submit" name="login" value="Log In">
          </form>
        <?php else : ?>
          <?php if ($_SESSION['userid']=='9016'): ?>
            <a href="adminhomepage.php">ADMIN</a>
            <a href="logout.php">Log Out</a>
          <?php else: ?>
            <a href="profile.php?creator=<?=$_SESSION['username']?>">My Profile</a>
            <a href="logout.php">Log Out</a>
          <?php endif; ?>
      <?php endif ?>
      </div>
      <div class="">
        <ul class="creators">
          <?php if ($statement -> rowCount()<1):?>
              <h2>Error - No Creators found</h2>
          <?php else: ?>
            <?php foreach ($statement as $user): ?>
              <li> <a href="profile.php?creator=<?=$user['username']?>"> <?=$user['username']?></a></li>
              <?php endforeach ?>
          <?php endif ?>
        </ul>
      </div>
      <div class="container">
        <ul class="nav nav-tabs">
          <?php if ($genrestatement -> rowCount()<1):?>
              <h2>Error - No Genre found</h2>
          <?php else: ?>
            <?php foreach ($genrestatement as $genre): ?>
              <li class"nav-item">
                <a href="index.php?genre=<?=$genre['genre']?>"><?=$genre['genre']?></a>
              </li>

              <?php endforeach ?>
          <?php endif ?>
        </ul>
        <!-- <li class="nav-item">
          <a class="nav-link active" href="#">Active</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <div class=""> -->

        <div class="Podcasts">
          <h3>Most Recent Podcasts</h3>
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

      <?php if(isset($_SESSION['username'])): ?>
        <a href="profile.php">Profile</a>
          <a href="logout.php">Log Out</a>
          <p><?=$_SESSION['userid']?></p>
      <?php endif ?>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
