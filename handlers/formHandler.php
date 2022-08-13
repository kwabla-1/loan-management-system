<?php
    //CONFIG FILE
    include "../config/config.php";

    if (isset($_POST["login"])) {
        $username = $_POST['username'];
		      $password = $_POST['password'];

        // $username;
        // $password;

        //set login attempt if not set
		if(!isset($_SESSION['attempt'])){ $_SESSION['attempt'] = 0;}

        //check if there are 3 attempts already
		if($_SESSION['attempt'] == 3){
			$_SESSION['error'] = 'Attempt limit reach';
            $_SESSION['attempted_reached'] = true;
            echo "attempted reached";
		}else{
            $sql = "SELECT * FROM admin WHERE username = :user and password = :pass LIMIT 1";
            $query = $con->prepare($sql);
            $query->bindParam(":user",$username);
			       $query->bindParam(':pass',$password);
            $query->execute();

            $query_result = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($query_result) {
                // $_SESSION['login_user'] = true;
                if (!isset($_SESSION['login_user'])) {
                    $_SESSION['login_user'] = $query_result[0]['fullname'];
                }
                //setting the privilages
                if (!isset($_SESSION['department'])) {
                    $_SESSION['department'] = 'Admin';
                }
                unset($_SESSION['attempt']);
                header('Location: ../pages/app.php');
            }else{
                //checking the employee table
                $employee_sql  = "SELECT * FROM employees WHERE staffID = :e_username AND password = :e_password LIMIT 1";
                $employee_query = $con->prepare($employee_sql);
                $employee_query->bindParam(":e_username",$username);
                $employee_query->bindParam(":e_password",$password);

                $employee_query->execute();

                $e_query_result = $employee_query->fetchAll(PDO::FETCH_ASSOC);
                if ($e_query_result) {
                    $testing = $e_query_result;
                    if (!isset($_SESSION['login_user'])) {
                        $_SESSION['login_user'] = $e_query_result[0]['fullname'];
                    }
                    //setting the privilages
                    if (!isset($_SESSION['department'])) {
                        $_SESSION['department'] = $e_query_result[0]['department'];
                    }
                    unset($_SESSION['attempt']);
                    header('Location: ../pages/app.php');
                }else{
                    $_SESSION['attempt'] += 1;
                    $_SESSION['attemptNumber'] -= 1;
                    header('Location: ../index.php');
                    //set the time to allow login if third attempt is reach
					if($_SESSION['attempt'] == 3){
						$_SESSION['attempt_again'] = time() + (1*60);
                        echo "attempted reached ";
						//note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
					}
                }

            }


    }

}elseif (isset($_POST['recommendBorrower'])) {
    $sql = "INSERT INTO fieldassements(amountRecommend,gurantorsFullname,gurantorsfLocation,gurantorsnumber,recommended,borrowerID) VALUES(:recAmount,:gfullanme,:glocation,:gnumber,:approve,:bID)";
    $query = $con->prepare($sql);
    $query->bindParam(':recAmount',$recommend_amount);
    $query->bindParam(':gfullanme',$gname);
    $query->bindParam(':glocation',$glocation);
    $query->bindParam(':gnumber',$gnumber);
    $query->bindParam(':approve',$approveclient);
    $query->bindParam(':bID',$borrowerID);

    $gname = $_POST['g_name'];
    $approveclient = true;
	   $glocation = $_POST['g_location'];
	    $gnumber = $_POST['g_number'];
	     $recommend_amount = $_POST['recommendedAmount'];
    $borrowerID = $_POST['borrowerID'];

    $query->execute();
    $lastinsertedID = $con->lastInsertId();


    if ($lastinsertedID > 0) {
        // UPDATE THE THE BORROWERS TABLE TO BE ASSESSED;
        $sql1 = "UPDATE borrower SET accessed = 'yes' WHERE b_id = $borrowerID";
        $query1 = $con->prepare($sql1);
        $query1->execute();

        if ($query1->rowCount() > 0) {
            header('location: ../pages/app.php');
        }else {
            echo "update of the borrower accessed failed";
        }

    }else{
        echo "insertion for borrower failde";
    }
}elseif (isset($_POST['adminRecommendAmount'])) {
    $sql = "UPDATE fieldassements SET amountRecommend = :recAmount WHERE borrowerID = :id";
    $query = $con->prepare($sql);
    $query -> bindParam(':recAmount', $recommendAmount, PDO::PARAM_INT);
    $query -> bindParam(':id' , $id , PDO::PARAM_INT);

    $recommendAmount = $_POST['adminRecommendAmount'];
    $id = $_POST['borrowerID'];
    $query -> execute();

    if ($query->rowCount() > 0) {
        echo "true";
    }else {
        echo "sorryupdatefail";
    }
}

if (isset($_POST['addExpense'])) {
  $expseDescription = $_POST['expenseDescription'];
  $expseDate = $_POST['expenseDate'];
  $expseAmount = $_POST['expenseAmount'];

  //perform calcuations;
  $sql = "INSERT INTO expenses(expenseDescription,expenseDate,expenseAmount) VALUES ('$expseDescription', '$expseDate', $expseAmount)";
  $query = $con->prepare($sql);
  $query->execute();

  $lastinsertedID = $con->lastInsertId();
  if ($lastinsertedID > 0) {
      echo "INSERTIONSUCCESS";
  }else{
      echo "INSERTIONFAILED";
  }


}
?>
