<?php 
    include "../config/config.php";
    include "../includes/class/Customers.php";
    include "../includes/functions/utility_functions.php";
    
    $clientOBject = new Customer($con);
    

    if (isset($_GET['getChronicClients'])) {
        $chronicResult = getChronicClients($con);
        if ($chronicResult) {
            echo json_encode($chronicResult);
        }else {
            echo false;
        }
    }elseif (isset($_GET['deleteChronicUSer'])) {
        $chronicDeleteID = $_GET['deleteChronicUSer'];
        $deleteResult = deleterFromChronic($con,$chronicDeleteID);
        if ($deleteResult) {
            echo "DELETESUCCESS";
        }else {
            return "DELETEFAILDED";
        }
    }
?>