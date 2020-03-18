<?php
  require('connect.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Log In</title>
  </head>
  <body>
    <div class="container">
      <label for="username">User Name:</label>
        <input type="text" name="username" value="">
      <label for="password">Password: </label>
        <input type="text" name="password" value="">
        <button type="submit" name="submit">Log In</button>
    </div>

  </body>
</html>
