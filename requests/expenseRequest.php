<?php
  include "../config/config.php";

if (isset($_GET['getAllExpense'])) {
  $sql = "SELECT * FROM expenses";
  $query = $con->prepare($sql);
  $query->execute();
  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  if ($query->rowCount() > 0) {
    echo json_encode($result);
  }else{
    echo false;
  }
}
?>
