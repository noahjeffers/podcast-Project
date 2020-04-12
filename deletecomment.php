<?php

// SANITZED ////////////////////////////////////////////////////////////////////////////////////////////
// HTML VALIDATED

require('connect.php');
session_start();
if (!isset($_SESSION['userid'])) {
  header('Location: index.php');
}
$error=false;
$success = false;
if (isset($_GET['commentid'])&&isset($_GET['podcastid'])) {
  if (!filter_input(INPUT_GET,'commentid',FILTER_VALIDATE_INT)||(!filter_input(INPUT_GET,'podcastid',FILTER_SANITIZE_SPECIAL_CHARS))) {  ////////////////////////////////////////////////////////////////////////////////////////////
    $error=true;
  }
  else{
    $commentID=filter_input(INPUT_GET,'commentid',FILTER_VALIDATE_INT); ////////////////////////////////////////////////////////////////////////////////////////////
    $podcastID=filter_input(INPUT_GET,'podcastid',FILTER_SANITIZE_SPECIAL_CHARS); ////////////////////////////////////////////////////////////////////////////////////////////
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
    else {
      $error=true;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Delete</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      .jumbotron{
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <?php if ($error==true): ?>
        <p>One of the values supplied is incorrect, please try again</p>
        <p><?=$_GET['commentid'] ?></p>
        <p><?=$_GET['podcastid'] ?></p>
      <?php endif ?>
      <a href="profile.php?creator=<?=$_SESSION['username']?>">My Profile</a>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
