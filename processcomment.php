<?php

// SANITIZED ///////////////////////////////////////////////////////////////////////////////////////////////////////////
// NOT HTML TO VALIDATE

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
     $commentName = filter_input(INPUT_POST,'name',FILTER_SANITIZE_SPECIAL_CHARS); //////////////////////////////////////////////////////////////////////
    $commentContent =filter_input(INPUT_POST,'content',FILTER_SANITIZE_SPECIAL_CHARS);
    $commentFK = filter_input(INPUT_POST,'podcastID',FILTER_SANITIZE_SPECIAL_CHARS);

    //create redirect link
    $return = "podcast.php?podcastid=".$commentFK;

    $createCommentQuery= "INSERT INTO Comment(Name,Content,PodcastID) VALUES(:name,:content,:podcastID)";
    $createCommentStatement = $db->prepare($createCommentQuery);
    $createCommentStatement->bindValue(':name',$commentName);
    $createCommentStatement->bindValue(':content', $commentContent);
    $createCommentStatement->bindValue(':podcastID', $commentFK);
    $createCommentStatement->execute();

//  Redirect Back to the page that the comment was posted to
    header("Location: $return");
  }



?>
