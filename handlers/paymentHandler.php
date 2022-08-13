<?php 
    include "../config/config.php";
    include "../includes/class/Acount.php";

    $account_object = new Account($con);

  if (isset($_POST['paymentAmount']) && isset($_POST['modeOfPayment']) && isset($_POST['paymentDate'])) {
    $payment_amount = $_POST['paymentAmount'];
    $payment_mode = $_POST['modeOfPayment'];
    $payemnt_date = $_POST['paymentDate'];
    $payment_by = $_POST['paymentBy'];
    $client_paymentID = $_POST['paymentClientID'];

    $sql = "INSERT INTO payments (amount,paymentDate,method,receivedBy,cleintID_fk) VALUES (:amount,:dateofpayment,:method,:paymentBy,:clientID)";
    $query = $con->prepare($sql);
    $query->bindParam(':amount',$payment_amount);
    $query->bindParam(':method',$payment_mode);
    $query->bindParam(':dateofpayment',$payemnt_date);
    $query->bindParam(':paymentBy',$payment_by);
    $query->bindParam(':clientID',$client_paymentID);
    $query->execute();

    //UPDATE CLIENT RECORDS
    if ($query->rowCount() > 0) {
      $clientData = $account_object->getPendingBalanceNextPayment($client_paymentID);

      if ($clientData) {
        $pendingBalance = $clientData[0]['pendingBalance'];
        $raw_data = $payemnt_date;
        $converted_date = date_create($raw_data);
        $formated_date = date_add($converted_date,date_interval_create_from_date_string("7 days"));
        $process_date = date_format($formated_date,"Y-m-d");

        $nextPaymentAfterpayment = $process_date; // next payment;
        $pendingBalanceAfterpayment = $pendingBalance - $payment_amount;// amout for the pending balance;
        $sql1 = "UPDATE account SET nextPayment = '$process_date', pendingBalance = $pendingBalanceAfterpayment WHERE cleintID_fk  = $client_paymentID";
        $query1 = $con->prepare($sql1);
				$query1->execute();

        $defaultSubtractResult = $account_object->substractFromDefaultedAmount($client_paymentID,$payment_amount);
        $sql2 = "UPDATE clients SET status = 'on track' WHERE client_id  = $client_paymentID";
        $query2 = $con->prepare($sql2);
        $query2->execute();
				// return $defaultSubtractResult;
        echo "PAYMENTINSERTED";
      }

      // echo "PAYMENTINSERTED";
    }
  }
?>