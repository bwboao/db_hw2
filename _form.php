<link rel="stylesheet" href="all.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
  include("connect_database.php");
  $information = array('laundry_facilities', 'wifi', 'lockers', 'kitchen', 'elevator', 'no_smoking', 'television', 'breakfast', 'toiletries_provided', 'shuttle_service');
  
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
   
  function check_is_admin($db){
    if(isset($_SESSION['account'])){
      $account = $_SESSION['account'];
      $table = find_account($db, $account);
      if($table[2] == 1){
        return 1;
      }
      else{
        return 0;
      }
    }
    else{
      return -1;
    }
  }

  function find_account($db, $account){
    $sql_find_account = "SELECT * FROM people WHERE account=:account";
    $rs = $db->prepare($sql_find_account);
    $rs->execute(array('account' => $account));
    $table = $rs->fetch();
    return $table;
  }

  function find_account_using($db){
    $account = $_SESSION['account'];
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
    $sql_find_house = "SELECT * FROM house WHERE id=:house_id";
    $rs = $db->prepare($sql_find_house);
    $rs->execute(array('house_id' => $house_id));
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

  function show_house_all($db, $user_id){
    //$sql_find_house_all = "SELECT h.id hid, h.name hname, price, location, time, owner_id, p.name owner FROM house as h LEFT JOIN people AS p ON owner_id = p.id";
    $sql_find_house_all = "SELECT *,house.id hid,house.name hname, people.name AS owner  FROM  `house` LEFT JOIN people ON owner_id = people.id LEFT JOIN favorite ON favorite_id = house.id AND user_id = $user_id GROUP BY house.id ORDER BY house.id ASC" ;
    $rs = $db->query($sql_find_house_all);
    return $rs;
  }

  function show_house_my($db, $user_id){
    $sql_find_house_my = "SELECT h.id hid, h.name hname, price, location, time, owner_id, p.name owner FROM house as h LEFT JOIN people AS p ON owner_id = p.id WHERE owner_id = $user_id";
    $rs = $db->query($sql_find_house_my); 
    return $rs;
  }

  function update_house($db, $update_id, $update_name, $update_price, $update_location){
    $sql_update_house = "UPDATE house SET name = :update_name, price = :update_price, location = :update_location WHERE id = :update_id";
    $rs = $db->prepare($sql_update_house);
    $rs->execute(array('update_name' => $update_name, 'update_price' => $update_price, 'update_location' => $update_location, 'update_id' => $update_id));
  }

  function create_house($db, $owner_id, $create_name, $create_price, $create_location){
    $time = date('Y-m-d');
    echo $time;
    $sql_create_house = "INSERT INTO house (id, name, price, location, time, owner_id) VALUES (NULL, :create_name, :create_price, :create_location, :time, :owner_id)";
    $rs = $db->prepare($sql_create_house);
    $rs->execute(array('create_name' => $create_name, 'create_price' => $create_price, 'create_location' => $create_location, 'time' => $time, 'owner_id' => $owner_id));
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

  function print_information_checkbox($information, $values){
    echo "<div>";
    for($i = 0 ; $i < 10 ; $i++){
      if($i == 4 || $i == 8){
        echo "<br>";
      }
      $tmp_str = $information[$i];
      echo "<input type = 'checkbox' name = $tmp_str";
      if($values[$i] == 1){
        echo "checked>";
      }
      else{
        echo ">";
      }
      echo $tmp_str;
      echo "</input>";
    }
    echo "</div>";
  }

  function check_post_value($post_name){
    if(isset($_POST[$post_name])){
      $temp = $_POST[$post_name];
    echo " value = \"$temp\" ";
    }
  }
  function check_post_select($post_name, $value){
    if(isset($_POST[$post_name])){
      $temp = $_POST[$post_name];
      if($temp == $value){
        echo " selected = \"true\" ";
      }
    }
  }
  function check_post_multiselect($post_name, $value){
    if(isset($_POST[$post_name])){
      foreach($_POST[$post_name] as $post_value)
      if($post_value == $value){
        echo " selected = \"true\" ";
      }
    }
  }
?>
