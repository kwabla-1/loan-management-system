<?php 
    include "../config/config.php";
    include "../includes/functions/sanitizeInputs.php";

    if (isset($_POST['register_c'])) {	

		//GENERATING A REFERENCE KEY FOR USER
		$reference_key = mt_rand(1000,9999);

        //INSERT STATEMENT USING PDO
        $sql = "INSERT INTO clients (fullname,occupation,telephone,location,reference_code) VALUES (:name,:occupation,:tel,:location,:ref)";
        $query = $con->prepare($sql);

        $query->bindParam(':name',$fname);
		$query->bindParam(':occupation',$occupation);
		$query->bindParam(':tel',$tel);
		$query->bindParam(':location',$location);
		$query->bindParam(':ref',$ref);
        $fullName = $_POST['f_name'] . " " . $_POST['l_name'];

        $fname = sanitize_input($fullName);
        $occupation = sanitize_input($_POST['occupation']);
        $tel = sanitize_input($_POST['tel']);
        $location = sanitize_input($_POST['location']);
        $ref = sanitize_input($reference_key);
		
        try {
            $query->execute();
			$lastinsertedID = $con->lastInsertId();

            if ($lastinsertedID > 0) {
                // ==============================================================================================
                // ================================= ACCOUNT INSERTION ========================================
                // ==============================================================================================
                $sql_account = "INSERT INTO account (loanAmount,laonInterest,laonReceivedOn,pendingBalance,nextPayment,completePayment,cleintID_fk) VALUES (:loanamount,:loanInterest,:receivedon,:pendingBalance,:nextpayment,:completepaymet,:clientID)";
                $query_account = $con->prepare($sql_account);

                $query_account->bindParam(':loanamount',$_POST['amount_borrowed']);
                $query_account->bindParam(':loanInterest',$pencentageInterest);
                $query_account->bindParam(':receivedon',$_POST['dmr']);
                $query_account->bindParam(':pendingBalance',$pendingLoan);
                $query_account->bindParam(':nextpayment',$next_payment);
                $query_account->bindParam(':completepaymet',$duc);
                $query_account->bindParam(':clientID',$clietnID);
               
                $clietnID = $lastinsertedID; 
                $amount_borrowed = $_POST['amount_borrowed'];
                //pending loan
                $pencentageInterest = (34 / 100) * $amount_borrowed;
      	        $pendingLoan = $pencentageInterest + $amount_borrowed;
                //FUNCTION TO ADD 4 MONTHS THE THE END OF PAYMENT;
                $duc = date_create($_POST['dmr']);
                $duc_formatted = date_format($duc,"Y/m/d");
                $duc = date_add($duc,date_interval_create_from_date_string("112 days"));
                $duc = date_format($duc,"Y-m-d");
                //function to add 102 days to make the date to complete payment

                //function for the next payment = add 7days to next payment
                $dmr_formated = $_POST['dmr'];
                $dmr_formated = date_create($dmr_formated);
                $dmr_formated = date_add($dmr_formated,date_interval_create_from_date_string("7 days"));
                $next_payment = date_format($dmr_formated,"Y-m-d");

                $query_account->execute();
                // END OF ACCOUNT INSERTING TABLE ---------------------------------------------------------


                // ==============================================================================================
                // ================================= GUARNATOR INSERTION ========================================
                // ==============================================================================================
                $gfullName = $_POST['g_fname'] . " " . $_POST['g_lname'];
                $santizedname = sanitize_input($gfullName);

                $sql_guarantor = "INSERT INTO guarantors(gfullname,glocation,gtelephone,gclient_id_FK) VALUES(:fullnameg,:locationg,:telg,:cleintfkg)";
                $query_guarantor = $con->prepare($sql_guarantor);

                $query_guarantor->bindParam(':fullnameg',$santizedname);
                $query_guarantor->bindParam(':locationg',$_POST['g_location']);
                $query_guarantor->bindParam(':telg',$_POST['g_number']);
                $query_guarantor->bindParam(':cleintfkg',$clietnID);
                
                $query_guarantor->execute();

                header('location: ../pages/app.php');
			}else{
				echo "insertion failed";
			}
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
?>