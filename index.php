<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<?php //include_once('layouts/header.php'); ?>
<?php include_once('layouts/admin_header.php'); ?>

<div class="page-login-inner">
  <div class="bg-admin"><img src="libs/images/admin-bg.jpg" style="width:100%" /></div>
  
  <div class="container-fluid">
<div class="login-page">
  <div class="logo-admin">
    <img src="libs/images/logo.png" alt="" title="" />
  </div>
    <div class="text-center">
       <h1>Welcome</h1>
       <p>Sign in to start your session</p>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="auth.php" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="name" class="form-control" name="username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name= "password" class="form-control">
        </div>
        <div class="form-group">
                <button type="submit" class="btn-lgin">Login</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
