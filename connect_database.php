<?php
  $db_host = "dbhome.cs.nctu.edu.tw";
  $db_name = "shenchi_cs_hw1";
  $db_user = "shenchi_cs";
  $db_password = "1111";
  $dsn = "mysql:host=$db_host;dbname=$db_name";
  $db = new PDO($dsn, $db_user, $db_password);

  /*$sql = "SELECT *FROM people";
  $rs = $db->query($sql);	  
  while($table = $rs->fetchObject()){
   ?>
     <tr>
       <thi scope="row"><?php echo $table->id ?></th>
       <td><?php echo $table->account ?></td>
       <td><?php echo $table->password ?></td>
       <td><?php echo $table->is_admin ?></td>
     </tr>
   <?php
  }*/
  /*
  if(!@mysql_connect($db_host, $db_user,$db_password))
    die("can't connect to database");
  if(!@mysql_select_db($db_name))
    die("can't use database");*/
?>
