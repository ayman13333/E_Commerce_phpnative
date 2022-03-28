<?php //session_start(); ?>
<style>
  .nav-item {
    margin-left: 10px;
  }

  /* .navbar-brand {
    margin-left: 346px;
  }

  .btn-outline-success {
    margin-left: 130px;
  } */
  .form-inline my-2 my-lg-0 {
    margin-left: 10px;
  }

  .btn-outline-success {
    margin-left: 130px;
  }
</style>
<?php
//header("Refresh:1");
if ($_SESSION['lang']=='ar') {
  //  echo 'done';
  require "lang/ar.php";
} else {
  require "lang/en.php";
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <!-- <a class="navbar-brand" href="#">Navbar</a> -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

      <li class="nav-item">

         <!-- <a class="nav-link" href="admins"><?php //echo isset($_SESSION['count']); ?></a>  -->

      </li>
      <li class="nav-item">
        <?php if(isset($_GET['owner'])  && $_GET['owner']==1 &&isset($_GET['count'])  && $_GET['count']==1 ): ?>
        <a class="nav-link" href="members?action=confirmlogout">  <?=$lang['members'] ?>  </a>
        <?php echo $_SESSION['count']; ?>
        <?php else: ?>
          <a class="nav-link" href="members"> <?=$lang['members'] ?> </a>
          <?php //echo isset($_SESSION['count']); ?>
          <?php endif ?>

      </li>
      <li class="nav-item">

        <a class="nav-link" href="products"> <?=$lang['products'] ?> </a>

      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $_SESSION['fullname']; ?>
        </a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php if($_SESSION['role']==1): ?>
          <a class="dropdown-item" href="members?action=confirm&owner=1"> <?= $lang['admins&moderators']?></a>
          <?php endif ?>
          <a class="dropdown-item" href="logout"> <?=$lang['logout'] ?></a>
          <!-- <div class="dropdown-divider"></div> -->
        </div>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="?lang=en">EN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?lang=ar" >العربيه</a>
      </li> -->



      <!-- <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="<?= $lang['search']?>" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><?= $lang['search']?> </button>
    </form>
  </div>


  <!-- <nav class="navbar navbar-light bg-light">
    <form class="form-inline">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </nav> -->
</nav>