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

        public function checkForCompletedPayment($con,$userID)
        {
            $sql = "SELECT pendingBalance FROM account WHERE clientID_fk = $userID";
            $query = $con->prepare($sql);
            $query->execute();

            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $queryResult;
        }

        public function insertIntoAccout($con,$clietnID,$loanAmount)
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
                        WHERE borrower.b_id = $clietnID";
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
        
    }
?>