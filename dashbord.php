<?php
session_start();
//echo $_SESSION['id'];
//this prevent user from escaping the login form throw url
//echo $_SESSION['flag'];
 if( $_SESSION['flag']==0){
     header("Location:index");
  }
?>
<?php  include_once "includes/header.php" ?>

<?php include "includes/navbar.php" ?>

<?php include_once 'includes/footer.php'?>