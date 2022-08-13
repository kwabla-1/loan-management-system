<?php 
    if (isset($_POST['makeAPayment'])) {
        $amount = $_POST['paymentAmount'];
        $modeOfPayment = $_POST['modeOfPayment'];
        $paymentDate = $_POST['paymentDate'];
        $paymentBy ='Martin Ahedor';
        // $client_ID = $_GET['cliendID'];

        echo "$amount,$modeOfPayment,$paymentDate,$paymentBy,$client_ID";

        // $insertPaymentResult = $accountObject->makeApeyment($amount,$modeOfPayment,$paymentDate,$paymentBy,$client_ID);
        // if ($insertPaymentResult) {
        //     header("Location: ./profile.php?cliendID=$client_ID");
        // }else {
        //     echo "error making payment";
        // }
    }
?>