<?php
require('connect.php');
session_start();
$GETCreator = ' ';

if(!isset($_GET['creator'])){
  header("Location: index.php");
}
//
// $GETCreator = filter_input(INPUT_GET, 'creator', FILTER_SANITIZE_SPECIAL_CHARS);
$GETCreator = $_GET['creator'];

if(strtolower($GETCreator)=='admin'){
  header("Location: index.php");
}
else{
  $query = "SELECT username,userid,logo,description FROM creator WHERE UserID = :userId ";
  $statement = $db->prepare($query);
  $statement->bindValue(':userId',$GETCreator);
  $statement->execute();
  $user = $statement->fetch();
}

if(isset($_SESSION['username'])){
  $username=$_SESSION['username'];
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Only loads the podcasts of the Creator supplied in the $_GET
$podcastquery = "SELECT title, description,PodcastID FROM Podcast WHERE podcastID LIKE  :profilepodcast AND GenreID<>1 ";
$podcaststatement = $db->prepare($podcastquery);
$userWwildcard =$user['userid'].'%';
$podcaststatement ->bindValue(':profilepodcast',$userWwildcard);
$podcaststatement->execute();
if (isset($_SESSION['userid'])) {
  $SessionID=$_SESSION['userid'];
  $PageID=$user['userid'];

  $podcastquery = "SELECT title, description,PodcastID FROM Podcast WHERE podcastID LIKE  :profilepodcast  ";
  $podcaststatement = $db->prepare($podcastquery);
  $userWwildcard =$user['userid'].'%';
  $podcaststatement ->bindValue(':profilepodcast',$userWwildcard);
  $podcaststatement->execute();
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$GETCreator?> Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      .jumbotron{
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4"><?=$user['username']?></h1>
        <p class="lead">A member of NETWORK NAME.</p>
      </div>
    </div>
    <a href="index.php">Home</a>

    <p>Description: <?=$user['description']?></p>
    <?php if (isset($_SESSION['userid'])): ?>
      <?php if($SessionID==$PageID):?>
        <p>THIS IS YOUR PAGE</p>
        <p><?=$SessionID?></p>
        <p><?=$PageID?></p>
        <a href="editaccount.php">EDIT</a><br><br>
        <a href="upload.php">Upload a Podcast</a>
      <?php endif ?>
    <?php endif ?>
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
    <?php if ($user['logo']!=""): ?>
        <img src="<?=$user['logo']?>" alt="<?=$user['logo']?>">
    <?php endif ?>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
