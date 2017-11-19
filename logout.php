<?php session_start(); ?>

<?php
  unset($_SESSION['account']);
  unset($_SESSION['is_admin']);
  if(isset($_SESSION['regist_account'])){
    unset($_SESSION['regist_account']);
  }
  if(isset($_SESSION['regist_is_admin'])){
    unset($_SESSION['regist_is_admin']);
  }
  if(isset($_SESSION['regist_name'])){
    unset($_SESSION['regist_name']);
  }
  if(isset($_SESSION['regist_email'])){
    unset($_SESSION['regist_email']);
  }
  if(isset($_SESSION['login_account'])){
    unset($_SESSION['login_account']);
  }
?>
  <div class="transport">
    <p class="notice">logging out...</p>
    <meta http-equiv=REFRESH CONTENT=1;url=index.php>
  </div>
<link rel="stylesheet" href="all.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
