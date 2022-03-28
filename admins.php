<?php session_start(); ?>
<?php require "config.php" ?>
<?php include_once "includes/header.php" ?>
<?php include "includes/navbar.php" ?>
<style>
    li {
        display: inline-block;
    }
</style>
<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'index';
}
?>
<?php if ($action == 'index') : ?>
    <h1>index page</h1>
    <?php
    $stml = $connection->prepare("SELECT * FROM `users` WHERE rule_id=1");
    $stml->execute();
    $admins = $stml->fetchAll();
    ?>
    <div>
        <ul>
            <li> <a class="nav-link" href="dashbord">
                    <i class="fa-solid fa-backward-step fa-2x"></i>
                </a> </li>
            <li> <a class="nav-link " href="admins?action=create">
                    <i class="fa-solid fa-user-plus fa-2x"></i>
                </a> </li>
        </ul>
    </div>
    <section>
        <table class="table">
            <thead>
                <tr>
                    <th>user name</th>
                    <th>password</th>
                    <th>email</th>
                    <th>full name</th>
                    <th>phone</th>
                    <th>gender</th>
                    <th>created_at</th>
                    <th>control</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin) : ?>
                    <tr>
                        <td> <?= $admin['username']; ?> </td>
                        <td> <?= $admin['password']; ?></td>
                        <td> <?= $admin['email']; ?> </td>
                        <td> <?= $admin['fullname']; ?> </td>
                        <td><?= $admin['phone']; ?> </td>
                        <td><?= $admin['gender']; ?> </td>
                        <td> <?= $admin['created_at']; ?></td>
                        <td>
                            <a href="?action=show&selection=<?= $admin['id'] ?>" class="btn btn-info">
                                <?php $_SESSION['showflag'] = 1; ?>
                                <i class="fa-solid fa-info " title="info"></i>
                            </a>
                            <a href="?action=edit&selection=<?= $admin['id'] ?>" class="btn btn-warning">
                                <?php $_SESSION['editflag'] = 1; ?>
                                <i class="fas fa-user-edit  " title="edit"></i>
                            </a>


                            <i class="fas fa-user-minus" title="delete"></i>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>

<?php elseif ($action == 'create') : ?>
    <h1>create</h1>
    <section>
        <form method="POST" action="?action=store">
            <div class="form-group">
                <label for="exampleInputEmail1">admin name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">admin email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">admin full name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="fullname">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">admin phone</label>
                <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="phone">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">admin gender</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="gender">
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </section>
<?php elseif ($action == 'store') : ?>
    <h1>store</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = sha1($_POST['password']);
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $rule_id = 1;
        $stml = $connection->prepare("INSERT  INTO `users` (username,password,email,fullname,phone,gender,created_at,rule_id)
             VALUES (?,?,?,?,?,?,now(),?)
             ");
        $stml->execute(array($username, $password, $email, $fullname, $phone, $gender, $rule_id));
        echo "<script>history.back()</script>";
    } else {
        echo "<script>history.back()</script>";
    }
    ?>

<?php elseif ($action == 'edit') : ?>
    <h1>edit</h1>
    <?php
    $admin_id = $_GET['selection'];
    $flag = $_SESSION['editflag'];
    if ($flag == 1) {
        $_SESSION['editflag']=0;
        $stml = $connection->prepare("SELECT * FROM `users`  WHERE id=?");
        $stml->execute(array($admin_id));
        $admin = $stml->fetch();
       // echo $admin['password'];
    ?>
        <section>
            <form method="POST" action="?action=update">
            <input type="hidden" value="<?= $admin['id'];?>"  name="userid">
                <div class="form-group">
                    <label for="exampleInputEmail1">admin name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  value="<?=$admin['username']?>" name="username">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="text" class="form-control" id="exampleInputPassword1"   name="newpassword">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  value="<?=$admin['email']?>" name="email">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin full name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  value="<?=$admin['fullname']?>" name="fullname">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin phone</label>
                    <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  value="<?=$admin['phone']?>" name="phone">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin gender</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  value="<?=$admin['gender']?>" name="gender">
                </div>

                <button type="submit" class="btn btn-primary">update</button>
            </form>
        </section>
    <?php
    } else {
        echo "<script>history.back()</script>";
    }
    ?>
<?php elseif ($action == 'update') : ?>
    <h1>update</h1>
    <?php
    if($_SERVER['REQUEST_METHOD']=='POST'){
      //  $_SESSION = array();
      $userid=$_POST['userid'];
      $username=$_POST['username'];
      $sucess=1;
     echo $newpassword=sha1($_POST['newpassword']) ;
      $email=$_POST['email'];
      $fullname=$_POST['fullname'];
      $phone=$_POST['phone'];
      $gender=$_POST['gender'];
        $stml = $connection->prepare(" UPDATE `users` 
        SET `username`=?,`password`=?,`email`=?,`fullname`=?,`phone`=?,`gender`=?
        WHERE id=?
         ");
        //   $stml=$connection->prepare(" UPDATE `users` 
        //   SET `username`=?, `email`=?, `fullname`=? ,`phone`=?
        //   WHERE `id`=?
        //   ");
        $stml->execute(array($username,$newpassword,$email,$fullname,$phone,$gender,$userid));
        $count=$stml->rowCount();
        if($count==$sucess){
           echo "updated successfully";
        }
       // $admin = $stml->fetch();
       //echo "<script>history.back()</script>";

    }
    else{
        echo "<script>history.back()</script>";
    }
   
     ?>

<?php elseif ($action == 'destroy') : ?>
    <h1>destroy</h1>

<?php elseif ($action == 'show') : ?>
    <h1>show</h1>
    <?php
    $flag = $_SESSION['showflag'];
    $admin_id = $_GET['selection'];
    if ($flag == 1) {
        $_SESSION['showflag']=0;
        $stml = $connection->prepare("SELECT * FROM `users`  WHERE id=?");
        $stml->execute(array($admin_id));
        $admin = $stml->fetch();
    ?>
        <section>
            <form>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $admin['username']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" value="<?= $admin['password']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $admin['email']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin full name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $admin['fullname']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin phone</label>
                    <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" readonly value="<?= $admin['phone']; ?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">admin gender</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $admin['gender']; ?>" readonly>
                </div>

                <button type="button" class="btn btn-primary">Back</button>
            </form>
        </section>

    <?php
    } else {
        echo "<script>history.back()</script>";
    }
    ?>

<?php else : header('location:index') ?>
<?php endif ?>
<?php include "includes/footer.php" ?>