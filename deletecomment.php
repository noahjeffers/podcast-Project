<?php
require('connect.php');
session_start();
if (!isset($_SESSION['userid'])) {
  header('Location: index.php');
}
$error=false;
$success = false;
if (isset($_GET['commentid'])&&isset($_GET['podcastid'])) {
  if (!filter_input(INPUT_GET,'commentid',FILTER_VALIDATE_INT)||(!filter_input(INPUT_GET,'podcastid',FILTER_SANITIZE_SPECIAL_CHARS))) {
    $error=true;
  }
  else{
    $commentID=filter_input(INPUT_GET,'commentid',FILTER_VALIDATE_INT);
    $podcastID=filter_input(INPUT_GET,'podcastid',FILTER_SANITIZE_SPECIAL_CHARS);
    $query = "SELECT * FROM Comment WHERE CommentID = :commentID AND PodcastID = :podcastID";
    $statement = $db->prepare($query);
    $statement->bindValue(':commentID',$commentID);
    $statement->bindValue(':podcastID',$podcastID);
    $statement->execute();
    if($statement->rowCount()==1){
      $home="podcast.php?podcastid=".$podcastID;
      $success=true;
      $deletequery = "DELETE FROM Comment WHERE  CommentID = :commentID AND PodcastID = :podcastID";
      $deletestatement = $db->prepare($deletequery);
      $deletestatement->bindValue(':commentID',$commentID);
      $deletestatement->bindValue(':podcastID',$podcastID);
      $deletestatement->execute();
      header("Location: $home");
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Delete</title>
  </head>
  <body>
    <div class="container">
      <?php if ($error==true): ?>
        <p>One of the values supplied is incorrect, please try again</p>
        <p><?=$_GET['commentid'] ?></p>
        <p><?=$_GET['podcastid'] ?></p>
      <?php else: ?>
        <p><?=$commentID?></p>
        <p><?=$podcastID ?></p>
      <?php endif ?>
      <?php if ($success==true): ?>
        <h1>SUCCESS</h1>
      <?php else: ?>
          <pre><?= print_r($statement)?></pre>
      <?php endif ?>
      <a href="profile.php?creator=<?=$_SESSION['username']?>">My Profile</a>
    </div>
  </body>
</html>
