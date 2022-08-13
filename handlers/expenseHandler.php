<?php
  include "../config/config.php";

  $expseDescription = $_POST['expenseDescription'];
  $expseDate = $_POST['expenseDate'];
  $expseAmount = $_POST['expenseAmount'];

  //perform calcuations;
  $sql = "INSERT INTO expenses(expenseDescription,expenseDate,expenseAmount) VALUES ('$expseDescription', '$expseDate', $expseAmount)";
  $query = $con->prepare($sql);
  $query->execute();

  $lastinsertedID = $con->lastInsertId();
  if ($lastinsertedID > 0) {
      echo "INSERTIONSUCCESS";
  }else{
      echo "INSERTIONFAILED";
  }





?>
