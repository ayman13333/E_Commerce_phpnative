<?php
try{
    $dsn="mysql:host=localhost;dbname=php66";
$username="root";
$password="";
$connection=new PDO($dsn,$username,$password);
// echo "connect";
}catch(PDOException $e){
   echo $e->getMessage();
}


?>