<?php

// NOTHING TO SANITZE /////////////////////////////////////////////////////////////////////////////
// NO HTML TO VALIDATE

session_start();
unset($_SESSION['username']);
unset($_SESSION['password']);
session_destroy();
  header("Location: index.php");
//$output= $_SESSION['username'];
 ?>
