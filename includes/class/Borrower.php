<?php 
    class Borrower{
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }

        

        public function getAllBorrowers($con){
            $sql = "SELECT * FROM borrower WHERE accessed = 'no'";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            if($query->rowCount() > 0){
                return $queryResult;
            }else {
                return false;
            }
            
        }

        public function deleteBorrower($borrowerID,$con)
        {
            $sql = "DELETE FROM borrower WHERE borrower.b_id = $borrowerID";
            $query = $con->prepare($sql);
            $query->execute();

            if($query->rowCount() > 0){
                return true;
            }else {
                return false;
            }
        }

        public function deleteBorrowerAndCLient($idBorrower,$idClient,$con)
        {
            $sql = "DELETE clients, borrower  FROM clients  INNER JOIN borrower  WHERE clients.client_id = $idClient  and borrower.b_id = $idBorrower";
            $query = $con->prepare($sql);
            $query->execute();

            if($query->rowCount() > 0){
                return true;
            }else {
                return $con->errorInfo();
            }
        }

        public function deleteApprovedClients($cliendID,$con)
        {
            // $sql = "DELETE FROM clients "
        }

        public function getBorrowerRecommendedAmoutn($borrowerID,$con)
        {
            //kindly change the function to a suitable name after making changes something link getAllborrowerinforIncludingrecommendamount
            $sql = "SELECT borrower.*, fieldassements.amountRecommend FROM borrower JOIN fieldassements ON borrower.b_id = fieldassements.borrowerID AND borrower.accessed = 'yes' WHERE borrower.b_id = $borrowerID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function registerClient($borrowerID,$con)
        {
             ///////////////////////// STARTING THE TRANSACTION PROCESS ////////////////////////////////////////////////////////
            
             $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // NEW
             //INSERTING INTO THE CLIENTS TABLE;
             
             try {
                $con->beginTransaction();// NEW
                $reference_key = mt_rand(1000,9999);
                $sql = "SELECT borrower.*,fieldassements.* FROM borrower JOIN fieldassements ON borrower.b_id = fieldassements.borrowerID WHERE borrower.b_id = $borrowerID";
                $query = $con->prepare($sql);
                $query->execute();
                $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);

                //gurantors records
                $gfullname = $queryResult[0]['gurantorsFullname'];
                $glocation = $queryResult[0]['gurantorsfLocation'];
                $gtelephone = $queryResult[0]['gurantorsnumber'];
                $gclient_id_FK  = $queryResult[0]['borrowerID']; 
                $vID = $queryResult[0]['votersID'];

                //ACCOUNT RECORDS
                        //pending loan;
                $amount_borrowed = $queryResult[0]['amountRecommend'];
                $pencentageInterest = (34 / 100) * $amount_borrowed;
                $pendingLoan = $pencentageInterest + $amount_borrowed;

           

                $sql1 = "INSERT INTO clients (fullname,occupation,telephone,location,reference_code,votersID) VALUES (:name,:occupation,:tel,:location,:ref,:votersid)";
                $query1 = $con->prepare($sql1);       
                $query1->bindParam(':name',$clientFullname);
                $query1->bindParam(':occupation',$occupation);
                $query1->bindParam(':tel',$telephone);
                $query1->bindParam(':location',$location);
                $query1->bindParam(':ref',$reference_code);
                $query1->bindParam(':votersid',$voterse_id);

                //cleints records
                $clientFullname = $queryResult[0]['b_fullname'];
                $occupation = $queryResult[0]['b_businesstype'];
                $telephone = $queryResult[0]['b_contact'];
                $location = $queryResult[0]['b_businessLocation'];
                $reference_code = $reference_key;
                $voterse_id = $vID;

                // return $gfullname;

            
                $query1->execute();  
			    $lastinsertedID = $con->lastInsertId();


                //INSERT INTO ACCOUNTS
                // return $gclient_id_FK;

                // if ($lastinsertedID > 0) {
                    $sql_guarantor = "INSERT INTO guarantors(gfullname,glocation,gtelephone,gclient_id_FK) VALUES(:fullnameg,:locationg,:telg,:cleintfkg) ";
                    $query_guarantor = $con->prepare($sql_guarantor);
    
                    $query_guarantor->bindParam(':fullnameg',$gfullname);
                    $query_guarantor->bindParam(':locationg',$glocation);
                    $query_guarantor->bindParam(':telg',$gtelephone);
                    $query_guarantor->bindParam(':cleintfkg',$lastinsertedID);

                    $query_guarantor->execute();
                    $lastinsertedID2 = $con->lastInsertId();
                    // if ($lastinsertedID2 > 0) {
                        $sql2 = "UPDATE fieldassements SET approved = true WHERE borrowerID = $borrowerID";
                        $query2 = $con->prepare($sql2);
                        $query2->execute();
                        $con->commit();

                        if ($query2->rowCount() > 0) {
                            return true;
                        }else {
                            echo "update of the borrower accessed failed";
                        }
                    // }
                    
                // }else {
                //     return false;
                // }

            } catch (PDOException $e) {
                $con->rollback();
                return $e->getMessage();
            }
        }

        public function getAllRecommendedBorrowers($con)
        {
            $sql = "SELECT borrower.*,fieldassements.* FROM borrower JOIN fieldassements ON borrower.b_id = fieldassements.borrowerID WHERE fieldassements.recommended = true AND approved = false";
            $query = $con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);

            if($query->rowCount() > 0){
                return $queryResult;
            }else {
                return false;
            }
            
        }     

        public function getAllApprovedClients($con)
        {
            $sql = "SELECT borrower.*,fieldassements.*,clients.client_id FROM borrower JOIN fieldassements ON borrower.b_id = fieldassements.borrowerID JOIN clients ON clients.fullname = borrower.b_fullname
            WHERE fieldassements.approved = true";
            $query = $con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }
        
        public function checkIfUserIsBlackListed($votersID,$con)
        {
            $sql = "SELECT 1 FROM blacklist WHERE blacklist.votersID = '$votersID'";
            $query = $con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);

            if($query->rowCount() > 0){
                return true;
            }else {
                return false;
            }
        }
    }
?>