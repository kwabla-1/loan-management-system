<?php 
    class Admin {
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }

        public function getUserRole($userFullname)
        {
            $sql = "SELECT fullname FROM admin WHERE fullname = '$userFullname'";
			$query = $this->con->prepare($sql);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_OBJ);

			if ($query->rowCount() > 0) {
				return "Admin";
			}else{
				$sql1 = "SELECT role FROM employee WHERE fullname = '$userFullname'";
                $query1 = $this->con->prepare($sql1);
                $query1->execute();
                $results1 = $query->fetchAll(PDO::FETCH_OBJ);
                if ($query1->rowCount() > 0) {
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    return $results;
                }
			}
        }
    }
?>