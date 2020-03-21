<?php
session_start();
if($_SESSION['userid']!='9016')
  header("Location: index.php");










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

  </body>
</html>
