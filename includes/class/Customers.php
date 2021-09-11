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
            $sql = "SELECT * from clients WHERE fullname like '%$data%' OR fullname like '$data%' LIMIT 5";
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

        public function insertIntoBlacklistTable($con,$blacklistID)
        {
            $dateAdded = date('Y-m-d');
            $sql = "INSERT INTO blacklist(clientID_FK,date_added)  SELECT * FROM (SELECT $blacklistID, '$dateAdded') AS temp WHERE NOT EXISTS (SELECT clientID_FK FROM blacklist WHERE clientID_FK = $blacklistID) LIMIT 1";
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
    }
?>