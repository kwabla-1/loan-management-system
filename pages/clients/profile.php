<?php
    include "../../config/config.php";
    include "../../includes/functions/utility_functions.php";
    include "../../includes/class/Customers.php";
    include "../../includes/class/Acount.php";

    $customerObject = new Customer($con);
    $accountObject = new Account($con);
    // $allClientDetails = $customerObject->getAllCLientInfor(($con))
    if (isset($_GET['cliendID'])) {
        $clientID = $_GET['cliendID'];
        $clientData = $customerObject->getClientById($clientID,$con);
        // var_dump($clientData[0]);
    }

    if (!isset($_GET['cliendID'])) {
        header("Location: ../../pages/app.php");
    }
?>

<?php
    if (isset($_GET['deleteClientID'])) {
        $delID = $_GET['deleteClientID'];
        $delResults = $customerObject->deleteSpecificClients($con,$delID);
        if ($delResults) {
            header("Location: ../../pages/app.php");
        }
    }

    $totalpayment = ($accountObject->getClientsTotalPayments($clientID,$con)) ? $accountObject->getClientsTotalPayments($clientID,$con) : 0;



    if (isset($_POST['deleteClientPayment'])) {
        $deleteID = $_POST['deleteClientPayment'];
        $delteResult = $accountObject->deletePaymentByID($deleteID);
        if ($delteResult) {
            header("location: ./profile.php?deleteClientID=<?php echo $clientData[0]['client_id']");
        }else{
            echo "something happened";
            echo $deleteID;
        }
    }

    if (isset($_SESSION['department'])) {
        if($_SESSION['department'] == 'managers') {
            $makePayment = false;
            $updateCLient = false;
        }
    }
    if (isset($_SESSION['department'])) {
        if($_SESSION['department'] == 'callCenter') {
            $makePayment = true;
            $updateCLient = false;
            $deleteClient = false;
        }
    }
    if (isset($_SESSION['department'])) {
        if($_SESSION['department'] == 'loanOfficer') {
            $makePayment = false;
            $updateCLient = false;
            $deleteClient = false;
        }
    }
    if (isset($_SESSION['department'])) {
        if($_SESSION['department'] == 'finance') {
            $makePayment = false;
            $updateCLient = false;
            $deleteClient = false;
        }
    }

    if (isset($_SESSION['department'])) {
        if($_SESSION['department'] == 'Admin') {
            $makePayment = true;
            $updateCLient = true;
            $deleteClient = true;
        }
    }

    $accountObject->getTotalNumberOfPayment($con,8);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="./styles1.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">

</head>
<body>
    <!-- spinner content -->
    <div class="spinner__container" style="display: none;">
        <div id="loadingSpinerr"></div>
    </div>

    <header class="logo__container">
        <span class="logo__text">PRIMEBOND</span>

        <div class="user__profile-settings">
            <a href="#" class="link-normalize">
                <div class="name">
                    <span><?php echo $clientData[0]['fullname'] ?></span>
                    <span>Client</span>
                </div>
            </a>
            <div class="user__image-icon">
                <a href="#">
                    <img src="../../img/img_avatar.png" alt="client image" class="avatar">
                </a>
            </div>
        </div>
    </header>
    <main class="userInfor__Container">
        <div class="profileInformation__container">

            <div class="left__content">
                <div class="button__and__image">
                    <div class="homeButtonContainer" style="margin: 2rem;">
                        <a href="../../pages/app.php" class="link_clear">
                            <ion-icon name="arrow-back" size="large"></ion-icon>
                        </a>
                    </div>

                    <div class="top__section">
                        <img src="../../img/img_avatar.png" alt="client image" class="user-icon">
                        <h2><?php echo $clientData[0]['fullname'] ?></h2>
                        <h3><?php echo $clientData[0]['telephone'] ?></h3>
                        <h3><?php echo $clientData[0]['location'] ?></h3>
                    </div>
                </div>



                <div class="bottom__section">
                    <div class="pair">
                        <div class="one">
                            <span>Fullname: <span class="bold"><?php echo $clientData[0]['fullname'] ?></span></span>
                            <span>Location: <span class="bold"><?php echo $clientData[0]['location'] ?></span></span>
                            <span>Number: <span class="bold"><?php echo $clientData[0]['telephone'] ?></span></span>
                        </div>

                        <div class="one">
                            <span>Loan: <span class="bold">Gh$<?php echo $clientData[0]['loanAmount'] ?></span></span>
                            <span>Pending: <span class="bold">Gh$<?php echo $clientData[0]['pendingBalance'] ?></span></span>
                            <span>Total payment <span class="bold">Gh$<?php echo $totalpayment ?></span></span>
                        </div>

                        <div class="one">
                            <span>Complete payment: <span class="bold"><?php echo $clientData[0]['completePayment'] ?></span></span>
                            <span>Next payment: <span class="bold"><?php echo $clientData[0]['nextPayment'] ?></span></span>
                            <span>Reference: <span class="bold">#<?php echo $clientData[0]['reference_code'] ?></span></span>
                        </div>

                        <div class="one">
                            <span>Status: <span class="bold"><?php echo $clientData[0]['status'] ?></span></span>
                            <span>Chronic: <span class="bold">No</span></span>
                            <span>Weeks: <span class="bold"><?php echo $accountObject->getTotalNumberOfPayment($con,$_GET['cliendID']); ?></span></span>
                        </div>

                        <div class="one">
                            <span>Gurantors: <span class="bold"><?php echo $clientData[0]['gfullname'] ?></span></span>
                            <span>Location: <span class="bold"><?php echo $clientData[0]['glocation'] ?></span></span>
                            <span>Number: <span class="bold"><?php echo $clientData[0]['gtelephone'] ?></span></span>
                        </div>
                    </div>
                </div>



                <div class="actionContainer">
                    <a href="./profile.php?deleteClientID=<?php echo $clientData[0]['client_id'] ?>" <?php if ($deleteClient===false){?>style="display:none"<?php } ?> class="btn__profile btn--danger" onClick="javascript: return confirm('Are you you sure you want to delete this client')">Delete</a>

                    <div class="conditionContainer"
                        <?php if ($updateCLient===false){?>style="display:none"<?php } ?>
                    >
                        <button type="submit" name="register_c" class="btn__profile btn--update" onclick="openSelectTac(event,'accoutnUPdate')">Update</button>
                    </div>

                    <a href="#" class="btn__profile tabLink btn--update" onclick="openSelectTac(event,'paymentHistory')">Payment History</a>
                </div>
            </div>

            <div class="right__content">
               <nav>
                   <ul class="userTabs-link">
                       <!-- <li><a href="#" class="selected-tab tabLink" onclick="openSelectTac(event,'accoutnUPdate')">Account Update</a></li> -->
                       <li><a href="#" class="selected-tab tabLink" onclick="openSelectTac(event,'paymentHistory')">Payment History</a></li>

                   </ul>
               </nav>
                <?php

                    // $test = $accountObject->getPendingBalanceNextPayment($clientData[0]['client_id']);
                    // var_dump($test[0]);
                ?>
               <div class="accountupdate fadIn tab" id="accoutnUPdate" style="display:none">
                <div class="customer__form">
                <?php
                    if (isset($_POST['updateCLientsInfor'])) {
                        $tracknum = $_POST['tracknumb'];
                        $loan_amount = $_POST['loanAmountUpdate'];
                        $guranotfullname = $_POST['gufullname'];
                        $glocation = $_POST['gulocation'];
                        $gnumber = $_POST['gunumber'];
                        $receivedON = $_POST['receivedON'];
                        $clientID = $_GET['cliendID'];

                        $updateResult = $customerObject->updateClientInfor($con,$clientID,$tracknum,$loan_amount,$receivedON,$guranotfullname,$glocation,$gnumber);
                        if ($updateResult) {
                            header("location: ./profile.php?cliendID=$clientID");
                        }else {
                            echo "update failed";
                        }
                    }
                ?>

                    <form action="" method="POST" class="form">
                        <div class="form_container">
                            <div class="first_column">
                                <h4>Client details</h4>

                                    <input type="number" placeholder="Enter track number" name="tracknumb" required>
                                    <input type="number" placeholder="Enter loan amount" name="loanAmountUpdate" required>
                                    <input type="date"  name="receivedON" required>

                                    <h4>Gurantors details</h4>
                                    <input type="text" placeholder="Gurantors fullname" name="gufullname">
                                    <input type="text" placeholder="Gurantors location" name="gulocation">
                                    <input type="number" placeholder="Gurantors Phone number" name="gunumber">
                            </div>
                        </div>
                        <input type="submit" name="updateCLientsInfor" value="Update" class="btn btn--primary">
                    </form>
                </div>
               </div>

                <div class="paymentHistory fadIn tab" id="paymentHistory" >
                    <!-- <h2>Payment History</h2> -->

                    <div class="custom-table">
                        <div class="table-header">
                          <table cellpadding="0" cellspacing="0" border="0" class="repaymentTable-head">
                            <thead>
                              <tr>
                                <th>No.</th>
                                <th>Payment by</th>
                                <th>Reference number</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Action</th>

                              </tr>
                            </thead>
                          </table>
                        </div>
                        <div class="table-content">
                          <table cellpadding="0" cellspacing="0" border="0" class="repaymentTable">
                            <tbody>


                                <?php
                                    //DELETING CLIENT PAYMENT RECORD;
                                    if (isset($_GET['DeletePaymentID'])) {
                                        $paymentID = $_GET['DeletePaymentID'];
                                        $delAmount = $_GET['delAmount'];
                                        $clientCurrentID = $_GET['cliendID'];
                                        $deleteResult = deleteApaymentByID($paymentID,$con);
                                        $updateDeletedRecord = $accountObject->updatePendingBalanceByID($clientCurrentID,$delAmount);
                                        if ($updateDeletedRecord) {
                                            header("location: ./profile.php?cliendID={$_GET['cliendID']}");
                                        }
                                    }

                                    $paymentResult = $accountObject->getAllSpecificPayment($_GET['cliendID']);
                                    $clientCurrentID = $_GET['cliendID'];
                                    $paymentCount = 1;
                                    if ($paymentResult) {
                                        foreach ($paymentResult as $key => $value) {
                                            $numberFOrmat = number_format($value['amount'],2);
                                            echo "<tr>
                                                <td>{$paymentCount}</td>
                                                <td>{$value['receivedBy']}</td>
                                                <td>#{$value['reference_code']}</td>
                                                <td>GH&#8373;{$numberFOrmat}</td>
                                                <td>{$value['method']}</td>
                                                <td>{$value['paymentDate']}</td>
                                                <td>
                                                    <a href='profile.php?cliendID={$clientCurrentID}&DeletePaymentID={$value['paymentID']}&delAmount={$value['amount']}' class='btn btn--danger'>Delete<Delete</a>
                                                </td>
                                            </tr>";
                                            $paymentCount++;
                                        }
                                    }else {
                                        echo "<h1>No payments has been made</h1>";
                                    }
                                ?>


                            </tbody>
                          </table>
                        </div>
                    </div>

                    <div class="conditionContainer"<?php if ($makePayment===false){?>style="display:none"<?php } ?>>
                        <div class="makePaymentContainer">
                            <?php
                                $lock_button = "";
                                if (isset($_POST['makeAPayment'])) {
                                    $amount = $_POST['paymentAmount'];
                                    $modeOfPayment = $_POST['modeOfPayment'];
                                    $paymentDate = $_POST['paymentDate'];
                                    $paymentBy = $_SESSION['login_user'];
                                    $client_ID = $clientData[0]['client_id'];
                                    $lock_button = "disabled";



                                    $insertPaymentResult = $accountObject->makeApeyment($con,$amount,$modeOfPayment,$paymentDate,$paymentBy,$client_ID);
                                    
                                    if ($insertPaymentResult) {
                                        $amount = '';
                                        $modeOfPayment = '';
                                        $paymentDate = '';
                                        $paymentBy = '';
                                        $client_ID = '';
                                        
                                        header("Location: ./profile.php?cliendID=$client_ID");
                                    }else{
                                        echo "its not set";
                                    }
                                }
                            ?>
                            <div class="paymentFormContainer" >
                                <div id="formErrorContainer__profilepage">
                                    <span id="formMessage">
                                    </span>
                                </div>
                                <div id="formSuccessContainer__profilepage">
                                    <span id="formMessageSuccess">
                                    </span>
                                </div>
                                <h2>Make a payment for <?php echo $clientData[0]['fullname'] ?></h2>
                                
                                <form action="" method="POST" id="payment_form">
                                    <input type="number" placeholder="Enter Amount" name="paymentAmount" required class="payment_form_data">
                                    <select name="modeOfPayment" id="modeID" required class="payment_form_data">
                                        <option value="MobileMoney">Mobile Money</option>
                                        <option value="physicalCash">Physical Money</option>
                                    </select>
                                    <input type="date" placeholder="" class="payment_form_data" name="paymentDate" required>
                                    <input type="hidden" name="paymentBy" class="payment_form_data" value="<?php echo $_SESSION['login_user']; ?>">
                                    <input type="hidden" name="paymentClientID" class="payment_form_data" value="<?php echo $clientData[0]['client_id'];; ?>">

                                    <input type="submit" value="Make payment" id="paymentbutton" onclick="makePayment(); return false" name="makeAPayment" class="btn btn--primary">
                                </form>
                            </div>
                        </div>
                    </div>

                </div>




            </div>
        </div>
    </main>
    <script>
        const openSelectTac = (event,selectedTab) => {
            const allTab = document.getElementsByClassName("tab");
            const linkTab = document.getElementsByClassName("tabLink");
            for (let j = 0; j < allTab.length; j++) {
                allTab[j].style.display = "none"
            }
            for (let z = 0; z < linkTab.length; z++) {
                linkTab[z].className = linkTab[z].className.replace("selected-tab", "");
            }
            document.getElementById(selectedTab).style.display = "block";
            event.currentTarget.className += " selected-tab";

        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../../js/profilescript.js"></script>
</body>
</html>
