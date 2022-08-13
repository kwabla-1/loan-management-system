<?php 
    
    class Customer{
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }



        public function searchCleintByNumber($data,$con)
        {
            $sql = "SELECT * from clients WHERE reference_code = $data";
            $query = $con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if($query->rowCount() > 0){
                return $queryResult;
            }else {
                return false;
            }
        }

        public function searchByName($data,$con)
        {
            $sql = "SELECT clients.*, account.* FROM clients JOIN account ON clients.client_id = account.cleintID_fk WHERE fullname like '%$data%' OR fullname like '$data%'  LIMIT 5";
            // $sql = "SELECT * from clients WHERE fullname like '%$data%' OR fullname like '$data%' LIMIT 5";  => the working code;

            $query = $con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if($query->rowCount() > 0){
                return $queryResult;
            }else {
                echo false;
            }
        }

        public function getAllCLientInfor($con){
            $sql = "SELECT clients.*, guarantors.*,account.* FROM clients JOIN account ON clients.client_id = account.cleintID_fk JOIN guarantors ON clients.client_id = guarantors.gclient_id_FK";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
            // if ($query->rowCount() > 0) {
            //     return $queryResult;
            // }else {
            //     return false;
            // }
            
        }

        public function getClientById($clientID,$con)
        {
            $sql = "SELECT clients.*, guarantors.*,account.* FROM clients JOIN account ON clients.client_id = account.cleintID_fk JOIN guarantors ON clients.client_id = guarantors.gclient_id_FK WHERE client_id = $clientID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function insertIntoBlacklistTable($con,$blacklistID,$votersID)
        {
            $dateAdded = date('Y-m-d');
            $sql = "INSERT INTO blacklist(clientID_FK,date_added,votersID)  SELECT * FROM (SELECT $blacklistID, '$dateAdded','$votersID') AS temp WHERE NOT EXISTS (SELECT clientID_FK FROM blacklist WHERE clientID_FK = $blacklistID AND votersID = '$votersID' ) LIMIT 1";
            $query = $con->prepare($sql);
            $query->execute();
            
            $lastinsertedID = $con->lastInsertId();
            if ($lastinsertedID > 0) {
                echo "User added to the blacklist";
                // header('location: ../pages/app.php');
            }else{
                echo 'User might already be in the database';
            }
        }

        public function getAllBlacklistedCLients($con)
        {
            $sql = "SELECT clients.*, blacklist.* FROM clients JOIN blacklist ON clients.client_id = blacklist.clientID_FK";
            $query = $con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult; 
        }

        public function updateClientInfor($con,$clientID,$track,$loanAmout,$dateReceived,$gname,$glocation,$gnumber)
        {
            //pending loan
            $pencentageInterest = (34 / 100) * $loanAmout;
            $pendingLoan = $pencentageInterest + $loanAmout;

            //FUNCTION TO ADD 4 MONTHS THE THE END OF PAYMENT;
            $duc = date_create($dateReceived);
            $duc_formatted = date_format($duc,"Y/m/d");
            $duc = date_add($duc,date_interval_create_from_date_string("112 days"));
            $duc = date_format($duc,"Y-m-d");

             //function for the next payment = add 7days to next payment
             $dmr_formated = $dateReceived;
             $dmr_formated = date_create($dmr_formated);
             $dmr_formated = date_add($dmr_formated,date_interval_create_from_date_string("7 days"));
             $next_payment = date_format($dmr_formated,"Y-m-d");

            $sql = "UPDATE clients,account,guarantors SET  
            clients.track = $track, 
            account.loanAmount = $loanAmout,
            account.laonReceivedOn = '$dateReceived',
            account.laonInterest = $pencentageInterest,
            account.pendingBalance = $pendingLoan,
            account.nextPayment = '$next_payment',
            account.completePayment = '$duc',
            guarantors.gfullname = '$gname',
            guarantors.glocation = '$glocation',
            guarantors.gtelephone = '$gnumber'
            WHERE clients.client_id = account.cleintID_fk AND guarantors.gclient_id_FK = clients.client_id AND clients.client_id = $clientID";

            $query = $con->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                // DELETE ALL PAYMENT RECORDS 
                $deletingPaymentHistoryResult = deletePaymentbyCLietID($clientID,$con);
                if ($deletingPaymentHistoryResult) {
                    return true;
                }else {
                    return false;
                }
                
            }else {
                return false;
            }

        }

        public function deleteSpecificClients($con,$clientID)
        {
            $sql = "DELETE FROM clients WHERE client_id = $clientID";
            $query = $con->prepare($sql);
            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }else {
                return false;
            }
        }

        public function updateStaleStatus($customerID,$stale_message){
			$sql = "UPDATE clients SET status = '$stale_message' WHERE client_id  = $customerID";
			$query = $this->con->prepare($sql);
			$query->execute();
		}

        public function getSpecificNextPayment($customerid){
			$sql = "SELECT nextPayment FROM account WHERE cleintID_fk = $customerid";
			$query = $this->con->prepare($sql);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_OBJ);

			if ($query->rowCount() > 0) {
				return $results[0]->nextPayment;
			}else{
				return false;
			}
			// return $results;
		}

        public function updateNextPayment($customerid,$date){
            $raw_data = $date;
			$converted_date = date_create($raw_data);
			$formated_date = date_add($converted_date,date_interval_create_from_date_string("7 days"));
			$process_date = date_format($formated_date,"Y-m-d"); //added the 7 days to the next payment

            $sql = "UPDATE account SET nextPayment = '$process_date' WHERE cleintID_fk  = $customerid";
            $query = $this->con->prepare($sql);
            $query->execute();
        }

        
    }
?>