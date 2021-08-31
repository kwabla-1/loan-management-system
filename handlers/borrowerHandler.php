<?php 
    include "../config/config.php";
    
        $sql = "INSERT INTO borrower(b_fullname,b_businesstype,b_businessLocation,b_contact,disbursmentMode,momoNumber,accoutName,accountNumber,amountRequested) VALUES(:Bfullname,:businessType,:businessLocation,:bmobileNumber,:bdisburstmentmod,:momonum,:accout_name,:accout_number,:brequestedAmount)";
            $query_guarantor = $con->prepare($sql);
            $query_guarantor->bindParam(':Bfullname',$bFullname);
            $query_guarantor->bindParam(':businessType',$businesstype);
            $query_guarantor->bindParam(':businessLocation',$businessLocation);
            $query_guarantor->bindParam(':bmobileNumber',$bmobileNumber);
            $query_guarantor->bindParam(':bdisburstmentmod',$bdispurstment);
            $query_guarantor->bindParam(':momonum',$momoNum);
            $query_guarantor->bindParam(':accout_name',$accountmane);
            $query_guarantor->bindParam(':accout_number',$accountnumber);
            $query_guarantor->bindParam(':brequestedAmount',$brequestAmount);

            $bFullname = $_POST['b_fullname'];
            $businesstype = $_POST['b_busineesTypes'];
            $businessLocation = $_POST['b_businessLocation'];
            $bmobileNumber = $_POST['b_mobileNumber'];
            $brequestAmount = $_POST['b_requestedAmount'];
            $bdispurstment = $_POST['disburementMode'];
            $momoNum = isset($_POST['momoNumber']) ? $_POST['momoNumber'] : "";
            $accountmane = isset($_POST['accountName']) ? $_POST['accountName'] : "";
            $accountnumber = isset($_POST['accountNumber']) ? $_POST['accountNumber'] : "";

            $query_guarantor->execute();
            $lastinsertedID = $con->lastInsertId();
            if ($lastinsertedID > 0) {
                echo "Borrower added successfully";
                // header('location: ../pages/app.php');
            }else{
                echo '<h3 class="error_alert" id="borrowerFormErrorMessage">Something went wrong please try again</h3>';
            }

?>