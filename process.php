<?php
session_start();
require('connect.php');
if(!isset($_SESSION['username']))
{
  header("Location: index.php");
}
$creatorname=$_SESSION['userid'];
$filename=$_POST['filename'];
$date=date("d/m/Y");
$PodcastID = $creatorname."-".$filename."-".$date;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <p><?=$PodcastID?></p>
  </body>
</html>
