<?php
require('connect.php');
session_start();
$creator = ' ';

if (isset($_GET['creator'])) {
  $creator = $_GET['creator'];
  $query = "SELECT username,userid FROM creator WHERE UserName = :creator ";
  $statement = $db->prepare($query);
  $statement->bindValue(':creator',$creator);
  $statement->execute();
  $user = $statement->fetch();
}
else{
  //header("Location: index.php");
}


if(isset($_SESSION['username'])){
  $username=$_SESSION['username'];
}
$podcastquery = "SELECT title, description FROM Podcast WHERE podcastID LIKE  :something  ";
$podcaststatement = $db->prepare($podcastquery);
$other = $user['userid'];
$something ='"'.$other.'%'.'"';
$podcaststatement ->bindValue(':something',$something);
$podcaststatement->execute();




 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$creator?> Profile</title>
  </head>
  <body>
    <a href="index.php">Home</a>
    <p><?=$creator?></p>

    <div class="Podcasts">
      <?php if ($podcaststatement -> rowCount()<1):?>
          <h2>Error - No Podcasts found</h2>
          <h2><?=$something?></h2>
      <?php else: ?>
        <?php foreach ($podcaststatement as $podcast): ?>
          <li><?=$podcast['Title']?></li>

          <?php endforeach ?>
      <?php endif ?>
    </div>
  </body>
</html>
