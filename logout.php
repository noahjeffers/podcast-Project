<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['password']);

  header("Location: index.php");
//$output= $_SESSION['username'];
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

  </body>
</html>
