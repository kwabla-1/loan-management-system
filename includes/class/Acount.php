<?php
    class Account{
        private $con;

        public function __construct($con)
        {
            $this->con = $con;
        }

        public function getHighestLoanAmountName($con)
        {
            $sql = "SELECT DISTINCT fullname FROM clients JOIN account ON clients.client_id = (SELECT cleintID_fk FROM account WHERE loanAmount = (SELECT MAX(loanAmount) from account))";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function getlowloaner($con)
        {
            $sql = "SELECT MIN(loanAmount) FROM account";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function getMaxLoaner($con)
        {
            $sql = "SELECT MAX(loanAmount) FROM account";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function getLowestLoanAmountName($con)
        {
            $sql1 = "SELECT DISTINCT fullname FROM clients JOIN account ON clients.client_id = (SELECT cleintID_fk FROM account WHERE loanAmount = (SELECT MIN(loanAmount) from account))";
            $query1 = $con->prepare($sql1);
            $query1->execute();

            $queryResult1 = $query1->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult1;
        }

        public function getAllClients($con)
        {
            $sql = "SELECT COUNT(accountID ) AS totalClients FROM account";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function getTotalNumberOfPayment($con,$clientID)
        {
            $sql = "SELECT COUNT(paymentID) AS numofweeks FROM payments WHERE cleintID_fk = $clientID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($queryResult[0]["numofweeks"] == 0) {
                return 1;
            }else {
               return $queryResult[0]["numofweeks"];
            }
        }

        public function getTotalMoneygiveout($con)
        {
            $sql = "SELECT SUM(loanAmount) AS amoutgivenout FROM account";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function getTotalInterest($con)
        {
            $sql = "SELECT SUM(laonInterest) AS Interest FROM account";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function getTotalPayments($con)
        {
            $sql = "SELECT SUM(amount) AS 'totalPayments' FROM payments";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;

        }

        public function getClientsTotalPayments($clientID,$con)
        {
            $sql = "SELECT SUM(amount) AS 'totalPayments' FROM payments WHERE cleintID_fk =$clientID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult[0]['totalPayments'];
        }

        public function checkForCompletedPayment($con,$userID)
        {
            $sql = "SELECT pendingBalance FROM account WHERE clientID_fk = $userID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function getallNextPaymentClientsInfor($con)
        {
            $sql = "SELECT clients.* ,account.nextPayment,account.loanAmount,account.laonInterest,account.pendingBalance FROM clients JOIN account ON account.cleintID_fk = clients.client_id";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($query->rowCount() > 0) {
                return $queryResult;
            }else {
                return false;
            }

        }

        public function getAllDefaulters($con)
        {
            $sql = "SELECT clients.* ,defaulters.defaultedAmount,defaulters.defaultDate FROM clients JOIN defaulters ON clients.client_id = defaulters.defaultClientID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($query->rowCount() > 0) {
                return $queryResult;
            }else {
                return false;
            }
        }

        public function insertIntoAccout($con,$clietnID,$BID,$loanAmount)
        {
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // setting the pdo to execute error or try catch case;
            $amount_borrowed = $loanAmount;
            //pending loan
            $pencentageInterest = (34 / 100) * $amount_borrowed;
            $pendingLoan = $pencentageInterest + $amount_borrowed;

            //FUNCTION TO ADD 4 MONTHS THE THE END OF PAYMENT;
            $duc = date_create(date("Y/m/d"));
            $duc_formatted = date_format($duc,"Y/m/d");
            $duc = date_add($duc,date_interval_create_from_date_string("112 days"));
            $duc = date_format($duc,"Y-m-d");

            //function for the next payment = add 7days to next payment
            $dmr_formated = date("Y/m/d");
            $dmr_formated = date_create($dmr_formated);
            $dmr_formated = date_add($dmr_formated,date_interval_create_from_date_string("7 days"));
            $next_payment = date_format($dmr_formated,"Y-m-d");

            // return $pendingLoan;
            try{
                // From this point and until the transaction is being committed every change to the database can be reverted
                $con->beginTransaction();
                $sql_account = "INSERT INTO account (loanAmount,laonInterest,laonReceivedOn,pendingBalance,nextPayment,completePayment,cleintID_fk) VALUES (:loanamount,:loanInterest,:receivedon,:pendingBalance,:nextpayment,:completepaymet,:clientID)";
                $query_account = $con->prepare($sql_account);
                $datemoneyreceived = date("Y/m/d");
                $query_account->bindParam(':loanamount',$loanAmount);
                $query_account->bindParam(':loanInterest',$pencentageInterest);
                $query_account->bindParam(':receivedon',$datemoneyreceived);
                $query_account->bindParam(':pendingBalance',$pendingLoan);
                $query_account->bindParam(':nextpayment',$next_payment);
                $query_account->bindParam(':completepaymet',$duc);
                $query_account->bindParam(':clientID',$clietnID);
                $query_account->execute();
                // DELETE BORROWER FROM THE DATABASE;
                $sql = "DELETE borrower, fieldassements FROM borrower JOIN fieldassements ON borrower.b_id = fieldassements.borrowerID
                        WHERE borrower.b_id = $BID";
                $query = $con->prepare($sql);
                $query->execute();

                // Make the changes to the database permanent
                $con->commit();
                if ($query->rowCount() > 0) {
                    return true;
                }else {
                    return "INSERTIONANDDELETEIONFAILED";
                }
            }catch ( PDOException $e ) {
                // Failed to insert the order into the database so we rollback any changes
                $con->rollback();
                throw $e;
            };

        }

        public function getPendingBalanceNextPayment($clientID)
        {
            $sql = "SELECT account.pendingBalance, account.nextPayment FROM account WHERE account.cleintID_fk = $clientID";
            $query = $this->con->prepare($sql);
            $query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function makeApeyment($con,$amount,$method,$dateofpayment,$paymentBy,$clientID)
		{
			$sql = "INSERT INTO payments (amount,paymentDate,method,receivedBy,cleintID_fk) VALUES (:amount,:dateofpayment,:method,:paymentBy,:clientID)";
			$query = $con->prepare($sql);
			$query->bindParam(':amount',$amount);
			$query->bindParam(':method',$method);
			$query->bindParam(':dateofpayment',$dateofpayment);
			$query->bindParam(':paymentBy',$paymentBy);
			$query->bindParam(':clientID',$clientID);

            $query->execute();
            if ($query->rowCount() > 0) {
                //update the pending balance;
                $clientdata = $this->getPendingBalanceNextPayment($clientID);
                if ($clientdata) {
                    $pendingBalance = $clientdata[0]['pendingBalance'];
                    // $nextpayment = $clientdata[0]['nextPayment'];

                    $raw_data = $dateofpayment;
                    $converted_date = date_create($raw_data);
                    $formated_date = date_add($converted_date,date_interval_create_from_date_string("7 days"));
                    $process_date = date_format($formated_date,"Y-m-d");

                    $nextPaymentAfterpayment = $process_date; // next payment;
                    $pendingBalanceAfterpayment = $pendingBalance - $amount;// amout for the pending balance;
                    $sql1 = "UPDATE account SET nextPayment = '$process_date', pendingBalance = $pendingBalanceAfterpayment WHERE cleintID_fk  = $clientID";
					$query1 = $this->con->prepare($sql1);
					$query1->execute();

                    $defaultSubtractResult = $this->substractFromDefaultedAmount($clientID,$amount);

                    $sql2 = "UPDATE clients SET status = 'on track' WHERE client_id  = $clientID";
                    $query2 = $this->con->prepare($sql2);
                    $query2->execute();
					return $defaultSubtractResult;
                }

            }else {
                return false;
                // return $query->errorInfo();
            }
		}


        public function getAllSpecificPayment($clientID)
        {
            $sql = "SELECT clients.reference_code,payments.* from clients INNER JOIN payments ON clients.client_id = payments.cleintID_fk WHERE clients.client_id = $clientID";
            $query = $this->con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($query->rowCount() > 0) {
                return $queryResult;
            }else {
                return false;
            }

        }

        public function updatePendingBalanceByID($clientID,$amountDeleted)
        {
            $sql = "UPDATE account SET pendingBalance = pendingBalance + $amountDeleted WHERE cleintID_fk = $clientID";
			$query = $this->con->prepare($sql);
			$query->execute();
            if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
        }

        public function deletePaymentByID($clientID)
		{
			$sql = "DELETE FROM payments WHERE payments.cleintID_fk = $clientID";
			$query = $this->con->prepare($sql);
			$query->execute();

			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

        public function getTotalChronic($con)
        {
            $sql = "SELECT SUM(chronic_amount) AS 'Total working' FROM chronic";
            $query = $this->con->prepare($sql);
			$query->execute();
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);

			if ($query->rowCount() > 0) {
				return $queryResult;
			}else{
				return false;
			}
        }


        public function getUserPayment($customerid,$date_of_payment){
			$sql = "SELECT * FROM payments WHERE cleintID_fk  = :c_id AND paymentDate = :payment_date ORDER BY paymentid DESC";
			$query = $this->con->prepare($sql);
            $cid = 0;
            $dateOfPayment = '';


			$query->bindParam(':c_id',$cid);
			$query->bindParam(':payment_date',$dateOfPayment);

			$cid = $customerid;
			$dateOfPayment = $date_of_payment;

			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_OBJ);
			return $results;
		}

        public function checkIFUserMadePayment($customerID,$date_of_payment){
			$sql = "SELECT * FROM payments WHERE cleintID_fk = $customerID AND paymentDate = '$date_of_payment' ORDER BY paymentid DESC";
			$query = $this->con->prepare($sql);

			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_OBJ);
			if ($query->rowCount() > 0) {
				return true;
			}else{
				return false;
			}
		}

        public function InsertIntoDefaultors($cliendID,$defaultDate)
        {
            $sql = "INSERT INTO defaulters(`defaultClientID`,`defaultDate`)  SELECT * FROM (SELECT $cliendID, '$defaultDate') AS temp WHERE NOT EXISTS (SELECT `defaultClientID` FROM defaulters WHERE `defaultClientID` = $cliendID AND `defaultDate` = '$defaultDate') LIMIT 1";
			$query = $this->con->prepare($sql);
            $query->execute();
        }

        public function substractFromDefaultedAmount($clientID,$amount)
        {
            $sql = "SELECT * FROM defaulters WHERE defaultClientID = $clientID AND defaultedAmount != 0";
            $query = $this->con->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                $sqli = "UPDATE defaulters set `defaultedAmount` = `defaultedAmount` - $amount where `defaultClientID` = $clientID";
                $queryi = $this->con->prepare($sqli);
                $queryi->execute();

                $this->defaultedDeleteIfZero($clientID);
                return "default amount substracted";
            }
        }

        public function defaultedDeleteIfZero($clientID)
        {
            $sql = "SELECT * FROM defaulters WHERE defaultClientID = $clientID AND defaultedAmount <= 0";
            $query = $this->con->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                $sqld = "DELETE FROM defaulters WHERE defaultClientID = $clientID";
			    $queryd = $this->con->prepare($sqld);
			    $queryd->execute();
            }
        }

        public function updatePendingBalance($clientID)
        {
          $sql = "UPDATE account SET pendingBalance = NULL, laonInterest = 0, loanAmount = 0, daysLeft = 0,nextPayment = NULL WHERE cleintID_fk = $clientID";
          $queryi = $this->con->prepare($sql);
          $queryi->execute();

        }

        public function insertIntoInterestAccrued($clientname,$clientlocation,$clientnumber,$clientID,$loanInterest,$dateaccrued)
        {
          $sql = "INSERT INTO interestaccrued(`fullname`,`location`,`telnumber`,`accruedClientID`,`accruedLoanInterest`,`accruedDate`)
          SELECT * FROM (SELECT  '$clientname','$clientlocation','$clientnumber',$clientID,$loanInterest,'$dateaccrued') AS TEMP
          WHERE NOT EXISTS(SELECT `accruedClientID` FROM interestaccrued WHERE `accruedClientID` = $clientID AND `accruedDate` = '$dateaccrued' AND fullname = '$clientname')";
          
          
        //   INSERT INTO interestaccrued(`accruedClientID`,`accruedLoanInterest`,`accruedDate`)
        //     SELECT * FROM (SELECT  $clientID,$loanInterest,'$dateaccrued') AS TEMP
        //     WHERE NOT EXISTS(SELECT `accruedClientID` FROM interestaccrued WHERE `accruedClientID` = $clientID AND `accruedDate` = '$dateaccrued' )";


          // $sql = "INSERT INTO interestAccrued(accruedClientID,accruedLoanInterest,accruedDate) VALUES($clientID,$loanInterest,'$dateaccrued')";
          // UPDATE account set `pendingBalance` = NULL WHERE cleintID_fk = 48
          $query = $this->con->prepare($sql);
          $query->execute();

          if ($query->rowCount() > 0) {
            return true;
          }else {
            return false;
          }
        }

        public function sumAllExpense()
        {
            $sql = "SELECT SUM(expenseAmount) AS 'totalExpenses' FROM `expenses`";
            $query = $this->con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($query->rowCount() > 0) {
                return $queryResult[0]['totalExpenses'];
            }else {
                return false;
            }
        }

        public function currentInterestAccrued()
        {
            $totalExpenses = $this->sumAllExpense();
            $sql = "SELECT SUM(accruedLoanInterest) as 'totalInterest' FROM interestaccrued";
            $query = $this->con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($query->rowCount() > 0) {
                return   $queryResult[0]['totalInterest'] - $totalExpenses ;
            }else {
                return false;
            }
            
        }

        public function getAllInterestAccrued()
        {
          $sql = "SELECT interestaccrued.* FROM interestaccrued";
          $query = $this->con->prepare($sql);
          $query->execute();

          $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
          if ($query->rowCount() > 0) {
              return $queryResult;
          }else {
              return false;
          }
        }

        public function sumAccruedInterest()
        {
          // SELECT SUM(`accruedLoanInterest`) AS 'accruedinterest' FROM `interestaccrued
          $sql = "SELECT SUM(accruedLoanInterest) AS 'accruedinterest' FROM interestaccrued";
          $query = $this->con->prepare($sql);
          $query->execute();

          $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
          return $queryResult[0]['accruedinterest'];
        }

    }
?>
