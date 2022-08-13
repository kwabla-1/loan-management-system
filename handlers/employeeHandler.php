<?php 
    include "../config/config.php";
    include "../includes/functions/utility_functions.php";
    include "../includes/class/employee.php";

    
    

    if (isset($_POST['emp_fullname'])) {

        

        //CREATING AN EMPLOYEE ID FOR EACH EMPLOYEE
        $staffID = "Emp".mt_rand(10,10000);

        $sql = "INSERT INTO employees(fullname,number,department,username,password,staffID,location) VALUES(:fullname,:number,:department,:username,:password,:staffID,:location)";
        $query_guarantor = $con->prepare($sql);
        $query_guarantor->bindParam(':fullname',$fullname);
        $query_guarantor->bindParam(':number',$number);
        $query_guarantor->bindParam(':department',$department);
        $query_guarantor->bindParam(':username',$username);
        $query_guarantor->bindParam(':password',$password);
        $query_guarantor->bindParam(':staffID',$staffID);
        $query_guarantor->bindParam(':location',$location);

        $fullname = $_POST['emp_fullname'];
        $firstAndLastname = explode(" ", $fullname);
        $firstname = $firstAndLastname[0];
        $lastname = $firstAndLastname[1];
        $location = $_POST['emp_location'];
        $number = $_POST['emp_tel'];
        $department = $_POST['departments'];
        $username = generageUsername($lastname);
        $password = $_POST['emp_password'];
        $confirm_password = $_POST['emp_confirmpassword'];

        $query_guarantor->execute();
        
        $lastinsertedID = $con->lastInsertId();
        if ($lastinsertedID > 0) {
            echo "INSERTIONSUCCESS";
        }else{
            echo "INSERTIONFAILED";
            echo $staffID;
        }
        
    }elseif (isset($_GET['getAllEmpleyees'])) {
        $employeeObject = new Employee($con);
        $allemployees = $employeeObject->getAllEmployee($con);
        if ($allemployees) {
            echo json_encode($allemployees);
        }else {
            echo "NOEMPLOYEEWASFOUND";
        }
    }elseif (isset($_GET['getEmployeeByID'])) {
        $EMPid = $_GET['getEmployeeByID'];
        $employeeObject = new Employee($con);
        $allemployees = $employeeObject->getEmployeeByID($EMPid,$con);
        if ($allemployees) {
            echo json_encode($allemployees);
        }
    }elseif (isset($_GET['delSpecificEmployee'])) {
        $EMPid = $_GET['delSpecificEmployee'];
        $employeeObject = new Employee($con);
        $allemployees = $employeeObject->deletSpecificEmployee($EMPid,$con);
        if ($allemployees) {
            echo ("Employee Deleted Successfully");
        }
    }
?>