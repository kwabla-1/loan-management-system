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
    }elseif (isset($_POST['blacklistID'])) {
        $blacklistID = $_POST['blacklistID'];
        $insertionData = $clientOBject->insertIntoBlacklistTable($con,$blacklistID);
        
        echo $insertionData;
    }elseif (isset($_GET['getAllBlacklistedClients'])) {
        $allBlacklistedCLient = $clientOBject->getAllBlacklistedCLients($con);
        if ($allBlacklistedCLient) {
            echo json_encode($allBlacklistedCLient);
        }else {
            echo json_encode(false);
        }
    }elseif (isset($_GET['blacklistDeleteID'])) {
        $blacklistDeleteID = $_GET['blacklistDeleteID'];
        $deleteResult = deleteFromBlacklist($con,$blacklistDeleteID);
        if ($deleteResult) {
            echo "DELETESUCCESS";
        }else {
            return "DELETEFAILDED";
        }
    }
?>