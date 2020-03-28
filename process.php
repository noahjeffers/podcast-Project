<?php
session_start();
require('connect.php');

if(!isset($_SESSION['username'])){
  header("Location: index.php");
}
//\\//\\//\\//\\//\\//\\///\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
  //\\//\\//\\//\\//\\///\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
    ////\\\       |||||||\\    ||\\\   ///||  |||||||||   ||\\\    |||
   ////  \\\      |||     \\   |||\\\ ///|||     |||      |||\\\   |||
  ////    \\\     |||      \\  ||| \\\// |||     |||      ||| \\\  |||
 //////////\\\    |||      //  |||  \\/  |||     |||      |||  \\\ |||
////        \\\   |||     //   |||       |||     |||      |||   \\\|||
///          \\\  |||||||//    |||       |||  |||||||||   |||    \\\||
//\\//\\//\\//\\//\\//\\///\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
  //\\//\\//\\//\\//\\///\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
if($_SESSION['userid']=='9016')
{
  if ($_POST['submit']=="Create New User") {

    $newUserName = filter_input(INPUT_POST,'username',FILTER_SANITIZE_SPECIAL_CHARS);
    $newUserID = filter_input(INPUT_POST,'userid', FILTER_VALIDATE_INT);
    $newPassword = filter_input(INPUT_POST,'password',FILTER_SANITIZE_SPECIAL_CHARS);
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    ///// DEFAULT VALUES
    $genreID='1';
    $description= "New Account";

    $createUserquery= "INSERT INTO creator(UserID,Password,UserName,Description,GenreID) VALUES(:userid,:password,:username,:description,:genreid)";
    $createUserStatement = $db->prepare($createUserquery);
    $createUserStatement->bindValue(':userid',$newUserID);
    $createUserStatement->bindValue(':username', $newUserName);
    $createUserStatement->bindValue(':password', $hashedPassword);
    $createUserStatement->bindValue(':description',$description);
    $createUserStatement->bindValue(':genreid',$genreID);
    $createUserStatement->execute();
    $usercreation=true;
  }
  else {
    $uploadgenre=true;
  }
  header("Location: adminhomepage.php");
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

  $currentPodcast=$_POST['currentPodcast'];
  $creatorID = substr($currentPodcast,0,4);

    if($_POST['submit']=="Save Edit"){
      //update
      if ($creatorID==$_SESSION['userid']) {
        $newTitle=$_POST['editTitle'];
        $newDescription=$_POST['editDescription'];
        $newGenreID=$_POST['genre'];
        $editQuery="UPDATE Podcast SET Title=:newTitle, Description=:newDescription, GenreID=:newGenreID WHERE PodcastID =:currentPodcast";
        $editStatement=$db->prepare($editQuery);
        $editStatement->bindValue(':newTitle',$newTitle);
        $editStatement->bindValue(':newDescription',$newDescription);
        $editStatement->bindValue(':newGenreID',$newGenreID);
        $editStatement->bindValue(':currentPodcast',$currentPodcast);
        $editStatement->execute();
        $test="Edit";
      }
      else {
        $error=true;
        $errorMessage="Your UserID doesn't match the one stored on the Podcast, please try again";
      }
    }
    else if($_POST['submit']=="Delete"){
      //delete
      $test="Delete";
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
      <?php if ($uploadpodcast==true): ?>
        <p><?=$PodcastID?></p>
        <p><?=$Title?></p>
        <p><?=$Description?></p>
        <p><?=$Genre?></p>
        <p><?=$SelectedGenreID?></p>
        <p><?=$uploadquery?></p>
      <?php elseif ($usercreation==true):?>
        <h4><?=$newUserName?></h4>
        <h4><?=$newUserID?></h4>
        <h4><?=$newPassword?></h4>
        <h4><?=$hashedPassword?></h4>
    <?php elseif($uploadgenre==true): ?>
        <h2>GENRE CREATED</h2>
    <?php endif ?>
      <?php if($_POST['submit']=="Edit Podcast" || $_POST['submit']=="Save Edit"): ?>
        <form class="editPodcast" action="process.php" method="post">
          <p>Current Title: <?=$podcast['Title']?></p>
          <label for="editTitle">Edit Title:</label>
          <input type="text" name="editTitle" value=""><br><br>
          <p>Current Description: <?=$podcast['Description']?></p>
          <label for="editDescription">Edit Description</label>
          <textarea name="editDescription" rows="8" cols="80"></textarea>
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
      <?php else: ?>
      <?php endif ?>
      <?php if(($_POST['submit']=="Delete Podcast" || $_POST['submit']=="Delete")):?>
        <form class="deletePodcast" action="process.php" method="post">
          <input type="hidden" name="currentPodcast" value="<?=$podcast['PodcastID']?>">
          <input class="btn btn-primary " type="submit" name="submit" value="Delete">
        </form>
      <?php endif ?>
      <p><?=$test?></p>
  </body>
</html>
