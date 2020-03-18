<?php
require('connect.php');
session_start();
$creator = ' ';

if (isset($_GET['creator'])) {
  $creator = $_GET['creator'];
  $query = "SELECT username FROM creator ";
  $statement = $db->prepare($query);
  $statement->execute();
}
else{
  header("Location: index.php");
}


if(isset($_SESSION['username'])){
  $username=$_SESSION['username'];
  $description=$_SESSION['description'];
}










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
  </body>
</html>
