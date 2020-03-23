<?php
require('connect.php');
session_start();
//Posted from the Podcast page
// inputs need to be sanitized
  $link = filter_input(INPUT_POST,'link',FILTER_SANITIZE_SPECIAL_CHARS);
  if(""==trim($_POST['name']))
  {
    header("Location: $link");
  }
  else {
    //SANITZE
    $commentName = $_POST['name'];
    $commentContent =$_POST['content'];
    $commentFK = $_POST['podcastID'];

    $createCommentQuery= "INSERT INTO Comment(Name,Content,PodcastID) VALUES(:name,:content,:podcastID)";
    $createCommentStatement = $db->prepare($createCommentQuery);
    $createCommentStatement->bindValue(':name',$commentName);
    $createCommentStatement->bindValue(':content', $commentContent);
    $createCommentStatement->bindValue(':podcastID', $commentFK);
    $createCommentStatement->execute();
  }



?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <a href=<?=$link?>>Link</a>
    <p><?=$link?></p>
    <p><?=$commentName?></p>
    <p><?=$commentContent?></p>
    <p><?=$commentFK?></p>
  </body>
</html>
