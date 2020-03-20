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
//  $comment = $commentstatement -> fetch();

}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <a href="index.php">Home</a>
    <p>filler content for now</p>
    <p><?=$podcast['PodcastID']?></p>
    <div class="container">
      <form class="createcomment" action="processcomment.php" method="post">
        <label for="name">Name: </label>
        <input type="text" name="name" value="">
        <label for="content">Comment: </label>
        <textarea name="content" rows="8" cols="80"></textarea>
        <input type="submit" name="submit" value="Post Comment">
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
            </li>
          <?php endforeach ?>
          <?php endif ?>
      </ul>
    </div>
  </body>
</html>