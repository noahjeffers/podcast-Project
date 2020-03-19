<?php
  session_start();
  require('connect.php');

  $query = "SELECT username,userid FROM creator ";
  //$query = "SELECT username FROM creator WHERE username <> 'ADMIN' ";
  //$query = "SELECT username FROM creator WHERE GenreID <> 1 ";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
        <form class="log" action="login.php" method="post">
          <label for="username">User Name: </label>
          <input type="text" name="username" value="">
          <label for="password">Password: </label>
          <input type="text" name="password" value="">
          <input type="submit" name="login" value="Log In">
        </form>
      </div>
      <div class="wrapper">
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
      <div class="">
        <div class="genre">
          <ul class="genres">
            <?php if ($genrestatement -> rowCount()<1):?>
                <h2>Error - No Genre found</h2>
            <?php else: ?>
              <?php foreach ($genrestatement as $genre): ?>
                <li><?=$genre['genre']?></li>

                <?php endforeach ?>
            <?php endif ?>
          </ul>
        </div>

      </div>
      <div class="Podcasts">
        <?php if ($podcaststatement -> rowCount()<1):?>
            <h2>Error - No Podcasts found</h2>
        <?php else: ?>
          <?php foreach ($podcaststatement as $podcast): ?>
            <li><?=$podcast['Title']?></li>

            <?php endforeach ?>
        <?php endif ?>
      </div>

      <?php if(isset($_SESSION['username'])): ?>
        <a href="profile.php">Profile</a>
          <a href="logout.php">Log Out</a>
      <?php endif ?>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
