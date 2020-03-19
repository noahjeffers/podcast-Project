<?php
session_start();
require('connect.php');
if(!isset($_SESSION['username'])){
  header("Location: index.php");
}
//creates the podcastID by appending a series of relevant data together
$creatorid=$_SESSION['userid'];
$filename=$_POST['filename'];
$date=date("Y/m/d");
$PodcastID = $creatorid."-".$filename."-".$date;

$title = $_POST['title'];
$Description = $_POST['description'];

//retrieves the genreID based off the chosen genres
// this is then fed into the INSERT statement for the foreign key
$Genre = $_POST['genre'];
$genrequery = "SELECT GenreID FROM Genre WHERE Genre = :genre";
$genrestatement = $db->prepare($genrequery);
$genrestatement->bindValue(':genre',$Genre);
$genrestatement->execute();
$GenreID = $genrestatement->fetch();




// $query= "INSERT podcast(PodcastID,Title,Description,genre) VALUES(:PocastID, :Title, :Description,:GenreValue)";
// $statement = $db->prepare($query);
// $statement->bindValue(':PodcastID',$PodcastID);
// $statement->bindValue(':Title', $Title);
// $statement->bindValue(':Description', $Description);
// $statement->bindValue(':GenreValue', $GenreID['GenreID']);
// $statement->execute();









?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <p><?=$PodcastID?></p>
        <p><?=$title?></p>
            <p><?=$Description?></p>
                <p><?=$Genre?></p>
                <p><?=$GenreID['GenreID']?></p>
  </body>
</html>
