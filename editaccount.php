<?php
session_start();
require('connect.php');
if(!isset($_SESSION['username'])){
  header("Location: index.php");
}
$user = $_SESSION['username'];
$query = "SELECT genre FROM genre ";
$genrestatement = $db->prepare($query);
$genrestatement->execute();



?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>EDIT - <?=$user?> ACCOUNT</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      .jumbotron{
        text-align: center;
      }


    </style>
  </head>
  <body>
    <div class="jumbotron">
      <h2>Edit your Account</h2>
    </div>
    <div class="container">
      <div class="card border-dark mb-3 text-center" style="max-width: 18rem;">
            <h5 class="text-center">Edit User Name</h5>
          <div class="card-body">
            <form action="editaccount.php" method="post">
              <input type="text" name="username" value="<?=$_SESSION['username']?>">
              <input class="btn btn-primary" type="submit" name="submit" value="Update your UserName">
            </form>
          </div>
        </div>



      <form class=" card border-dark mb-3 style="max-width: 18rem;"" action="editaccount.php" method="post">
        <h3 class="text-center">Edit your Description</h3>
        <textarea name="description" rows="8" cols="30"><?=$_SESSION['description']?></textarea>
        <br><input type="submit" name="submit" value="Update your Description">
      </form>

      <form class="card " action="editaccount.php" method="post">
        <div class="card-body">
          <h3 class="text-center">Change the Genre of your Account</h3>
          <?php if ($genrestatement -> rowCount()<1):?>
              <h2 class="text-center">Error - No Genre found</h2>
          <?php else: ?>
            <?php foreach ($genrestatement as $genre): ?>
                <input type="radio" id="<?=$genre['genre']?>" name="genre" value="<?=$genre['genre']?>">
                <label for="<?=$genre['genre']?>"><?=$genre['genre']?></label>
              <?php endforeach ?>
              <br><small>(Undefined will be hidden on the homepage)</small>
          <?php endif ?>
          <br><input type="submit" name="upload" value="Change Genre">
        </div>
      </form>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>

</html>
