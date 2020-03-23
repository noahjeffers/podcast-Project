<?php
session_start();
require('connect.php');
if($_SESSION['userid']!='9016')
  header("Location: index.php");

$query="SELECT * FROM creator WHERE UserName<>'ADMIN'";
$statement=$db->prepare($query);
$statement->execute();






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
      <form class="createuseraccount" action="process.php" method="post">
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
      <form class="" action="process.php" method="post">
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
            <p><?=$creator['UserName']?></p>
            <a href="#">Delete</a>
          </li>
        <?php endforeach ?>
      </ul>
    </div>

  </body>
</html>
