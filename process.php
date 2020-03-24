<?php
session_start();
require('connect.php');

if(!isset($_SESSION['username'])){
  header("Location: index.php");
}

$usercreation=false;
$uploadgenre=false;
$uploadpodcast=false;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//CREATORS///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_SESSION['userid']!='9016')
{
  //creates the podcastID by appending a series of relevant data together
  $creatorid=$_SESSION['userid'];
  $filename=$_POST['filename'];
  $date=date("Y/m/d");
  $PodcastID = $creatorid."-".$filename."-".$date;

  $Title = $_POST['title'];//sanitized\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  $Description = $_POST['description'];//sanitized\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

  //retrieves the genreID based off the chosen genres
  // this is then fed into the INSERT statement for the foreign key
  $Genre = $_POST['genre'];
  $genrequery = "SELECT GenreID FROM Genre WHERE Genre = :genre";
  $genrestatement = $db->prepare($genrequery);
  $genrestatement->bindValue(':genre',$Genre);
  $genrestatement->execute();
  $GenreID = $genrestatement->fetch();
  $SelectedGenreID = $GenreID['GenreID'];

  // $uploadquery= "INSERT INTO podcast(PodcastID,Title,Description,GenreID) VALUES(:PodcastID,:Title,:Description,:GenreValue)";
  // $uploadStatement = $db->prepare($uploadquery);
  // $uploadStatement->bindValue(':PodcastID',$PodcastID);
  // $uploadStatement->bindValue(':Title', $Title);
  // $uploadStatement->bindValue(':Description', $Description);
  // $uploadStatement->bindValue(':GenreValue', $GenreID['GenreID']);
  // $uploadStatement->execute();
  $uploadpodcast=true;
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



}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
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
  </body>


  <form class="upload" action="process.php" method="post">
      <label for="filename">File Name:</label>
    <input type="text" name="filename" value="">
    <label for="title">Episode Title: </label>
    <input type="text" name="title" value="">

    <label for="description">Description:</label>
    <textarea name="description" rows="8" cols="80"></textarea>
    <div class="genres">
      <select class="genre" name="genre">
        <?php if ($genrestatement -> rowCount()<1):?>
          <option value="-1">No Genre Found</option>
            <h2>Error - No Genre found</h2>
        <?php else: ?>
          <?php foreach ($genrestatement as $genre): ?>
            <option value="<?=$genre['genreid']?>"><?= $genre['genre']?></option>
          <?php endforeach; ?>
        <?php endif ?>
      </select>
      <br><input type="submit" name="upload" value="Upload File">
    </div>
  </form>

</html>
