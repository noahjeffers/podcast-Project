<?php
  session_start();
  require('connect.php');
  $login = false;
  $note="You are already logged in under another account. Log out and try again.";
if(isset($_SESSION['username'])){
  $login=true;
}
else {
  /// FILTER AND strtolower NEEDED/////////////////////////////////////////////////////////////////////////////////////
  if(isset($_POST['username']))
  {
    $username = strtolower(filter_input(INPUT_POST,"username",FILTER_SANITIZE_SPECIAL_CHARS));
    $password = $_POST["password"];//HASH Password?

    $query = "SELECT UserID, UserName, Description, Password, GenreID FROM creator WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username',$username);
    $statement->execute();
    $user=$statement->fetch();
    $userHashedPassword=$user['Password'];

    if (password_verify($password,$userHashedPassword)) {
      $_SESSION['userid']=$user['UserID'];
      $_SESSION['username']=$user['UserName'];
      $_SESSION['description']=$user['Description'];
      $_SESSION['genreid']=$user['GenreID'];
      //header("Location: index.php");
    }

  }

}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Log In</title>
  </head>
  <body>
    <?php  if(isset($_SESSION['username'])):?>
      <h2>You are already signed in</h2>
      <a href="profile.php">Profile Page</a>
      <a href="index.php">Home</a>
      <?php if($login==true); ?>
        <p><?=$note?></p>
        <a href="logout.php">Log Out</a>
        <!-- <p><?=$_SESSION['genreid']?></p> -->
    <?php else:?>
      <p>Please try again</p>
      <div class="container">
        <form class="login" action="login.php" method="post">
          <label for="username">User Name:</label>
            <input type="text" name="username" value="">
          <label for="password">Password: </label>
            <input type="text" name="password" value="">
            <button type="submit" name="submit">Log In</button>
        </form>
      </div>
    <?php endif?>
  </body>
</html>
