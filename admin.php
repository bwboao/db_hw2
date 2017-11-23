<?php session_start(); ?>

<?php
  include("connect_database.php");
  include("_form.php");
  unset_session('login_account');
  
  if(isset($_SESSION['account'])){//check is_admin
    if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != 1){
      print_p_with_div("alert", "Pemission denied, only administrator can use this page.", 2, "member.php");
    }
    else{
//delete part start
      if(isset($_POST['button_delete_house'])){
        //store_session_as_post('button_delete_house', 'button_delete_house');
        $house_id=$_POST['button_delete_house'];
        //unset_session('button_delete_house');
        /*$sql="DELETE FROM  house WHERE id='$houseid';DELETE FROM favorite WHERE house_id = '$houseid'";
        $rs=$db->prepare($sql);
        $rs->execute();*/
        delete_house($db, $house_id);
        print_p_with_div("notice", "already delete", 1, "admin.php");
      }
//delete part end

//favorite part start
      if(isset($_POST['button_favorite_house'])){
          $account = $_SESSION['account'];
          $table = find_account($db, $account);
          $user_id = $table[5];
          
          $house_id = $_POST['button_favorite_house'];
          echo $user_id, $house_id;
          $sql_fav_house="INSERT INTO favorite ( id , user_id , favorite_id ) VALUES ( NULL , $user_id , $house_id )";
          $rs=$db->prepare($sql_fav_house);
          $rs->execute();
          print_p_with_div("notice", "Already upgrade", 1, "admin.php");
      }
//favorite part end

      $my_account = $_SESSION['account'];//for sql;
      $table = find_account($db, $my_account);
?>
      <div id="welcome">
        <h1>Welcome to the Adim page!</h1>
        <div id="transbutton">
          <p class="margin">
            <input type="submit" onclick="location.href='admin_favorite.php'" value="我的最愛"></input>
            <input type="submit" onclick="location.href='admin_house.php'" value="房屋管理"></input>
            <input type="submit" onclick="location.href='admin_user.php'" value="會員管理"></input>
          </p>
        </div>
      </div>
<!-- Personinfo part START-->
      <div id="personinfo">
        <p>Hello, <?php echo "$table[0]"; ?> ! </p>
        <table>
          <tbody>
            <tr>
              <th colspan="2">info</th>
            </tr>
            <tr>
              <td>name</td>
              <td><?php echo "$table[3]"; ?></td>
            </tr>
            <tr>
              <td>email</td>
              <td><?php echo "$table[4]"; ?></td>
            </tr>
          </tbody>
        </table>

        <p class="margin">
          <input type="button" onclick="location.href='logout.php'" value="logout"></input>
        </p>
      </div>
<!-- Personinfo part END-->
<!-- Search part START-->
<?php
      $sql_find_all = "SELECT *,house.id hid,house.name hname, people.name AS owner FROM `house` LEFT JOIN people ON owner_id = people.id LEFT JOIN information AS info ON house.id = info.house_id" ;
      //$people_rs = $db->query($sql_find_all);
      $people_rs = $db->prepare($sql_find_all);
      $people_rs->execute();
?>

<!-- Search part END-->
<!-- Table part START-->
      <div id="table">
        <table>
          <h3>All houses</h3>
          <tr>
            <th>id</th>
            <th>name</th>
            <th>price</th>
            <th>location</th>
            <th>time</th>
            <th>owner</th>
            <th>information</th>
            <th>option</th>
          </tr>
<?php
      while($table = $people_rs->fetchObject()){
?>
          <tr>
	    <td><?php echo $table->hid; ?></td>
            <td><?php echo $table->hname; ?></td>
            <td><?php echo $table->price; ?></td>
            <td><?php echo $table->location; ?></td>
            <td><?php echo $table->time; ?></td>
            <td><?php echo $table->owner; ?></td>
            <td><?php echo $table->information; ?></td>
            <td class="adjust">
              <form method="post" action="admin.php">
                <input type="hidden" name="button_favorite_house" value="<?php echo $table->hid; ?>">
                <input class="adjust" value="favorite" type="submit">
              </form>
              <form method="post" action="admin.php">
                <input type="hidden" name="button_delete_house" value="<?php echo $table->hid; ?>">
                <input class="adjust" value="delete" type="submit">
              </form>
            </td>
          </tr>
<?php
      }
?>
        </table>
      </div>
<!-- Table part END -->
 <?php
    }
  }
  else{
    print_p_with_div("alert", "please login", 2, "index.php");
  }
?>
