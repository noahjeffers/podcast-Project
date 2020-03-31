<?php
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
  </head>
  <body>
    <div class="createuser">
      <h3>User Creation</h3>
      <form class="createuseraccount" action="adminprocess.php" method="post">
        <label for="userid">UserID:</label>
        <input type="text" name="userid" value="">
        <label for="username">User Name:</label>
        <input type="text" name="username" value="">
        <label for="password">Password:</label>
        <input type="text" name="password" value="">
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
        <input type="text" name="genre" value="">
        <input type="submit" name="submit" value="Add Genre">
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
        <input type="text" name="editGenre" value="">
        <input type="submit" name="submit" value="Edit Genre">
        <input type="submit" name="submit" value="Delete Genre">
      </form>
    </div>
  </body>
</html>
