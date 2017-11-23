<link rel="stylesheet" href="all.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
  include("connect_database.php");
  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

  function unset_session($session_to_delete){
    if(isset($_SESSION[$session_to_delete])){
      unset($_SESSION[$session_to_delete]);
    }
  }

  function store_post_as_session($session_name, $post_name){
    $_SESSION[$session_name] = $_POST[$post_name];
  }
      
  function find_account($db, $account){
    $sql_find_account = "SELECT * FROM people WHERE account=:account";
    $rs = $db->prepare($sql_find_account);
    $rs->execute(array('account' => $account));
    $table = $rs->fetch();
    return $table;
  }
  function print_session($session_name){
    if(isset($_SESSION[$session_name])){
      echo $_SESSION[$session_name];
    }
  }

  function insert_account($db, $account, $hash_password, $is_admin, $name, $email){ 
    $sql_insert_account="INSERT INTO people (account, password, is_admin, name, email) VALUES (:account, :hash_password, :is_admin, :name, :email)";
    $rs=$db->prepare($sql_insert_account);
    $rs->execute(array('account' => $account, 'hash_password' => $hash_password, 'is_admin' => $is_admin, 'name' => $name, 'email' => $email));
  }

  function delete_house($db, $house_id){
    $sql_delete_house="DELETE FROM  house WHERE id=$house_id;DELETE FROM favorite WHERE house_id = $house_id";
    /*$rs=$db->prepare($sql_delete_house);
    $rs->execute();*/
    $rs=$db->query($sql_delete_house);
    //$db->query($sql_delete_house);
  }

  function print_p($class_p, $content){
    echo "<p class = $class_p>";
    echo $content;
    echo "</p>";
  }

  function print_p_with_div($class_p, $content, $redirect_time, $redirect_url){
    echo "<div class='transport'>";
    if(is_array($content)){
      print_p("notice", $content[0]);
      foreach($content[1] as $key => $value){
        print_p("alert", $value);
      }
    }
    else{
      print_p($class_p, $content);
    }
    echo "<meta http-equiv=REFRESH CONTENT=$redirect_time;url=$redirect_url>";
    echo "</div>";
  }
?>
