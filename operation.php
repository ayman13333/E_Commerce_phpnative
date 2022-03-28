<?php
//  echo $_GET['email']."<br>";
//  echo $_GET['password']."<br>";
session_start();
echo "Hello ".$_SESSION['user_name']."<br>";
echo $_SERVER['SERVER_NAME']."<br>";
echo __FILE__."<br>";


?>
<a href="logout.php">logout</a>