<?php
session_start();
require('connect.php');

if(!isset($_SESSION['username'])){
  header("Location: index.php");
}


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

  $uploadquery= "INSERT INTO podcast(PodcastID,Title,Description,GenreID) VALUES(:PodcastID,:Title,:Description,:GenreValue)";
  $uploadStatement = $db->prepare($uploadquery);
  $uploadStatement->bindValue(':PodcastID',$PodcastID);
  $uploadStatement->bindValue(':Title', $Title);
  $uploadStatement->bindValue(':Description', $Description);
  $uploadStatement->bindValue(':GenreValue', $GenreID['GenreID']);
  $uploadStatement->execute();

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


}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php if ($_SESSION['userid']!='9016'): ?>
      <p><?=$PodcastID?></p>
      <p><?=$Title?></p>
      <p><?=$Description?></p>
      <p><?=$Genre?></p>
      <p><?=$SelectedGenreID?></p>
      <p><?=$uploadquery?></p>
    <?php else: ?>
      <h4><?=$newUserName?></h4>
      <h4><?=$newUserID?></h4>
      <h4><?=$newPassword?></h4>
      <h4><?=$hashedPassword?></h4>
    <?php endif: ?>


  </body>
</html>
