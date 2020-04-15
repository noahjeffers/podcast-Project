<?php

// SANITIZED ///////////////////////////////////////////////////////////////////////////////////////////////////////
// HTML VALIDATED


session_start();
require('connect.php');


if(!isset($_SESSION['username'])){
  header("Location: index.php");
}

$genreListQuery = "SELECT genre FROM genre WHERE GenreID <> 1  ";
$genreListStatement = $db->prepare($genreListQuery);
$genreListStatement->execute();

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
    <title>PROCESS PAGE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
    body{
      background-color: black;
    }
    .jumbotron{
      background-color: grey;
      text-align: center;
      margin-bottom: 0;
    }
    .card:nth-child(even){
      background-color: orange;
    }
    .container{
      background-color: white;
    }
    .col-9{
      background: #CDCDCD;
    }
    .col-3{
      background: orange;
    }
    </style>
      </head>
      <body>
        <div class="container">
          <div class="jumbotron jumbotron-fluid">
              <h1 class="display-4">Network Name</h1>
              <p class="lead">We cover all of the topics, from News to Comedy to History. NETWORK NAME does it all</p>
          </div>
          <div >
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only"></span></a>
                  </li>
                  <?php if (isset($_SESSION['userid'])): ?>
                    <?php if ($_SESSION['username']=='ADMIN'): ?>
                      <li class="nav-item active">
                        <a class="nav-link" href="adminhomepage.php">Admin Page <span class="sr-only"></span></a>
                      </li>
                    <?php else: ?>
                      <li class="nav-item active">
                        <a class="nav-link" href="editaccount.php">Edit your Account <span class="sr-only"></span></a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link" href="upload.php">Upload a Podcast <span class="sr-only"></span></a>
                      </li>
                    <?php endif ?>
                  <?php endif ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Sort by Genre
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <?php if ($genreListStatement -> rowCount()<1):?>
                          <a class="dropdown-item" href="#">No Genre Found</a>
                      <?php else: ?>
                        <?php foreach ($genreListStatement as $genreList): ?>
                          <a class="dropdown-item" href="genre.php?genre=<?=$genreList['genre']?>"><?=$genreList['genre']?></a>
                        <?php endforeach ?>
                      <?php endif ?>
                    </div>
                  </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="search.php" method="post">
                  <input id="search" class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
                  <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="submit" value="Search">
                </form>
              </div>
            </nav>
          </div>
          <div class="container">


    <?php if($error==true): ?>
      <p><?=$errorMessage?></p>
    <?php endif ?>
      <?php if($_POST['submit']=="Edit Podcast" || $_POST['submit']=="Save Edit"): ?>
        <form class="editPodcast" action="process.php" method="post">
          <p>Current Title: <?=$podcast['Title']?></p>
          <label for="editTitle">Edit Title:</label>
          <input type="text" id="editTitle" name="editTitle" value="<?=$podcast['Title']?>"><br><br>
          <p>Current Description: <?=$podcast['Description']?></p>
          <label for="editDescription">Edit Description</label>
          <textarea name="editDescription" id="editDescription" rows="8" cols="80"><?=$podcast['Description']?></textarea>
          <input type="hidden" name="currentPodcast" value="<?=$podcast['PodcastID']?>">

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
          </div><br>
          <input class="btn btn-primary " type="submit" name="submit" value="Save Edit">
        </form>
        <a href="profile.php?creator=<?=$_SESSION['userid']?>">Back to your Profile</a><br><br>
          <a href="logout.php">Log Out</a>
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
        <a href="profile.php?creator=<?=$_SESSION['userid']?>">Back to your Profile</a>
          <a href="logout.php">Log Out</a>
      <?php endif ?>


      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
