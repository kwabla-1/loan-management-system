<?php 
    include "../config/config.php";
    include "../includes/class/Borrower.php";

    $borrwerObject = new Borrower($con);
    $allBorrowers = $borrwerObject->getAllBorrowers($con);
    

    if ($allBorrowers) {
        echo json_encode($allBorrowers);
    }else {
        echo "false";
    }
?>