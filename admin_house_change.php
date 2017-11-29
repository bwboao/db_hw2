<?php session_start(); ?>

<?php
  include("connect_database.php");
  include("_form.php");

  if(check_is_admin($db) != -1){
    $account_using = find_account_using($db);
    $values=array(0,0,0,0,0,0,0,0,0,0);  
    if(!isset($_SESSION['is_update'])){
      $_SESSION['is_update'] = 0;
    }
    if(isset($_POST['button_change_house'])){
      $my_house = find_house($db, $_POST['button_change_house']);
      $_SESSION['change_house_id'] = $my_house[0];
      $_SESSION['change_house_name'] = $my_house[1];
      $_SESSION['change_house_price'] = $my_house[2];
      $_SESSION['change_house_location'] = $my_house[3];
      $_SESSION['is_update'] = 1;
    }
    if(isset($_POST['price'])){
      $change_house_name = $_POST['house_name'];
      $change_house_price = $_POST['price'];
      $change_house_location = $_POST['location'];
      for($i = 0;$i < 10;$i++){
        if(isset($_POST[$information[$i]])){
          $values[$i] = 1;
        }
        echo $values[$i];
      }
      //$change_house_time = date('Y-m-d');
      $owner_id = $account_using[5];//people.id
      if($_SESSION['is_update'] == '1'){
        $change_house_id = $_SESSION['change_house_id'];
        update_house($db, $change_house_id, $change_house_name, $change_house_price, $change_house_location);  
      }
      else{
        create_house($db, $owner_id, $change_house_name, $change_house_price, $change_house_location);    
      }
      unset_session('change_house_id');
      unset_session('change_house_name');
      unset_session('change_house_price');
      unset_session('change_house_location');
      if($_SESSION['is_update'] == '1'){
        //print_p_with_div("notice", "update success", 2, "admin_house.php");
      }
      else{
        //print_p_with_div("notice", "create success", 2, "admin_house.php");
      }
    }
  }
  else{
    print_p_with_div("alert", "Please login.", 2, "index.php");
  }

?>       
<html>
<head>
  <link rel="stylesheet" href="all.css">
  <meta http-equiv="Content-Type" content="text/html charset=utf-8" />
</head>

<body>
  <div id="regist"><!--copy from regist.php-->
  <form name="change_house" method="post" action="admin_house_change.php">
  <h3><?php if($_SESSION['is_update'] == 1){echo "Update ";}else{echo "Add new ";} ?>house</h3> 
    <p><?php if($_SESSION['is_update'] == 1){echo "update ";}else{echo "add ";} ?>it~</p>
    <table class="noshadow">
      <tbody>
        <tr>
          <td>house_name</td>
          <td><input name="house_name" type="text" value="<?php print_session('change_house_name'); ?>"></td>
        </tr>
        <tr>
          <td>price</td>
          <td><input name="price" type="text" value="<?php print_session('change_house_price'); ?>"></td>
        </tr>
        <tr>
          <td>location</td>
          <td><input name="location" type="text" value="<?php print_session('change_house_location'); ?>"></td>
        </tr>
      </tbody>
    </table>

<?php
    print_information_checkbox($information, $values);
?>
    <p>
      <input name="button_to_create" type="submit" value=<?php if($_SESSION['is_update'] == '1'){echo "update";}else{echo "create";} ?>>
    </p>
  </form>
      <input type="button" onclick="location.href='admin.php'" value="cancel"></input>
  </div>
</body>
</html>
  

