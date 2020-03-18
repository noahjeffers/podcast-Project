<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}
$username=$_SESSION['username'];
$description=$_SESSION['description'];
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$username?> Profile</title>
  </head>
  <body>
    <a href="index.php">Home</a>
    <p><?= $description?></p>
  </body>
</html>
