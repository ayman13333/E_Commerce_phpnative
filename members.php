<?php
//header("Refresh:0");
session_start();
ob_start();
//$_SESSION['count']=0;
?>
<?php require "config.php" ?>
<?php include_once "includes/header.php" ?>
<?php include "includes/navbar.php" ?>
<style>
    .create li {
        margin-left: 982px;
    }

    .members a {
        margin-left: 991px;
        display: table-cell;

    }

    li {
        display: inline;
    }

    #confirm {
        display: block;
    }

    .modal {
        /* top: 0; */
        /* display: block; */
        margin-top: 120px;
        /* margin-left: 1316px; */
    }

    .modal-dialog {
        width: 216px;
    }
</style>
<!-- for language -->
<?php
if ($_SESSION['lang'] == 'ar') {
    //  echo 'done';
    require "lang/ar.php";
} else {
    require "lang/en.php";
}
?>

<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $_SESSION['count'] = 0;
    //include "includes/navbar.php";
    //  if(isset($_GET['owner']) &&$_GET['owner']==1){
    //     $_SESSION['count'] = 0;
    //   include "includes/navbar.php";
    // }
    //include "includes/navbar.php";
} else {
    $action = "index";
    // if( isset($_GET['owner']) && $_GET['owner']==1){
    //     $_SESSION['count'] = 0;
    //     include_once "includes/navbar.php";
    // }
    // include "includes/navbar.php";

}
?>
<?php if ($action == 'index') : ?>
    <?php
    $admin_flag = 0;
    $owner = 0;
    //$_SESSION['count']=0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //include "includes/navbar.php";
        $confirm_password = sha1($_POST['confirm']);
        if ($confirm_password == $_SESSION['password']) {
            $owner = $_POST['id'];
            $admin_flag = $_SESSION['adminflag'];
            echo  $_SESSION['count'] = 1;
            header('location:members?action=index&owner=1&count=1');
            //header("Refresh:0");
            // include "includes/navbar.php";
            // ob_start();
            //include "includes/navbar.php";
        } else {
            echo "you are not admin";
        }
    }
    //echo $_GET['owner'];
    //echo $_SESSION['count'] ;
    if (isset($_GET['owner'])  && $_GET['owner'] == 1 && isset($_GET['count'])  && $_GET['count'] == 1) {
        // echo 'done';
        $_SESSION['count'] = 1;
        $owner = 1;
        $admin_flag = 1;
        //header('location:members?action=index&owner=1');
        //ob_start();
        //include "includes/navbar.php";
    } else {
        // $_SESSION['count'] =0;
        //  include "includes/navbar.php";
    }

    $hidden = $_SESSION['role'] == 1 ? "" : "AND hidden =0";
    $role = isset($owner) && $owner == 1 ? "rule_id !=2" : "rule_id=2";
    $stml = $connection->prepare("SELECT * FROM `users` WHERE $role $hidden");
    $stml->execute();
    $users = $stml->fetchAll();
    ?>
    <div class="members">

        <ul>
            <li>
                <?php if($admin_flag==1): ?>
                <h1><?= $lang["All Admins"] ?> </h1>
                <?php else : ?>
                    <h1><?= $lang["All Members"] ?> </h1>
                    <?php endif ?>

            </li>
            <?php if ($admin_flag == 1) : ?>
                <li> <a class="nav-link " href="members?action=createadmin">
                        <?php $_SESSION['add_admin']=1; ?>
                        <i class="fa-brands fa-adn   fa-2x" title="<?= $lang['add_admin'] ?>"></i>
                    </a>
                <?php endif ?>
                <li> <a class="nav-link " href="members?action=create">
                    <?php $_SESSION['add_user']=1; ?>
                        <i class="fa-solid fa-user-plus fa-2x" title="<?= $lang['adduser'] ?>"></i>
                    </a>
                </li>
        </ul>

    </div>

    <section>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"><?= $lang['email'] ?></th>
                    <th scope="col"><?= $lang['fullname'] ?></th>
                    <th scope="col"><?= $lang['created_at'] ?></th>
                    <th scope="col"><?= $lang['added_by'] ?></th>
                    <th scope="col"><?= $lang['control'] ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- admin flag -->
                <?php //echo $_SESSION['adminflag']=0;
                ?>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <th scope="row"> <?= $lang['1'] ?></th>
                        <td><?= $user['email'] ?>
                            <?php if ($user['hidden'] == 1) : ?>
                                <span class="badge bg-danger"><?= $lang['Deleted'] ?></span>
                            <?php endif ?>

                        </td>
                        <td><?= $user['fullname'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td><?= $user['added_by'] ?></td>
                        <td>
                            <?php if ($user['hidden'] == 1) : ?>
                                <a class="btn btn-info" href="members?action=restore&selection=<?= $user['id'] ?>">
                                    <i class="fa-solid fa-trash-arrow-up" title="<?= $lang['restore'] ?>"></i>
                                </a>
                            <?php endif ?>

                            <a class="btn btn-info" href="members?action=show&selection=<?= $user['id'] ?>">
                                <?php $_SESSION['showflag'] = 1; ?>
                                <i class="fa-solid fa-info" title="<?= $lang['info'] ?>"></i>
                            </a>
                            <?php if ($_SESSION['role'] == 1) : ?>
                                <a class="btn btn-warning" href="members?action=edit&selection=<?= $user['id'] ?>">
                                    <?php $_SESSION['editflag'] = 1; ?>
                                    <i class="fas fa-user-edit" title="<?= $lang['edit'] ?>"></i>
                                </a>
                                <a class="btn btn-danger" href="members?action=confirm&selection=<?= $user['id'] ?>">
                                    <?php $_SESSION['destroyflag'] = 1; ?>
                                    <i class="fas fa-user-minus" title="<?= $lang['delete'] ?>"></i>
                                </a>
                            <?php else : ?>
                                <a class="btn btn-danger" href="members?action=confirm&selection=<?= $user['id'] ?>">
                                    <?php // $_SESSION['confirm'] = 0 
                                    ?>
                                    <i class="fas fa-trash" title="<?= $lang['softdelete'] ?>"></i>
                                </a>

                            <?php endif ?>
                        </td>
                    <?php endforeach ?>
                    </tr>

            </tbody>
        </table>
    </section>




<?php elseif ($action == 'create') : ?>
    <h1><?= $lang['adduser'] ?></h1>
    <?php //echo $_SESSION['add_user'];?>
    <!-- <div class="create">
        <ul>
            <li> <a class="nav-link " href="members?action=index" style="font-size:xx-large;">All Members</a> </li>
        </ul>
    </div> -->
    <section>

        <form method="POST" action="?action=store">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label"><?= $lang["username"] ?></label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"> <?= $lang["password"] ?></label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label"><?= $lang["email"] ?></label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">

            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"><?= $lang["fullname"] ?></label>
                <input type="text" class="form-control" id="exampleInputPassword1" name="fullname">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"><?= $lang["phone"] ?></label>
                <input type="number" class="form-control" id="exampleInputPassword1" name="phone">
            </div>

            <button type="submit" class="btn btn-primary" onclick="add()"><?= $lang["add"] ?></button>

            <?php if ($_SESSION['count'] == 0) : ?>
                <?php echo $_SESSION['count']; ?>
                <button type="button" class="btn btn-primary" onclick="back()"><?= $lang['back'] ?></button>
            <?php else : ?>
                <?php echo $_SESSION['count']; ?>
                <button type="button" class="btn btn-primary" onclick="back1()"><?= $lang['back'] ?></button>
            <?php endif ?>
        </form>
        <script>
            function add() {
                alert('added successfully');
            }

            function back() {
                history.back()
            }

            function back1() {
                window.location.href = "members?action=index&owner=1";
            }
        </script>
    </section>

<?php elseif ($action == 'createadmin') : ?>
    <h1><?= $lang['add_admin'] ?></h1>
  <?php  echo $_SESSION['add_admin']; ?>

    <section>

        <form method="POST" action="?action=store">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label"><?= $lang["adminname"] ?></label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"> <?= $lang["password"] ?></label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label"><?= $lang["email"] ?></label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">

            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"><?= $lang["fullname"] ?></label>
                <input type="text" class="form-control" id="exampleInputPassword1" name="fullname">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"><?= $lang["phone"] ?></label>
                <input type="number" class="form-control" id="exampleInputPassword1" name="phone">
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"><?= $lang["type"] ?></label>
               <select  name="type">
                   <option  value="admin"><?= $lang["admin"] ?> </option>
                   <option  value="moderator"><?= $lang["moderator"] ?> </option>
               </select>
            </div>

            <button type="submit" class="btn btn-primary" onclick="add()"><?= $lang["add"] ?></button>

            <?php if ($_SESSION['count'] == 0) : ?>
                <?php echo $_SESSION['count']; ?>
                <button type="button" class="btn btn-primary" onclick="back()"><?= $lang['back'] ?></button>
            <?php else : ?>
                <?php echo $_SESSION['count']; ?>
                <button type="button" class="btn btn-primary" onclick="back1()"><?= $lang['back'] ?></button>
            <?php endif ?>
        </form>
        <script>
            function add() {
                alert('added successfully');
            }

            function back() {
                history.back()
            }

            function back1() {
                window.location.href = "members?action=index&owner=1";
            }
        </script>
    </section>

<?php elseif ($action == 'store') : ?>
    <h1>store</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['add_user']==1 ) {
        echo 'done';
        $_SESSION['add_user']=0;
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $phone = $_POST['phone'];
        $fullname = $_POST['fullname'];
        $added_by = $_SESSION['username'];
        $stml = $connection->prepare("INSERT INTO `users` (username,password,email,fullname,phone,created_at ,rule_id,added_by)VALUES (?,?,?,?,?,now(),2,?)  ");
        $stml->execute(array($username, $password, $email, $fullname, $phone, $added_by));
        header('location:members');
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['add_admin']==1 ) {
        $_SESSION['add_admin']=0;
        $user= $_POST['username'];
        $email=$_POST['email'];
        $password = sha1($_POST['password']);
        $phone = $_POST['phone'];
        $fullname = $_POST['fullname'];
        $added_by = $_SESSION['username'];
        $type=$_POST['type'];
        $role;
        if($type=='admin'){
            $role=1;
        }
        elseif($type=='moderator'){
            $role=3;
        }
        $stml = $connection->prepare("INSERT INTO `users` (username,password,email,fullname,phone,created_at ,rule_id,added_by)VALUES (?,?,?,?,?,now(),?,?)  ");
        $stml->execute(array($username, $password, $email, $fullname, $phone, $role,$added_by));
        header('location:members?action=index&owner=1&count=1');


    }

    ?>


<?php elseif ($action == 'edit') : ?>
    <h1> <?= $lang['edit'] ?></h1>
    <?php
    $userid = $_GET['selection'];
    $flag = $_SESSION['editflag'];
    $inDp = 1;
    if (intval($userid) && $flag == 1) {
        $_SESSION['editflag'] = 0;
        $stml = $connection->prepare("SELECT * FROM `users` WHERE id=? ");
        $stml->execute(array($userid));
        $user = $stml->fetch();
        $count = $stml->rowCount();
        if ($count == $inDp) { ?>
            <div class="container">
                <form method="POST" action="?action=update">

                    <div class="mb-3">

                        <input type="hidden" value="<?= $user['id'] ?>" name="id">

                        <label for="exampleInputEmail1" class="form-label"> <?= $lang['username'] ?> </label>

                        <input type="text" class="form-control" value="<?= $user['username'] ?> " name="username">

                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"> <?= $lang['email'] ?></label>

                        <input type="text" class="form-control" value="<?= $user['email'] ?>" name="email">

                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"> <?= $lang['password'] ?></label>

                        <input type="hidden" class="form-control" value="<?= $user['password'] ?>" name="oldpassword">
                        <input type="text" class="form-control" name="newpassword">

                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"> <?= $lang['fullname'] ?></label>

                        <input type="text" class="form-control" value="<?= $user['fullname'] ?>" name="fullname">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"> <?= $lang['phone'] ?></label>

                        <input type="text" class="form-control" value="<?= $user['phone'] ?>" name="phone">

                    </div>
                    <button type="submit" class="btn btn-primary"><?= $lang['edit'] ?></button>

                    <?php if ($_SESSION['count'] == 0) : ?>
                        <?php echo $_SESSION['count']; ?>
                        <button type="button" class="btn btn-primary" onclick="back()"><?= $lang['back'] ?></button>
                    <?php else : ?>
                        <?php echo $_SESSION['count']; ?>
                        <button type="button" class="btn btn-primary" onclick="back1()"><?= $lang['back'] ?></button>
                    <?php endif ?>

                </form>
                <script>
                    function back() {
                        history.back();
                    }

                    function back1() {
                        //   history.back()
                        window.location.href = "members?action=index&owner=1";
                    }
                </script>

            </div> <?php } else {
                    echo '<script>history.back()</script>';
                }
            } else {
                echo '<script>history.back()</script>';
            }
                    ?>

<?php elseif ($action == 'update') : ?>
    <h1>update</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
        $stml = $connection->prepare(" UPDATE `users` 
        SET `username`=?, `email`=?, `fullname`=? ,`phone`=? ,`password`=?
        WHERE `id`=?
        ");
        $stml->execute(array($username, $email, $fullname, $phone, $password, $id));
        echo '<script>history.back()</script>';
    }

    ?>


<?php elseif ($action == 'show') : ?>
    <h1><?= $lang['info'] ?></h1>
    <?php
    echo $_SESSION['count'];
    $userid = $_GET['selection'];
    $flag = $_SESSION['showflag'];
    $inDp = 1;
    if (intval($userid) && $flag == 1) {
        $_SESSION['showflag'] = 0;
        $stml = $connection->prepare("SELECT * FROM `users` WHERE id=? ");
        $stml->execute(array($userid));
        $user = $stml->fetch();
        $count = $stml->rowCount();
        if ($count == $inDp) { ?>
            <div class="container">
                <form>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label"><?= $lang['username'] ?></label>

                        <input type="email" class="form-control" value="<?= $user['username'] ?>" readonly>

                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><?= $lang['email'] ?></label>

                        <input type="text" class="form-control" value="<?= $user['email'] ?>" readonly>

                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><?= $lang['fullname'] ?></label>

                        <input type="text" class="form-control" value="<?= $user['fullname'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><?= $lang['phone'] ?></label>

                        <input type="text" class="form-control" value="<?= $user['phone'] ?>" readonly>

                    </div>
                    <?php if ($_SESSION['count'] == 0) : ?>
                        <?php //echo $_SESSION['count'];
                        ?>
                        <button type="button" class="btn btn-primary" onclick="back()"><?= $lang['back'] ?></button>
                    <?php else : ?>
                        <?php echo $_SESSION['count']; ?>
                        <button type="button" class="btn btn-primary" onclick="back1()"><?= $lang['back'] ?></button>
                    <?php endif ?>
                </form>

            </div>


            <script>
                function back() {
                    history.back()
                    //window.location.href = "members?action=index&owner=1";
                }

                function back1() {
                    //   history.back()
                    window.location.href = "members?action=index&owner=1";
                }
            </script>


    <?php } else {
            echo '<script>history.back()</script>';
        }
    } else {
        echo '<script>history.back()</script>';
    }
    ?>

<?php elseif ($action == 'confirm') : ?>
    <?php
    if ($_SESSION['role'] == 3) {
        $id = $_GET['selection'];
        $action = 'soft';
    } elseif ($_SESSION['role'] == 1 && isset($_GET['owner'])) {
        $_SESSION['adminflag'] = 1;
        $_SESSION['countflag'] = 1;
        $id = $_GET['owner'];
        $action = 'index';
    } elseif ($_SESSION['role'] == 1  && isset($_GET['selection'])) {
        $id = $_GET['selection'];
        $action = 'destroy';
    }

    ?>
    <section>
        <form method="POST" action="?action=<?= $action ?>">

            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="modal" tabindex="-1" id="confirm">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= $lang['password_confirm'] ?> </h5>
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                        </div>
                        <div class="modal-body">
                            <input type="text" placeholder="<?= $lang['enter_password'] ?>" name="confirm">
                        </div>
                        <div class="modal-footer">

                            <?php if ($_SESSION['count'] == 0) : ?>
                                <?php echo $_SESSION['count']; ?>
                                <button type="button" class="btn btn-primary" onclick="back()"><?= $lang['close'] ?></button>
                            <?php else : ?>
                                <?php echo $_SESSION['count']; ?>
                                <button type="button" class="btn btn-primary" onclick="back1()"><?= $lang['close'] ?></button>
                            <?php endif ?>


                            <button type="submit" class="btn btn-primary"> <?= $lang['password'] ?></button>
                        </div>

                    </div>
                </div>
            </div>

        </form>
        <script>
            function back() {
                history.back();
            }

            function back1() {
                window.location.href = "members?action=index&owner=1";
            }
        </script>
    </section>

<?php elseif ($action == 'soft') : ?>
    <?php
    $id = $_POST['id'];
    $confirm_password = sha1($_POST['confirm']);
    $admin_password = $_SESSION['password'];
    $inDp = 1;
    if ($confirm_password == $admin_password) {
        if (intval($id)) {
            $stml = $connection->prepare("  UPDATE `users` SET `hidden`=1  WHERE `id`=?
            ");
            $stml->execute(array($id));
            header('location:members');
        } else {
            echo '<script>history.back()</script>';
        }
    } else {
        echo "wrong password";
    }
    ?>
    <section>
    <?php elseif ($action == 'restore') : ?>
        <?php
        $id = $_GET['selection'];
        if (intval($id)) {
            $stml = $connection->prepare("  UPDATE `users` SET `hidden`=0  WHERE `id`=?
            ");
            $stml->execute(array($id));
            // echo 'done';
            echo '<script>history.back()</script>';
            // header('location:members');
            //echo 'done';
        } else {
            echo '<script>history.back()</script>';
        }
        ?>

    <?php elseif ($action == 'destroy') : ?>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $confirm_password = sha1($_POST['confirm']);
            if ($confirm_password == $_SESSION['password']) {
                $confirm = 1;
            } else {
                $confirm = 0;
            }
        }

        // $id = $_GET['selection'];
        $inDp = 1;
        $flag = $_SESSION['destroyflag'];
        if (intval($id) && $flag == 1  && $confirm == 1) {
            $_SESSION['destroyflag'] = 0;
            $stml = $connection->prepare("  DELETE FROM `users` WHERE `id`=?
        ");
            $stml->execute(array($id));
            $count = $stml->rowCount();
            if ($count == $inDp) {
                // echo '<script>history.back()</script>';
                header('location:?action=index');
            } else {
                echo '<script>history.back()</script>';
            }
        } else {
            echo 'wrong password';
        }

        ?>

    <?php elseif ($action == 'confirmlogout') : ?>

        <div class="modal" tabindex="-1" id="confirm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure you want to logout</h5>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="back1()">back</button>
                        <button type="button" class="btn btn-primary" onclick="members()">log out</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function members() {
                window.location.href = "members?action=index";
            }

            function back1() {
                window.location.href = "members?action=index&owner=1& count=1";
            }
        </script>

    <?php else :   header('location:index'); ?>

    <?php endif ?>

    <?php include_once 'includes/footer.php' ?>