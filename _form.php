<link rel="stylesheet" href="all.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
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
      
  function find_who_login($db, $account){
    $sql_find_account = "SELECT * FROM people WHERE account=:account";
    $people_rs = $db->prepare($sql_find_account);
    $people_rs->execute(array('account' => $account));
    $table = $people_rs->fetch();
    return $table;
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
