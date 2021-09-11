<?php 
    include "../config/config.php";
    include "../includes/class/Customers.php";
    $customerObject = new Customer($con);


    if (isset($_POST['searchUser'])) {
        //CHECKING IF USER IS SEARCH FOR CLIENTS USING HIS REFERENCE NUMBER;
        $searchData = $_POST['searchUser'];
        if (is_numeric($searchData)) {
            $searchResult = $customerObject->searchCleintByNumber($searchData,$con);
            echo json_encode($searchResult);
        }else {
            //search for clients using the firstname or lastname;
            $nameSearchResult = $customerObject->searchByName($searchData,$con);
            echo json_encode($nameSearchResult);
        }
    }
?>