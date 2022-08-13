<?php 
    class Employee{
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }

        public function getAllEmployee($con)
        {
            $sql = "SELECT * FROM employees";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
            // if($query->rowCount() > 0){
                
            // }else {
            //     return false;
            // }
        }

        public function getEmployeeByID($employeeID,$con)
        {
            $sql = "SELECT * FROM employees WHERE employees.id = $employeeID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function deletSpecificEmployee($employeeID,$con)
        {
            $sql = "DELETE FROM employees WHERE employees.id = $employeeID";
            $query = $con->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                return true;
            }else {
                return false;
            }
        }
    }
?>