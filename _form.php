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

  function insert_account($db, $account, $hash_password, $is_admin, $name, $email){ 
    $sql_insert_account="INSERT INTO people (account, password, is_admin, name, email) VALUES (:account, :hash_password, :is_admin, :name, :email)";
    $rs=$db->prepare($sql_insert_account);
    $rs->execute(array('account' => $account, 'hash_password' => $hash_password, 'is_admin' => $is_admin, 'name' => $name, 'email' => $email));
  }

  function find_house($db, $house_id){
    $sql_find_house = "SELECT * FROM house WHERE id=$housd_id";
    $rs = $db->query($sql_find_house);
    $table = $rs->fetch();
    return $table;
  }

  function delete_house($db, $house_id){
    $sql_delete_house="DELETE FROM  house WHERE id = $house_id;DELETE FROM favorite WHERE favorite_id = $house_id;DELETE FROM information WHERE house_id = $house_id";
    $rs=$db->query($sql_delete_house);
    //$db->query($sql_delete_house);
  }

  function favorite_house($db, $user_id, $house_id){
    $sql_favorite_house="INSERT INTO favorite (id , user_id , favorite_id) VALUES (NULL , $user_id , $house_id)";
    $rs=$db->query($sql_favorite_house);
    //$rs->query($sql_favorite_house);
  }

  function show_house_all($db){
    $sql_find_house_all = "SELECT h.id hid, h.name hname, price, location, time, owner_id, p.name owner FROM house as h LEFT JOIN people AS p ON owner_id = p.id";
    $rs = $db->query($sql_find_house_all);
    return $rs;
  }

  function show_house_my($db, $user_id){
    $sql_find_house_my = "SELECT h.id hid, h.name hname, price, location, time, owner_id, p.name owner FROM house as h LEFT JOIN people AS p ON owner_id = p.id WHERE owner_id = $user_id";
    $rs = $db->query($sql_find_house_my); 
    return $rs;
  }

  function button_with_form($post_to, $name, $value, $button_name){
    echo "<form method='post' action=$post_to>";
    echo "<input type='hidden' name=$name value='$value'>";
    echo "<input class='adjust' value=$button_name type='submit'>";
    echo "</form>";
  }

  function print_info($db, $house_id){
    $sql_find_info = "SELECT * FROM information AS info WHERE info.house_id = $house_id";
    $rs = $db->query($sql_find_info);
    while($info = $rs->fetchObject()){
       echo "<p> $info->information </p>" ;
    } 
  }

  function print_session($session_name){
    if(isset($_SESSION[$session_name])){
      echo $_SESSION[$session_name];
    }
  }
  
  function print_h($h_num, $content){
    echo "<h$h_num>$content</h$h_num>";
  }

  function print_p($class_p, $content){
    echo "<p class = $class_p>$content</p>";
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
