<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload</title>
  </head>
  <body>
    <div class="container">
      <form class="upload" action="process.php" method="post">
        <label for="filename">File Name:</label>
        <input type="text" name="filename" value="">
        <label for="description">Description:</label>
        <input type="text" name="description" value="">
        <input type="submit" name="upload" value="Upload File">
      </form>
    </div>
  </body>
</html>
