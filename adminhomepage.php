<?php

// NOTHING TO SANITZE ////////////////////////////////////////////////////////////////////////////////////////////
// HTML VALIDATED

session_start();
require('connect.php');
if($_SESSION['username']!='ADMIN')
  header("Location: index.php");

$query="SELECT * FROM creator WHERE UserName<>'ADMIN'";
$statement=$db->prepare($query);
$statement->execute();

$genreQuery="SELECT Genre,GenreID FROM Genre";
$genreStatement=$db->prepare($genreQuery);
$genreStatement->execute();

$genreQuerys="SELECT Genre,GenreID FROM Genre";
$genreStatements=$db->prepare($genreQuery);
$genreStatements->execute();



// Add Genre Functionality
// Delete User Account

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Administrative Page</title>
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
              <h1 class="display-4">Upload</h1>
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
          <div class="">


    <div class="createuser">
      <h3>User Creation</h3>
      <form class="createuseraccount" action="adminprocess.php" method="post">
        <label for="username">User Name:</label>
        <input type="text" id="username" name="username" value="">
        <label for="password">Password:</label>
        <input type="text" id="password" name="password" value="">
        <input type="submit" name="submit" value="Create New User">
      </form>
    </div>
    <div class="">
      <h3>Add a Genre</h3>
      <h5>Existing Genres</h5>
      <ul>
        <?php foreach ($genreStatement as $genre):?>
          <li><?=$genre['Genre']?> </li>
        <?php endforeach ?>
      </ul>
      <form class="" action="adminprocess.php" method="post">
        <label for="genre"></label>
        <input type="text" id="genre" name="genre" value="">
        <input type="submit" name="submit" value="Add Genre">
      </form>
    </div>

    <div class="genres">
      <h3>Delete or Edit Genre</h3>
      <form class="deleteOrEditGenre" action="adminprocess.php" method="post">
        <select class="genre" name="genre">
          <?php if ($genreStatements -> rowCount()<1):?>
            <option value="-1">No Genre Found</option>
              <h2>Error - No Genre found</h2>
          <?php else: ?>
            <?php foreach ($genreStatements as $genre): ?>
              <option value="<?=$genre['GenreID']?>"><?= $genre['Genre']?></option>
            <?php endforeach ?>
          <?php endif ?>
        </select>
        <label for="editGenre">Edit Selected Genre:</label>
        <input type="text" id="editGenre" name="editGenre" value="">
        <input type="submit" name="submit" value="Edit Genre">
        <input type="submit" name="submit" value="Delete Genre">
      </form>
    </div>
    <div class="delete">
      <h3>Delete User</h3>
      <ul>
        <?php foreach($statement as $creator): ?>
          <li>
            <small><?=$creator['UserID']?></small>
            <p><?=$creator['UserName']?></p>
            <a href="#">Delete</a>
          </li>
        <?php endforeach ?>
      </ul>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
