<?php
//Posted from the Podcast page
// inputs need to be sanitized
$link = $_POST['link'];
if(""==trim($_POST['name']))
{
  header("Location: $link");
}





?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <a href=<?=$link?>>Link</a>
    <p><?=$link?></p>
  </body>
</html>
