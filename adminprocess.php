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
if($_SESSION['username']=='ADMIN')
{
  if ($_POST['submit']=="Create New User") {

    $newUserName = filter_input(INPUT_POST,'username',FILTER_SANITIZE_SPECIAL_CHARS);
    $newPassword = filter_input(INPUT_POST,'password',FILTER_SANITIZE_SPECIAL_CHARS);
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    ///// DEFAULT VALUES
    $genreID='1';
    $description= "New Account";

    $createUserquery= "INSERT INTO creator (Password,UserName,Description,GenreID) VALUES(:password,:username,:description,:genreid)";
    $createUserStatement = $db->prepare($createUserquery);
    $createUserStatement->bindValue(':username', $newUserName);
    $createUserStatement->bindValue(':password', $hashedPassword);
    $createUserStatement->bindValue(':description',$description);
    $createUserStatement->bindValue(':genreid',$genreID);
    $createUserStatement->execute();
    $usercreation=true;
  }
  if($_POST['submit']=="Add Genre") {
    if (""==trim($_POST['genre'])) {
      //redirect that isnt working ///////////////////////////////////////////////////////////////////////////
      header("Location: adminhomepage.php");
    }
    $newGenre = $_POST['genre'];
    $genreQuery="INSERT INTO Genre(Genre) VALUES(:genre)";
    $genreStatement=$db->prepare($genreQuery);
    $genreStatement->bindValue(':genre',$newGenre);
    $genreStatement->execute();
  }
  if ($_POST['submit']=="Edit Genre") {

    $editedGenre=$_POST['editGenre'];
    $SelectedGenreID =$_POST['genre'];
    $genreQuery="UPDATE Genre SET Genre=:genre WHERE GenreID =:genreID";
    $genreStatement=$db->prepare($genreQuery);
    $genreStatement->bindValue(':genre',$editedGenre);
    $genreStatement->bindValue(':genreID',$SelectedGenreID);
    $genreStatement->execute();
  }
  if ($_POST['submit']=="Delete Genre") {
    $SelectedGenreID =$_POST['genre'];
    $genreQuery="DELETE FROM Genre WHERE GenreID =:genreID";
    $genreStatement=$db->prepare($genreQuery);
    $genreStatement->bindValue(':genreID',$SelectedGenreID);
    $genreStatement->execute();
  }
  header("Location: adminhomepage.php");
}
?>
