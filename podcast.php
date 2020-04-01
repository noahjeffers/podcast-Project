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

  $commentquery = "SELECT * FROM comment WHERE PodcastID =:podcastID ORDER BY PostDate DESC";
  $commentstatement = $db->prepare($commentquery);
  $commentstatement -> bindValue(':podcastID', $podcastID);
  $commentstatement -> execute();

    $creatorID = substr($podcastID,0,stripos($podcastID,'-'));
  //
  // $creatorQuery="SELECT * FROM Creator WHERE UserID=:creatorID";
  // $creatorStatement=$db->prepare($creatorQuery);
  // $creatorStatement->bindValue(':creatorID',$creatorID);
  //


}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$podcast['Title']?></title>
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
        <h1 class="display-4"><?=$podcast['Title']?></h1>
        <p class="lead">NETWORK NAME</p>
      </div>
    </div>
    <a href="index.php">Home</a>
    <?=$creatorID ?>

    <p>Description: <?=$podcast['Description']?></p>
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
      <form class="createcomment" action="processcomment.php" method="post">
        <label for="name">Name: </label>
        <input id="name" type="text" name="name" value=""><br><br>
        <label for="content">Comment: </label>
        <textarea id="content" name="content" rows="8" cols="80"></textarea>
        <input type="submit" name="submit" value="Post Comment">
        <input type="hidden" name="podcastID" value="<?=$podcast['PodcastID']?>">
        <input type="hidden" name="link" value="podcast.php?podcastid=<?=$podcast['PodcastID']?>">
      </form>
      <ul class="comments">
        <?php if($commentstatement->rowCount()<1):?>
          <li>There are no comments yet.</li>
        <?php else: ?>
          <?php foreach ($commentstatement as $comments): ?>
            <li>
              <small><?=$comments['CommentID'] ?></small>
              <p><?=$comments['Content']?></p>
              <p>From: <?=$comments['Name']?></p>
              <?php if(isset($_SESSION['userid'])): ?>
                <?php if($_SESSION['userid']==$creatorID || $_SESSION['username']=='ADMIN'): ?>
                <small>
                  <a href="deletecomment.php?commentid=<?=$comments['CommentID']?>&podcastid=<?=$comments['PodcastID']?>">Delete Comment</a>
                </small>
              <?php endif ?>
            <?php endif ?>
            </li>
          <?php endforeach ?>
        <?php endif ?>
      </ul>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
