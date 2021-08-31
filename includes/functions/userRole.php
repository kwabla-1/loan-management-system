<?php 
    function getUserRole($con,$userFullname,$staffid)
    {
        $sql = "SELECT fullname FROM admin WHERE fullname = '$userFullname'";
        $query = $con->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            return "Admin";
        }else{
            $sql1 = "SELECT privileges FROM employees WHERE staffID = '$staffid'";
            $query1 = $con->prepare($sql1);
            $query1->execute();
            $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
            if ($query1->rowCount() > 0) {
                return $results1[0]->privileges;
            }else{
                return "query not working";
            }
        }
    }
?>