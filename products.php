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
if ($_SESSION['lang']=='ar') {
  //  echo 'done';
  require "lang/ar.php";
} else {
  require "lang/en.php";
}
?>

<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "index";
}
?>
<?php if ($action == 'index') : ?>
    <?php
    $stml = $connection->prepare('SELECT * FROM `products` ');
    $stml->execute();
    $products = $stml->fetchAll();
    ?>
    <h1><?=$lang['all_products']?></h1>
    <div   >
    <ul >
    <!-- <li >  <a class="nav-link" href="dashbord">
    <i class="fa-solid fa-backward-step fa-2x"></i>
    </a> </li> -->
      <li > <a  class="nav-link " href="products?action=create">
        <?php  $_SESSION['createflag']=1; ?>
      <i class="fa-solid fa-user-plus fa-2x"></i>
        </a> </li>
    </ul>
    </div>
    <div>

        <section>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?=$lang['product_name']?></th>
                        <th scope="col"><?=$lang['price']?></th>
                        <th scope="col"><?=$lang['added_by']?></th>
                        <th scope="col"><?=$lang['created_at']?></th>
                        <th scope="col"><?=$lang['control']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <th scope="row">1</th>
                            <td><?= $product['product_name'] ?></td>
                            <td><?= $product['product_price'] ?></td>
                            <td><?= $product['admin_name'] ?></td>
                            <td><?= $product['created_at'] ?></td>
                            <td>
                                <a class="btn btn-info" href="products?action=show&selection=<?= $product['product_id'] ?>">
                                    <?php
                                    $_SESSION['showflag'] = 1;
                                    ?>
                                    <i class="fa-solid fa-info" title="<?=$lang['info']?>"></i>
                                </a>
                                <a class="btn btn-warning" href="products?action=edit&selection=<?= $product['product_id'] ?>">
                                    <?php
                                    $_SESSION['editflag'] = 1;
                                    ?>
                                    <i class="fas fa-user-edit" title="<?=$lang['edit']?>"></i>
                                </a>
                                <a class="btn btn-danger" href="products?action=destroy&selection=<?=$product['product_id']?>">
                                <?php  $_SESSION['destroyflag'] = 1; ?>
                                <i class="fas fa-user-minus" title="<?=$lang['delete']?>"></i> 
                            </a>

                            </td>
                        <?php endforeach ?>
                        </tr>

                </tbody>
            </table>
        </section>

    <?php elseif ($action == 'edit') : ?>
        <h1> <?=$lang['edit_product']?></h1>
        <?php
        $product_id = $_GET['selection'];
        $flag=$_SESSION['editflag'];
        $inDp=1;
        if(intval($product_id)&&$flag==1){
            $_SESSION['editflag']=0;
            $stml=$connection->prepare("SELECT * FROM `products` WHERE product_id=? ");
            $stml->execute(array($product_id));
            $product=$stml->fetch();
            $count=$stml->rowCount();
            if($count==$inDp){
                ?>
                <section>
            <form method="POST" action="products?action=update" >
                <input type="hidden" value="<?=$product_id?>" name="product_id">
                <div class="mb-3">
                    <label for="exampleInputEmail1"><?=$lang['product_name']?></label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?=$product['product_name'] ?>" name="product_name" >

                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1"><?=$lang['price']?></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" value="<?=$product['product_price'] ?>" name="product_price" >
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1"><?=$lang['added_by']?></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" value="<?=$product['admin_name']?>" name="admin_name" >
                </div>
               <div  class="mb-3" >
                    <button type="submit" class="btn btn-primary"><?=$lang['edit']?></button>
                    <button type="button" class="btn btn-primary" onclick="back()"><?=$lang['back']?></button>
               </div>
                
            </form>
            <script>
                    function back() {
                        history.back()
                    }
                </script>
        </section>

                <?php
                 
            }else{
                echo '<script>history.back()</script>';
            }
        }else{
            echo '<script>history.back()</script>';
        }
        ?>
        
    <?php elseif ($action == 'update') : ?>
        <h1>update product</h1>
        <?php
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $product_id= $_POST['product_id'];
            $product_name= $_POST['product_name'];
            $product_price= $_POST['product_price'];
            $admin_name=$_POST['admin_name'];
            $stml=$connection->prepare(" UPDATE `products` 
            SET `product_name`=?, `product_price`=?, `admin_name`=? ,`created_at`=now()
            WHERE `product_id`=?
            ");
            $stml->execute(array($product_name,$product_price,$admin_name,$product_id));
            echo '<script>history.back()</script>'; 
        }
      
        
        ?>


    <?php elseif ($action == 'show') : ?>
        <h1><?=$lang['info']?> </h1>
        <?php
        $product_id = $_GET['selection'];
        $flag = $_SESSION['showflag'];
        $inDp = 1;
        if (intval($product_id) && $flag == 1) {
            $_SESSION['showflag'] = 0;
            $stml = $connection->prepare('SELECT * FROM `products` WHERE product_id=?  ');
            $stml->execute(array($product_id));
            $product = $stml->fetch();
            $count = $stml->rowCount();
            if ($count == $inDp) {
        ?>
                <section>
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1"><?=$lang['product_name']?></label>
                            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $product['product_name'] ?>" readonly>

                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1"><?=$lang['price']?></label>
                            <input type="text" class="form-control" id="exampleInputPassword1" value="<?= $product['product_price'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1"><?=$lang['added_by']?></label>
                            <input type="text" class="form-control" id="exampleInputPassword1" value="<?= $product['admin_name'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1"><?=$lang['created_at']?></label>
                            <input type="text" class="form-control" id="exampleInputPassword1" value="<?= $product['created_at'] ?>" readonly>
                        </div>
                        <button type="button" class="btn btn-primary"  onclick="back()"><?=$lang['back']?></button>
                    </form>
                    <script>
                    function back() {
                        history.back()
                    }
                    </script>
                    
                </section>
        <?php
            } else {
                echo '<script>history.back()</script>';
            }
        } else {
            echo '<script>history.back()</script>';
        }

        ?>
    <?php elseif ($action == 'destroy') : ?>
        <h1>destroy product</h1>
        <?php
        $product_id=$_GET['selection'];
        $flag=$_SESSION['destroyflag'];
        $inDp=1;
        if($flag==1){
            $_SESSION['destroyflag']=0;
            $stml = $connection->prepare("DELETE  FROM `products` WHERE product_id=?");
           $stml->execute(array($product_id));
            if ($count == $inDp) {
                echo 'user deleted';
            } else {
                echo '<script>history.back()</script>';
            }
        } else {
            echo '<script>history.back()</script>';
        }
        
         ?>



    <?php elseif ($action == 'store') : ?>
        <h1>store product</h1>
        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productname = $_POST['productname'];
            $price = $_POST['price'];
            $admin_name = $_SESSION['username'];
            $stml = $connection->prepare("INSERT INTO `products`(product_name,product_price,admin_name,created_at)
             VALUES(?,?,?,now())
             ");
            $stml->execute(array($productname, $price, $admin_name));
            echo '<script>history.back()</script>';
        }
        ?>
     

    <?php elseif ($action == 'create') : ?>
        <h1>Add product</h1>
        <section>

            <form method="POST" action="?action=store">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">product name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="productname">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Price</label>
                    <input type="number" class="form-control" id="exampleInputPassword1" name="price">
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </section>

    <?php else :   header('location:index'); ?>
    <?php endif ?>

    <?php include_once 'includes/footer.php' ?>