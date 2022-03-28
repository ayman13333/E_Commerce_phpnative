<?php require "config.php" ?>

<?php

 session_start();
 //this empty our session
 $_SESSION = array();
 $_SESSION['flag']=0;
if($_SERVER['REQUEST_METHOD']=='POST'  ){
  session_start() ;
  //echo "done";
  
   $email=$_POST['email'];
   $password= sha1($_POST['password']);
  $stml=$connection ->prepare('SELECT * FROM `users` WHERE `email`=? AND `password`=? AND `rule_id` !=2');
  $stml ->execute(array($email,$password));
  $row=$stml->fetch();
  $count=$stml->rowCount();
  echo $count;
  if($count==1){
    $_SESSION['id']=$row['id'];
    $_SESSION['username']=$row['username'];
    $_SESSION['password']=$row['password'];
    $_SESSION['email']=$row['emai'];
    $_SESSION['role']=$row['rule_id'];
    $_SESSION['fullname']=$row['fullname'];
   // $_SESSION['added_by']=$row['added_by'];
    $_SESSION['flag']=1;

    header('location:dashbord');
  }else{
   
    header('Location:index');
  }
}
?>

<?php
    if (isset($_GET['lang']) && $_GET['lang']=='ar'){
    //  echo 'done';
      require "lang/ar.php";
      $_SESSION['lang']='ar';
    } else{
      require "lang/en.php";
      $_SESSION['lang']='en';
    }
?>
<?php  include "includes/header.php" ?>

  <body>
    <h1><?=$lang['helloworld'] ?></h1>
    <div class="container">
   
    <form method="POST"  action="<?php $_SERVER['PHP_SELF'] ?>"  >
    
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label"> <?= $lang["email"] ;?></label>

    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">

    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">  <?= $lang["password"] ;?></label>

    <input type="password" class="form-control" id="exampleInputPassword1" name="password">

  </div>
  <!-- <div class="mb-3 form-check">
     <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label> 
  </div> -->
  <button type="submit" class="btn btn-primary"><?=$lang['submit']; ?></button>
</form>
<a href="?lang=en"  style="text-decoration: none; color:red">EN</a> | <a href="?lang=ar"   style="text-decoration: none; color:red">اللغة العربيه</a>
    </div>

 
 <?php include 'includes/footer.php'?>