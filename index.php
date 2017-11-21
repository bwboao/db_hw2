<?php session_start(); ?>

<?php
  include("connect_database.php");
  if(isset($_SESSION['regist_account'])){
    unset($_SESSION['regist_account']);
  }
  if(isset($_SESSION['regist_name'])){
    unset($_SESSION['regist_name']);
  }
  if(isset($_SESSION['regist_name'])){
    unset($_SESSION['regist_name']);
  }

  if(isset($_POST['account'])){
    $_SESSION['login_account']=$_POST['account'];
    $account=$_POST['account'];//for sql
    $password=$_POST['password'];
    $hash_password=hash('sha256', $password);
    $sql_find_account = "SELECT * FROM people WHERE account='$account'";
    $people_rs = $db->prepare($sql_find_account);
    $people_rs->execute();
    $table = $people_rs->fetch();

    $needto_output = array();
    $needto_reinput = 0;

    if($_SESSION['login_account'] == null){
      array_push($needto_output, "account can't be null");
      $needto_reinput = 1;
    }
    if($password == null){
      array_push($needto_output, "password can't be null");
      $needto_reinput = 1;
    }
    if($_SESSION['login_account'] != $table[0]){
      array_push($needto_output, "account doesn't be exist");
      $needto_reinput = 1;
    }
    if($hash_password != $table[1]){
      array_push($needto_output, "password isn't correct");
      $needto_reinput = 1;
    }
     
    if($needto_reinput == 1){
      ?><div class="transport">
      <p class="notice">Login failed</p><?php
      foreach($needto_output as $key => $value){
        ?><p class="alert" style="text-align:start">><?php echo "$value"; ?></p><?php
      }
      ?></div>
      <meta http-equiv=REFRESH CONTENT=2;url=index.php><?php
      unset($needto_output);
    }
    else{
      $_SESSION['account'] = $account;
      $_SESSION['is_admin'] = $table[2];
      unset($_SESSION['login_account']);

      if($table[2] == 0){
        $who = "member";
      }
      else{
        $who = "admin";
      }
?>
      <div class="transport">
        <p class="notice"><?php echo "$who"; ?> login successed</p>
        <meta http-equiv=REFRESH CONTENT=1;url=<?php echo "$who" ?>.php>
      </div>
<?php
    }
  }
?>
<html>
<head>
  <link rel="stylesheet" href="all.css" >
  <title>need_to_login</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body>
  <div>
    <h1>Welcome to HW2</h1>
  </div>
  <div id="index" >  
    <h1>Login</h1>
    <!--form name="login" method="post" action="can_login.php"-->
    <form name="login" action="index.php" method="post">
      <table id="login" class="noshadow">
      <tr>
        <td>account</td>
        <td><input name="account" type="text" value="<?php if(isset($_SESSION['login_account'])){echo $_SESSION['login_account'];}; ?>"></td>
      </tr>
      <tr>
        <td>password</td>
        <td><input name="password" id = "password" type="password"></td>
      </tr>
      </table>
      
      <p>
        <input name="button_to_submit" type="submit" value="login">&nbsp;&nbsp;
        <input type="button" onclick="location.href='regist.php'" value="regist"></input>
      </p>
    </form>
  </div>
</body>
</html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


