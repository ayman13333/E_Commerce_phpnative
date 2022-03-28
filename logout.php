<?php
session_start();
session_unset();
//print_r($_SESSION);
 session_destroy();
//print_r($_SESSION);
header("location:index.php");
?>