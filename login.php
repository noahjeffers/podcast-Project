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
  if(isset($_POST['username'])&& $login==false)
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
      $profile = "profile.php?creator=".$user['UserID'];

      header("Location: $profile");
    }
    elseif ($login==true) {
      $profile = "profile.php?creator=".$_SESSION['userid'];
      header("Location: $profile");
    }

  }
header("Location: index.php");
}

?>
