<?php
require('connect.php');
session_start();
$creator = ' ';

if(!isset($_GET['creator'])){
  header("Location: index.php");
}

$GETCreator = filter_input(INPUT_GET, 'creator', FILTER_SANITIZE_SPECIAL_CHARS);

if(strtolower($GETCreator)=='admin'){
  header("Location: index.php");
}
else{
  $query = "SELECT username,userid FROM creator WHERE UserName = :creator ";
  $statement = $db->prepare($query);
  $statement->bindValue(':creator',$GETCreator);
  $statement->execute();
  $user = $statement->fetch();
}

if(isset($_SESSION['username'])){
  $username=$_SESSION['username'];
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Only loads the podcasts of the Creator supplied in the $_GET
$podcastquery = "SELECT title, description,PodcastID FROM Podcast WHERE podcastID LIKE  :profilepodcast  ";
$podcaststatement = $db->prepare($podcastquery);
$userWwildcard =$user['userid'].'%';
$podcaststatement ->bindValue(':profilepodcast',$userWwildcard);
$podcaststatement->execute();


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$creator?> Profile</title>
  </head>
  <body>
    <a href="index.php">Home</a>
    <p><?=$creator?></p>
    <?php if (isset($_SESSION['username'])): ?>
      <?php if($_SESSION['username']==$GETCreator)?>
        <p>THIS IS YOUR PAGE</p>
        <a href="editaccount.php">EDIT</a><br><br>
        <a href="upload.php">Upload a Podcast</a>
    <?php endif ?>
    <div class="Podcasts">
      <ul>
        <?php if ($podcaststatement -> rowCount()<1):?>
            <h2>Error - No Podcasts found</h2>
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
  </body>
</html>
