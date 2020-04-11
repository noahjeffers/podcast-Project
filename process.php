<?php

// SANITIZED ///////////////////////////////////////////////////////////////////////////////////////////////////////

session_start();
require('connect.php');

if(!isset($_SESSION['username'])){
  header("Location: index.php");
}
$edit=false;
$delete=false;
$usercreation=false;
$uploadgenre=false;
$uploadpodcast=false;
$test="nothing";
$error=false;
$errorMessage="";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//CREATORS///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST){

  $query = "SELECT genre,genreid FROM genre ";
  $genrestatement = $db->prepare($query);
  $genrestatement->execute();
  $updateDate = date('yy-m-d H:i:s');

  $currentPodcast=filter_input(INPUT_POST,'currentPodcast',FILTER_SANITIZE_SPECIAL_CHARS); ////////////////////////////////////////////////////////////////////////////////////////////
  $creatorID = substr($currentPodcast,0,4);

    if($_POST['submit']=="Save Edit"){
      //update
      if ($creatorID==$_SESSION['userid']) {
        $newTitle=filter_input(INPUT_POST,'editTitle',FILTER_SANITIZE_SPECIAL_CHARS); ////////////////////////////////////////////////////////////////////////////////////////////
        $newDescription=filter_input(INPUT_POST,'editDescription',FILTER_SANITIZE_SPECIAL_CHARS); ////////////////////////////////////////////////////////////////////////////////////////////
        $newGenreID=filter_input(INPUT_POST,'genre',FILTER_VALIDATE_INT); ////////////////////////////////////////////////////////////////////////////////////////////
        $editQuery="UPDATE Podcast SET Title=:newTitle, Description=:newDescription, GenreID=:newGenreID, LastEdited=:updateDate WHERE PodcastID =:currentPodcast";
        $editStatement=$db->prepare($editQuery);
        $editStatement->bindValue(':newTitle',$newTitle);
        $editStatement->bindValue(':newDescription',$newDescription);
        $editStatement->bindValue(':newGenreID',$newGenreID);
        $editStatement->bindValue(':updateDate',$updateDate);
        $editStatement->bindValue(':currentPodcast',$currentPodcast);
        $editStatement->execute();
        $test="$updateDate";
      }
      else {
        $error=true;
        $errorMessage="Your UserID doesn't match the one stored on the Podcast, please try again";
      }
    }
    else if($_POST['submit']=="Delete Podcast"||$_POST['submit']=="Delete"){
      $podcastID=filter_input(INPUT_POST,'currentPodcast',FILTER_SANITIZE_SPECIAL_CHARS); //////////////////////////////////////////////////////////////////////
      $firstIndex=strrpos($podcastID,'-');
      $firstTrim=substr($podcastID,0,$firstIndex);
      $secondIndex=strpos($firstTrim,'-',0)+1;
      $secondTrim=substr($firstTrim,$secondIndex);
      $filePath='uploads' . DIRECTORY_SEPARATOR. $secondTrim;
      if ($_POST['submit']=="Delete") {
        $deleteQuery="DELETE FROM Podcast WHERE PodcastID =:podcastID";
        $deleteStatement=$db->prepare($deleteQuery);
        $deleteStatement->bindValue(':podcastID',$podcastID);
        if ($deleteStatement->execute()) {
          unlink($filePath);
        }
        else{
          $error=true;
          $errorMessage="The podcast failed to delete, please remove any comments on the post";
        }
      }

      $delete=true;

    }
    $query="SELECT * FROM Podcast WHERE PodcastID=:currentPodcast";
    $statement=$db->prepare($query);
    $statement->bindValue(":currentPodcast",$currentPodcast);
    $statement->execute();
    $podcast=$statement->fetch();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php if($error==true): ?>
      <p><?=$errorMessage?></p>
    <?php endif ?>
      <?php if($_POST['submit']=="Edit Podcast" || $_POST['submit']=="Save Edit"): ?>
        <form class="editPodcast" action="process.php" method="post">
          <p>Current Title: <?=$podcast['Title']?></p>
          <label for="editTitle">Edit Title:</label>
          <input type="text" name="editTitle" value="<?=$podcast['Title']?>"><br><br>
          <p>Current Description: <?=$podcast['Description']?></p>
          <label for="editDescription">Edit Description</label>
          <textarea name="editDescription" rows="8" cols="80"><?=$podcast['Description']?></textarea>
          <input type="text" name="currentPodcast" value="<?=$podcast['PodcastID']?>">
          <input class="btn btn-primary " type="submit" name="submit" value="Save Edit">
          <div class="genres">
            <select class="genre" name="genre">
              <?php if ($genrestatement -> rowCount()<1):?>
                <option value="-1">No Genre Found</option>
                  <h2>Error - No Genre found</h2>
              <?php else: ?>
                <?php foreach ($genrestatement as $genre): ?>
                  <option value="<?=$genre['genreid']?>" <?php if($genre['genreid']==$podcast['GenreID']):?> selected="selected"<?php endif ?>><?= $genre['genre']?></option>
                <?php endforeach; ?>
              <?php endif ?>
            </select>
        </form>
        <a href="profile.php?creator=<?=$_SESSION['userid']?>">Back to your Profile</a>
      <?php endif ?>
      <?php if($_POST['submit']=="Delete Podcast"):?>
        <form class="deletePodcast" action="process.php" method="post">
          <input type="hidden" name="currentPodcast" value="<?=$podcast['PodcastID']?>">
          <input class="btn btn-primary " type="submit" name="submit" value="Delete">
          <p><?=$podcastID?></p>
          <p><?=$firstIndex?></p>
          <p><?=$firstTrim?></p>
          <p><?=$secondIndex?></p>
          <p><?=$secondTrim?></p>
          <p><?=$filePath?></p>
        </form>
      <?php endif ?>
      <?php if(($_POST['submit']=="Delete")): ?>
        <p>The podcast has been deleted</p>
        <a href="profile.php?creator=<?=$_SESSION['username']?>">Back to your Profile</a>
      <?php endif ?>
  </body>
</html>
