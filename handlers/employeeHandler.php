<?php 
    include "../config/config.php";
    include "../includes/functions/utility_functions.php";

    if (isset($_POST['register_employee'])) {
        echo "registration in progress";
        $fullname = $_POST['emp_fullname'];
        $firstAndLastname = explode(" ", $fullname);
        $firstname = $firstAndLastname[0];
        $lastname = $firstAndLastname[1];
        $location = $_POST['emp_location'];
        $number = $_POST['emp_tel'];
        $role = $_POST['emp_role'];
        $priviledge = $_POST['priviledge'];
        $username = generageUsername($lastname);
        $password = $_POST['emp_password'];
        $confirm_password = $_POST['emp_confirmpassword'];
        $staff_id = $firstname.mt_rand(1,999);
        $errors = [];

        if ($password !== $confirm_password) {
            array_push($errors,"incorrect password");
        }else{
            //checking if username is alread taken;

            $sql = "INSERT INTO employees (firstname,lastname,number,role,privileges,username,password,staffID,location) VALUES(:firstname,:lastname,:number,:role,:privileges,:username,:password,:staff_id,:location)";
            $query_guarantor = $con->prepare($sql);
            $query_guarantor->bindParam(':firstname',$firstname);
            $query_guarantor->bindParam(':lastname',$lastname);
            $query_guarantor->bindParam(':location',$location);
            $query_guarantor->bindParam(':number',$number);
            $query_guarantor->bindParam(':role',$role);
            $query_guarantor->bindParam(':privileges',$priviledge);
            $query_guarantor->bindParam(':username',$username);
            $query_guarantor->bindParam(':password',$password);
            $query_guarantor->bindParam(':staff_id',$staff_id);

            $query_guarantor->execute();
            $lastinsertedID = $con->lastInsertId();
            if ($lastinsertedID > 0) {
                header('location: ../pages/app.php');
            }else{
                echo "insertion failed";
            }
        }
        
    }else{
        echo "registratio failed";
    }
?>