hello :)
<? session_start(); ?>

<?php
  include("connect_database");
  include("_form.php");

  if(isset($_SESSION['account'])){//check is_admin
    if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 1){
      print_p_with_div("alert", "Pemission denied, only administrator can use this page.", 2, "member.php");
    }
    else{
      if(isset($_POST['button_change_house'])){
        $my_house = find_house($db, $_POST['button_change_house']);
        $_SESSION['house_name'] = $my_house[1];
        $_SESSION['price'] = $my_house[2];
        $_SESSION['location'] = $my_house[3];
    }
  }

?>       


