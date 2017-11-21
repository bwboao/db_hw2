<?php session_start(); ?>

<?php
  include("connect_database.php");
  include("_form.php");
  
  unset_session('login_account');

  if(isset($_SESSION['account'])){
    $my_account = $_SESSION['account'];
    if($_SESSION['is_admin'] != 1){
      $sql_find_account = "SELECT * FROM people WHERE account='$my_account'";
      //$people_rs = $db->query($sql_find_account);
      $people_rs = $db->prepare($sql_find_account);
      $people_rs->execute();
      $table = $people_rs->fetch();
?>
  <div>
    <h1>Welcome to Member page</h1>
  </div>
      <div id="member">
        <h3>Member Info</h3>
        
        <table>
          <tr>
            <th>account</th>
            <td><?php echo "$table[0]"; ?></td>
          </tr>
          <tr>
            <th>name</th>
            <td><?php echo "$table[3]"; ?></td>
          </tr>
          <tr>
            <th>email</th>
            <td><?php echo "$table[4]"; ?></td>
          </tr>
        </table>

        <p>
          <input type="button" onclick="location.href='logout.php'" value="logout"></input>
        </p>
      </div>
<?php
    }
    else{
      print_p_with_div("alert", "This account is admin, you DON'T belong here", 2, "admin.php");
    }
  }
  else{
   print_p_with_div("alert", "Please login!", 2, "index.php");
  }
?>
