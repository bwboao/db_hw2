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
        $house_id=$_POST['button_delete_house'];
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
          /*$sql_fav_house="INSERT INTO favorite ( id , user_id , favorite_id ) VALUES ( NULL , $user_id , $house_id )";
          $rs=$db->prepare($sql_fav_house);
          $rs->execute();*/
          favorite_house($db, $user_id, $house_id);
          print_p_with_div("notice", "Favorited <3", 1, "admin.php");
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
//search part start
      $user_id = $table[5];
      if(isset($_POST['advanced_search'])){
        $sql_find_all = "SELECT *,house.id hid,house.name hname, people.name AS owner  FROM  `house` LEFT JOIN people ON owner_id = people.id LEFT JOIN favorite ON favorite_id = house.id AND user_id = $user_id " ;
        $sql_find_all_by_info = "SELECT *,house.id hid,house.name hname, people.name AS owner , COUNT(house.id) FROM information AS info LEFT JOIN `house` ON info.house_id = house.id LEFT JOIN people ON owner_id = people.id LEFT JOIN favorite ON favorite_id = house.id AND user_id = $user_id " ;
        //$people_rs = $db->query($sql_find_all);
        $sql_find_require = "";
        if(!empty($_POST['id'])){
          //echo "id = $_POST[id] \n";
          $sql_find_require .= " house.id = '$_POST[id]' "; 
        }
        if(!empty($_POST['name'])){
          if(!empty($sql_find_require)){$sql_find_require .= " AND "; }
          //echo "name = $_POST[name] \n" ;
          $sql_find_require .= " house.name = \"$_POST[name]\" "; 
        }
        if(!empty($_POST['price'])){
          if(!empty($sql_find_require)){$sql_find_require .= " AND "; }
          //echo "price = $_POST[price] \n" ;
          switch($_POST['price']){
            case "1":
              $sql_find_require .= " price <= 30000 ";
              break;
            case "2":
              $sql_find_require .= " price <= 60000 AND price >= 30000 ";
              break;
            case "3":
              $sql_find_require .= " price <= 120000 AND price >= 60000 ";
              break;
            case "4":
              $sql_find_require .= " price >= 120000 ";
          }
        }
        if(!empty($_POST['location'])){
          if(!empty($sql_find_require)){$sql_find_require .= " AND "; }
          //echo "location = $_POST[location] \n"  ;
          $sql_find_require .= " location = \"$_POST[location]\" "; 
        }     
        if(!empty($_POST['time'])){
          if(!empty($sql_find_require)){$sql_find_require .= " AND "; }
          //echo "time = $_POST[time] \n"  ;
          $sql_find_require .= " time = '$_POST[time]' "; 
         } 
        if(!empty($_POST['owner'])){
          if(!empty($sql_find_require)){$sql_find_require .= " AND "; }
          //echo "owner = $_POST[owner] \n"  ;
          $sql_find_require .= " people.name = \"$_POST[owner]\" "; 
        }
        if(isset($_POST['information'])){
          foreach ($_POST['information'] as $infoid )
          {
          if(!empty($sql_find_require)){$sql_find_require .= " OR "; }
            //echo "infoid = $infoid";
            switch ($infoid){
                case "0":
                  unset($_POST['information']);
                  break 2;
                case "1":
                  $sql_find_require .= " info.information = \"laundry facilities\" ";
                  break;
                case "2":
                  $sql_find_require .= " info.information = \"wifi\" ";
                  break;
                case "3":
                  $sql_find_require .= " info.information = \"lockers\" ";
                  break;
                case "4":
                  $sql_find_require .= " info.information = \"kitchen\" ";
                  break;
                case "5":
                  $sql_find_require .= " info.information = \"elevators\" ";
                  break;
                case "6":
                  $sql_find_require .= " info.information = \"no smoking\" ";
                  break;
                case "7":
                  $sql_find_require .= " info.information = \"television\" ";
                  break;
                case "8":
                  $sql_find_require .= " info.information = \"breakfast\" ";
                  break;
                case "9":
                  $sql_find_require .= " info.information = \"toiletries provided\" ";
                  break;
                case "10":
                  $sql_find_require .= " info.information = \"shuttle service\" ";
                  break;
            }
          }
          if(isset($_POST['information'])){
          $sql_find_require .= "GROUP BY house.id HAVING COUNT(house.id) = '" . count($_POST['information']) . "' " ;
          }
        }
        //echo '<br>' ;
        //echo "addup = $sql_find_require";
        if(!empty($sql_find_require)){
          if(isset($_POST['information'])){
            $sql_find_all = $sql_find_all_by_info . " WHERE " . $sql_find_require;
          }
          else{
            $sql_find_all .= " WHERE " . $sql_find_require;
          }
        }
        $sql_find_all .= " ORDER BY house.id ASC";
        //echo '<br>' . "last = '$sql_find_all'";
        $people_rs = $db->prepare($sql_find_all);
        $people_rs->execute();       
      }
      else{
        $people_rs = show_house_all($db, $user_id);
      }
//search part end
?>
<!-- Search part END-->
<!-- Table part START-->
      <div id="table">
        <table>
          <h3>All houses</h3>
          <tbody>
            <tr><td class="adjust" colspan="8">
            <p style="text-align:end;font-size:10px;">*info:use ctrl + mouse to multi-check the information</p>
            </td></tr>
            <tr>
      <form method="post" action="admin.php" >
              <td class="adjust">
                <input class="search" name="id" type="number" placeholder="interval" min="0">
              </td>
              <td class="adjust">
                <input class="search" name="name" type="text" placeholder="keywords">
              </td>
              <td class="adjust">
                <select class="search" name="price"  placeholder="keywords">
                  <option value="0" >--</option>
                  <option value="1" >0 ~ 30,000</option>
                  <option value="2" >30,000 ~ 60,000</option>
                  <option value="3" >60,000 ~ 120,000</option>
                  <option value="4" >120,000 ~</option>
                </select>
              </td>
              <td class="adjust">
                <input class="search" name="location" type="text" placeholder="keywords">
              </td>
              <td class="adjust">
                <input class="search" name="time" type="date" placeholder="date">
              </td>
              <td class="adjust">
                <input class="search" name="owner" type="text" placeholder="keywords">
              </td>
              <td class="adjust">
                <div id="infoselect" >
                  <select class="search" name="information[]" multiple="multiple">
                    <option value="0">-none-</option>
                    <option value="1">laundry facilities</option>
                    <option value="2">wifi</option>
                    <option value="3">lockers</option>
                    <option value="4">kitchen</option>
                    <option value="5">elevator</option>
                    <option value="6">no smoking</option>
                    <option value="7">television</option>
                    <option value="8">breakfast</option>
                    <option value="9">toiletries provided</option>
                    <option value="10">shuttle service</option>
                  </select>
                </div>
              </td>
              <td class="adjust">
                <input name="advanced_search" type="hidden" value="true">
                <input type="submit" value="search">
              </td>
      </form>
            </tr>
          </tbody>
          <tbody>
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
            <td>
<?php
        $sql_find_info = "SELECT * FROM information AS info WHERE info.house_id= $table->hid " ;
        $info_rs = $db->query($sql_find_info);
        while($info = $info_rs->fetchObject()){
          echo "<p> $info->information </p>" ;
        }

?>
            </td>
           <td class="adjust">
              <form method="post" action="admin.php">
              <input type="hidden" name="button_favorite_house" value="<?php echo $table->hid; ?>" <?php if($table->user_id != NULL){ echo "disabled"; } ?>>
                <input class="adjust" value="favorite" type="submit" <?php if($table->user_id != NULL) {echo "disabled";} ?> >
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
        </tbody>
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
