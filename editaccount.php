<?php

// SANITZED ////////////////////////////////////////////////////////////////////////////////////////////

session_start();
require('connect.php');
if(!isset($_SESSION['username'])){
  header("Location: index.php");
}
$warning=false;
//Page Load






//this page will deal with image upload
//and updating the creator database


if($_POST){
    $newUserName=filter_input(INPUT_POST,'username',FILTER_SANITIZE_SPECIAL_CHARS);  ////////////////////////////////////////////////////////////////////////////////////////////
    if(strtolower($newUserName)=='admin'){
      $warning=true;
    }else{
      $newDescription=filter_input(INPUT_POST,'description',FILTER_SANITIZE_SPECIAL_CHARS); ////////////////////////////////////////////////////////////////////////////////////////////
      $newGenre = filter_input(INPUT_POST,'genre',FILTER_VALIDATE_INT); ////////////////////////////////////////////////////////////////////////////////////////////
      //retrieve the GenreID based off the selected genre radio button
      $Genre = filter_input(INPUT_POST,'genre',FILTER_VALIDATE_INT); ////////////////////////////////////////////////////////////////////////////////////////////
      $genrequery = "SELECT GenreID FROM Genre WHERE Genre = :genre";
      $genrestatement = $db->prepare($genrequery);
      $genrestatement->bindValue(':genre',$Genre);
      $genrestatement->execute();
      $GenreID = $genrestatement->fetch();
      $SelectedGenreID = $GenreID['GenreID'];
      $userID=$_SESSION['userid'];

      $updatequery = "UPDATE creator SET UserName = :username, Description = :description, GenreID = :genre WHERE UserID = $userID";
      $updatestatement=$db->prepare($updatequery);
      $updatestatement->bindValue(':username',$newUserName);
      $updatestatement->bindValue(':description', $newDescription);
      $updatestatement->bindValue(':genre',$SelectedGenreID);
      $updatestatement->execute();

      $_SESSION['username']=$newUserName;
      $_SESSION['description']=$newDescription;

    }
}
  $user = $_SESSION['username'];
  $query = "SELECT genre FROM genre ";
  $genrestatement = $db->prepare($query);
  $genrestatement->execute();

  $currentGenreID=$_SESSION['genreid'];
  $query = "SELECT genre FROM genre WHERE GenreID = :genreID ";
  $secondgenrestatement = $db->prepare($query);
  $secondgenrestatement->bindValue(':genreID',$currentGenreID);
  $secondgenrestatement->execute();
  $currentGenre=$secondgenrestatement->fetch();


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>EDIT - <?=$user?> - ACCOUNT</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      .jumbotron{
        text-align: center;
      }
    </style>
  </head>
  <body>

    <div class="jumbotron">
      <h1><?=$_SESSION['username']?></h1>
      <h2>Edit your Account</h2>
    </div>
    <div class="container">
      <form class="" action="editaccount.php" method="post">
        <div class="card-deck">
          <div class="card">
            <div class="card-header">Edit User Name</div>
              <div class="card-body">
                <h5 class="card-title">New User Name</h5>
                  <input type="text" name="username" value="<?=$_SESSION['username']?>">
                <br>
                <br>
                <p>Your user name can contain spaces, but this is what you will use to log in.</p>
              </div>
          </div>
          <div class="card">
            <div class="card-header">
              Edit your Description
            </div>
            <div class="card-body">
              <h5 class="card-title">New Description</h5>
              <p class="card-text">Max 2000 characters</p>
                <h3 class="text-center"></h3>
                <textarea name="description" rows="8" cols="30"><?=$_SESSION['description']?></textarea>

            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">Change the Genre of your Account</div>
          <div class="card-body">
                <?php if ($genrestatement -> rowCount()<1):?>
                    <h2 class="text-center">Error - No Genre found</h2>
                <?php else: ?>
                  <?php foreach ($genrestatement as $genre): ?>
                    <?php if($currentGenre['genre']==$genre['genre']):?>
                        <input type="radio" checked id="<?=$genre['genre']?>" name="genre" value="<?=$genre['genre']?>">
                      <label for="<?=$genre['genre']?>"><?=$genre['genre']?></label>
                    <?php else: ?>
                      <input type="radio" id="<?=$genre['genre']?>" name="genre" value="<?=$genre['genre']?>">
                      <label for="<?=$genre['genre']?>"><?=$genre['genre']?></label>
                    <?php endif ?>
                  <?php endforeach ?>
                    <br><small>(Undefined will be hidden on the homepage)</small>
                <?php endif ?>
          </div>
        </div>
        <input class="btn btn-primary " type="submit" name="submit" value="Update your Account">
        <a class="btn btn-warning "href="profile.php?creator=<?=$_SESSION['userid']?>">Cancel</a>
      </form>
      <div class="card">
        <div class="card-header">Upload an Image</div>
        <div class="card-body">
          <form action="uploadimage.php"method='post' enctype='multipart/form-data'>
             <label for='image'>Image Filename:</label>
             <input type='file' name='image' id='image'>
             <input class="btn btn-primary "type='submit' name='submit' value='Upload Image'>
             <input class="btn btn-warning"type="submit" name="submit" value="Delete Image">
         </form>
        </div>
    </div>
    <?php if($warning==true): ?>
      <?php echo "<script type='text/javascript'>alert('The name you have selected is invalid');</script>"; ?>
    <?php endif ?>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
