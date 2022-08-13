<?php 
    include "../config/config.php";
    include "../includes/class/Borrower.php";
    include "../includes/class/Acount.php";
    include "../includes/class/Customers.php";

    $borrwerObject = new Borrower($con);
    $accountObject = new Account($con);
    $clientOBject = new Customer($con);
    

    if (isset($_GET['borrowerID'])) {
        $borrowerID = $_GET['borrowerID'];
        $deleteResult = $borrwerObject->deleteBorrower($borrowerID,$con);
        
        echo $deleteResult;
    }elseif (isset($_POST["g_name"])) {
        $sql = "INSERT INTO fieldassements(amountRecommend,gurantorsFullname,gurantorsfLocation,gurantorsnumber,recommended,borrowerID) VALUES(:recAmount,:gfullanme,:glocation,:gnumber,:approve,:bID)";
        $query = $con->prepare($sql);
        $query->bindParam(':recAmount',$recommend_amount);
        $query->bindParam(':gfullanme',$gname);
        $query->bindParam(':glocation',$glocation);
        $query->bindParam(':gnumber',$gnumber);
        $query->bindParam(':approve',$approveclient);
        $query->bindParam(':bID',$borrowerID);
    
        $gname = $_POST['g_name'];
        $approveclient = true;
        $glocation = $_POST['g_location'];
        $gnumber = $_POST['g_number'];
        $recommend_amount = $_POST['recommendedAmount'];
        $borrowerID = $_POST['borrowerID'];
    
        $query->execute();
        $lastinsertedID = $con->lastInsertId();
        
    
        if ($lastinsertedID > 0) {
            // UPDATE THE THE BORROWERS TABLE TO BE ASSESSED;
            $sql1 = "UPDATE borrower SET accessed = 'yes' WHERE b_id = $borrowerID";
            $query1 = $con->prepare($sql1);
            $query1->execute();
    
            if ($query1->rowCount() > 0) {
                echo "true";
            }else {
                echo "update of the borrower accessed to yes failed check it";
            }
            
        }else{
            echo "insertion for borrower failde";
        }
        // echo json_encode("getting the gurnator information");
    }elseif (isset($_GET['allAprovedCLient'])) {
        $allApprovedBorrowers = $borrwerObject->getAllRecommendedBorrowers($con);
        if ($allApprovedBorrowers) {
            echo json_encode($allApprovedBorrowers);
            // echo $recommended_Amount;
        }else {
            echo "NOAPPROVEDCLIENTS";
        }
    }elseif (isset($_GET['adminApproveID'])) {
        $bid = $_GET['adminApproveID'];
        $regClient = $borrwerObject->registerClient($bid,$con);
        //getting alll recommend amount for all approved clients KEY = GARA;
        $allApprovedBorrowers = $borrwerObject->getAllRecommendedBorrowers($con);
        
        //------------------------ END GARA ---------------------------------------
        echo $regClient;

    }elseif (isset($_GET['adminDeleteClient']) and isset($_GET['CleintDelID'])) {
        $borrowerID = $_GET['adminDeleteClient'];//borrower id;
        $idCLient = $_GET['CleintDelID'];// client id;
        // $deleteResult = $borrwerObject->deleteBorrower($borrowerID,$idCLient,$con);
        $resultAfterDeleting = $borrwerObject->deleteBorrowerAndCLient($borrowerID,$idCLient,$con);
        
        echo json_encode($resultAfterDeleting);
    }elseif (isset($_GET['disbursementlist'])) {
        $allAdminApproved = $borrwerObject->getAllApprovedClients($con);
        if ($allAdminApproved) {
            echo json_encode($allAdminApproved);
        }else {
            echo "NODISPURSEMENTLIST";
        }
    }elseif (isset($_GET['paymentID']) and isset($_GET['loanAmount']) and isset($_GET['IDborrower'])) {
        $cId = $_GET['paymentID'];
        $loanAmount = $_GET['loanAmount'];
        $BID = $_GET['IDborrower'];
        $accountInsertResult = $accountObject->insertIntoAccout($con,$cId,$BID,$loanAmount);
        if ($accountInsertResult) {
            echo $accountInsertResult;
        }else{
            print_r($accountInsertResult) ;
        }
    }elseif (isset($_GET['getAllCurrentClient'])) {
        $allCLients = $clientOBject->getAllCLientInfor($con);
        if ($allCLients) {
            echo json_encode($allCLients);
        }else {
            return false;
        }
    }
?>