<?php
    function randomPasswordGene(){
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*";
        $passWord = [];
        $alphabetLenght = strlen($alphabet) - 1;
        for ($i = 0; $i < 13; $i++) {
            $n = rand(0, $alphabetLenght);
            $passWord[] = $alphabet[$n];
        }
        return implode($passWord);
    }

    
    
    function generageUsername($lastname){
        $combined_f_l_name = $lastname."_".rand();
        return $combined_f_l_name;
    }

    function checkForCompletedPayment($con,$userID){
        $sql = "SELECT pendingBalance FROM account WHERE cleintID_fk = $userID";
        $query = $con->prepare($sql);
        $query->execute();

        $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
        return $queryResult;
    }

    function checkForRepayment($con,$clientID)
    {
        $sql = "SELECT nextPayment FROM account WHERE cleintID_fk = $clientID";
        $query = $con->prepare($sql);
        $query->execute();

        $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
        return $queryResult;
    }

    function insertIntoChronicTable($con,$clientID)
    {
        //if the record of the same user in the database do not insert any record unless its a different person
        $sql1 = "SELECT EXISTS(SELECT * FROM chronic WHERE clientID_fk = $clientID) AS found";
        $query1 = $con->prepare($sql1);
        $query1->execute();
        $queryResult1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        if ($queryResult1[0]['found'] == 0) {
            //INSERT THE USER INTO THE TABLE
            $sql = "INSERT INTO Chronic (clientID_fk) VALUES($clientID)";
            $query = $con->prepare($sql);
            $query->execute();
            $lastinsertedID = $con->lastInsertId();
            if ($lastinsertedID > 0) {
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
        return $queryResult1;
    }

    function getChronicClients($con)
    {
        $sql = "SELECT clients.fullname, clients.telephone,clients.client_id,clients.location,clients.votersID, account.loanAmount,account.laonReceivedOn, account.pendingBalance,account.completePayment FROM clients INNER JOIN account ON account.cleintID_fk = clients.client_id INNER JOIN chronic ON chronic.clientID_fk = clients.client_id AND account.cleintID_fk = chronic.clientID_fk";

        $query = $con->prepare($sql);
        $query->execute();

        $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
        return $queryResult;
    }

    function deleterFromChronic($con,$cliendID)
    {
        $sql = "DELETE FROM chronic WHERE clientID_fk = $cliendID";
        $query = $con->prepare($sql);
        $query->execute();
        if($query -> rowCount() > 0){
            return true;
        }else {
            return false;
        }
    }

    function deleteFromBlacklist($con,$blacklistID)
    {
        $sql = "DELETE FROM blacklist WHERE clientID_FK  = $blacklistID";
        $query = $con->prepare($sql);
        $query->execute();
        if($query -> rowCount() > 0){
            return true;
        }else {
            return false;
        }
    }

    function clientNameNumberLocation($clientId,$con)
    {
        $sql = "SELECT clients.fullname,clients.location,clients.telephone, fieldassements.amountRecommend, borrower.disbursmentMode,borrower.momoNumber,borrower.accoutName,borrower.accountNumber FROM clients JOIN fieldassements ON clients.client_id = fieldassements.borrowerID JOIN borrower ON borrower.b_id = fieldassements.borrowerID WHERE client_id = 5";

        $query = $con->prepare($sql);
        $query->execute();

        $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
        return $queryResult;
    }
?>