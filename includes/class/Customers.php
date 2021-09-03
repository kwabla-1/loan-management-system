<?php 
    class Customer{
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }

        public function searchCleintByNumber($data,$con)
        {
            $sql = "SELECT * from clients WHERE reference_code = $data LIMIT 1";
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
            $sql = "SELECT * from clients WHERE fullname like '%$data%' LIMIT 5";
            $query = $con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if($query->rowCount() > 0){
                echo json_encode($queryResult);
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
    }
?>