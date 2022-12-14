<?php
    //CONFIGURATION FILE
    include "../config/config.php";
    //FUNCTIONS
    include "../includes/functions/userRole.php";
    include "../includes/functions/utility_functions.php";

    // session_destroy();

     if (!isset($_SESSION['login_user'])) {
        header('Location: ../index.php');
    }

    if (isset($_SESSION['login_user'])) {
        $userFullname = $_SESSION['login_user'];
        // var_dump($userFullname);
        $role = getUserRole($con,$userFullname,$userFullname);

        // echo $userFullname;
        // var_dump($role);
    }

    //CLASS
    include "../includes/class/admin.php";
    include "../includes/class/Acount.php";
    include "../includes/class/Customers.php";
    include "../includes/class/employee.php";
    include "../includes/class/Borrower.php";

    //CLASS INITIALIZATION
    $accountObject = new Account($con);
    $clientOBject = new Customer($con);
    $employeeObject = new Employee($con);
    $borrwerObject = new Borrower($con);


    // =================== ACCOUNT CLASS PROPERTIES ===================
    $highestLoaner = $accountObject->getHighestLoanAmountName($con);
    if ($highestLoaner) {
        $highestLoaner = $highestLoaner;
        $highestLoanerName = $highestLoaner[0]['fullname'] ? $highestLoaner[0]['fullname'] : "";
    }else {
        $highestLoanerName = "no one";
    }
    $lowestLoaner = $accountObject->getLowestLoanAmountName($con);
    if ($lowestLoaner) {
        $lowestloanerName = $lowestLoaner[0]['fullname'];
    }else {
        $lowestloanerName = "no one";
    }

    $allPaymentsCalculated = $accountObject->getTotalPayments($con);
    $totalCleints = $accountObject->getAllClients($con);
    $totalPayment = ($allPaymentsCalculated[0]['totalPayments']) ? $allPaymentsCalculated[0]['totalPayments'] : 0;
    $lowestloan = $accountObject->getlowloaner($con);
    $higerstloan = $accountObject->getMaxLoaner($con);
    $moneyOut = $accountObject->getTotalMoneygiveout($con);
    $interest = $accountObject->getTotalInterest($con);
    $getTotalChronic = $accountObject->getTotalChronic($con);
    if ($getTotalChronic[0]['Total working'] > 0) {
        $totalChronicAmount = $getTotalChronic[0]['Total working'];
    }else {
        $totalChronicAmount = 0;
    }


    $moneyPending = ($moneyOut[0]["amoutgivenout"] + $interest[0]['Interest']) - $totalPayment; //WORK ON THE PAYMENT TO CALCULAT THE PENDING BALANCE;


    // =================== CLASS PROPERTIES ===================
    $allCLients = $clientOBject->getAllCLientInfor($con);
    $allemployees = $employeeObject->getAllEmployee($con);

    // =================== CHRONIC SECTION ===================
    $today = date('Y-m-d');
    // $today = '2022-01-04';
    // // echo $today;

    foreach ($allCLients as $key => $value) {
        if ($today == date($value['completePayment'], strtotime("+1 months", strtotime($value['completePayment'])))) {
            $clientid = $value['client_id'];
            // //Check if user is done with his or her payment;
            $pendingamount = checkForCompletedPayment($con,$clientid);
            if ($pendingamount[0]['pendingBalance'] > 0) {
                // INSERT USER INTO THE CHRONIC TABLE;
                $result = insertIntoChronicTable($con,$clientid,$pendingamount[0]['pendingBalance']);
            }
        }
        $latePaymentStyle = "";
    }



    // BORROWER PROPERTIES
    $allBorrowers = $borrwerObject->getAllBorrowers($con);
    $allApprovedBorrowers = $borrwerObject->getAllRecommendedBorrowers($con);
    $allAdminApproved = $borrwerObject->getAllApprovedClients($con);

    // echo $today;
    //CHECKING IF PAYMENT IS TO BE MADE, AND DO THE NEEDFUL;
    $allNextRepayment = $accountObject->getallNextPaymentClientsInfor($con);
    if ($allNextRepayment) {
        for ($i=0; $i < count($allNextRepayment); $i++) {
            //CHECK IF PAYMENT IS TODAY
            $payment_is_today = $allNextRepayment[$i]['nextPayment'];
            $made_a_payment = $accountObject->checkIFUserMadePayment($allNextRepayment[$i]['client_id'],$today);
            $late_payment_indicator = false;

            if ($payment_is_today == $today AND $made_a_payment == false) {
                $clientOBject->updateStaleStatus($allNextRepayment[$i]['client_id'],"BEHIND");
                // $accountObject->InsertIntoDefaultors($allNextRepayment[$i]['client_id'],$today);
                $defaulteredAmount = ($allNextRepayment[$i]['loanAmount'] + $allNextRepayment[$i]['laonInterest']) / 16;
                $roundedDefalutAmount = round($defaulteredAmount);
                $updateTime = date("y-m-d");
                // the original code
                $UPIFNOTEXIT = InsertUpdateExist($con,$allNextRepayment[$i]['client_id'],$today,$roundedDefalutAmount,$updateTime);
                // echo($UPIFNOTEXIT);
                // end of original code

                // new code
                $updateTIme = updateDefaulteresTable($con,$allNextRepayment[$i]['client_id'],$today,$roundedDefalutAmount,$updateTime,date("h:i:sa"));
                // var_dump($updateTIme);
            }elseif ($payment_is_today == $today AND $made_a_payment == true) {
                echo "payment made";
            }
            // echo $payment_is_today;


            // var_dump($allNextRepayment[$i]);
        }

    }

    // =================== PENDING BALANCE SECTION  ==================
    //FUNCTION TO CHECK IF PENDING BALANCE IS LESS DONE 0;

    foreach ($allCLients as $key => $value) {
      if ( $value['pendingBalance'] == 0 ) {
        //USER HAS COMPLETE INESRT INTO INTEREST ACCRUED;
        $insertResult = $accountObject->insertIntoInterestAccrued($value['fullname'],$value['location'],$value['telephone'],$value['client_id'],$value['laonInterest'],$today);
        $updatePendingResult = $accountObject->updatePendingBalance($value['client_id']);
      }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../pages/clients/styles1.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>PrimeBond</title>
</head>
<body>
    <!-- spinner content -->
    <div class="spinner__container" >
        <div id="loadingSpinerr"></div>
    </div>

    <div class="main__container">
        <div class="header">
            <div class="logo">
                <span class="p">PRIME<span class="b">BOND</span></span>
            </div>

            <form action="../handlers/search.php" method="POST" class="search">
                <div class="searchFormELements">
                    <input type="text" class="search__input" id="searchTextInput" placeholder="search client" name="searchUser" autocomplete="off" onkeyup="getLiveUserSearch(this.value)">
                    <button class="search__button">
                        <span class="lnr lnr-magnifier"></span>
                    </button>
                </div>
                <div class="searchResults" id="liveSearchData">

                    <!-- <a href="#" class="link_clear searchResultLink">
                        <div class="searchImageResult" style="margin-right: 20px;">
                            <img src="../img/img_avatar.png" alt="user image" style="border-radius: 100px; width: 30px;">
                        </div>
                        <div class="searchTextResult">
                            <div class="clientInforSeacrch">
                                <span style="font-weight: bold;font-size: 1.5rem;">Martin Ahedor</span>
                            </div>
                            <div class="clientGurantorInforSearch">
                                <span>0543234234</span>
                            </div>
                        </div>
                    </a> -->


                </div>
            </form>



            <div class="user-nav">

                    <div class="user-nav__container">
                    <a href="#" class="dropdown_link">
                        <span class="lnr lnr-user"></span>
                        <span class="user__name"><?php echo $_SESSION['login_user'] ?></span>
                        </a>
                    </div>

                <div class="dropdown_content">
                    <a href="./logout.php">Logout</a>
                    <a href="#logout">Profile</a>
                </div>
            </div>
        </div>

        <div class="content">
            <nav class="sidebar">
                <div class="navigation">
                    <ul id="nav-tab">
                            <!-- SHOW THE NAVIGATION BASE ON DEPARTMENT -->
                        <?php
                            if (isset($_SESSION['department'])) {
                                if ($_SESSION['department'] == 'callCenter') {
                                    echo "
                                        <li class=\"list\">
                                            <a href=\"#customers\" onclick=\"openTab('customers')\" id=\"subdropdLink\" >
                                                <span class=\"icon\"><ion-icon name=\"people-circle-outline\"></ion-icon></span>
                                                <span class=\"title\">Customers </span>
                                            </a>
                                        </li>
                                    ";


                                    echo "
                                        <li class=\"list\" onclick=\"openBTab(event,'add_borrower')\" >
                                            <a href=\"#registration\" class=\"dropdown_button\">
                                                <span class=\"icon\"><ion-icon name=\"person-add-outline\"></ion-icon></span>
                                                <span class=\"title arrowIndicator\">Add Borrorwer <ion-icon class=\"arrowRight\" name=\"chevron-forward-outline\"></ion-icon></span>
                                            </a>
                                        </li>
                                        ";

                                        echo "
                                        <li  class=\"list\" onclick=\"openTab('repayments')\">
                                            <a href=\"#repayments\">
                                                <span class=\"icon\"><ion-icon name=\"wallet-outline\"></ion-icon></span>
                                                <span class=\"title\">Repayments</span>
                                            </a>
                                        </li>
                                    ";




                                }elseif ($_SESSION['department'] == 'loanOfficer') {
                                    echo "
                                        <li class=\"list\">
                                            <a href=\"#customers\" onclick=\"openTab('customers')\" id=\"subdropdLink\" >
                                                <span class=\"icon\"><ion-icon name=\"people-circle-outline\"></ion-icon></span>
                                                <span class=\"title\">Customers </span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                    <li class=\"list\" onclick=\"openBTab(event,'assessment')\" >
                                        <a href=\"#registration\" class=\"dropdown_button\">
                                            <span class=\"icon\"><ion-icon name=\"person-add-outline\"></ion-icon></span>
                                            <span class=\"title arrowIndicator\">Assessment</span>
                                        </a>
                                    </li>
                                    ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('repayments')\">
                                            <a href=\"#repayments\">
                                                <span class=\"icon\"><ion-icon name=\"wallet-outline\"></ion-icon></span>
                                                <span class=\"title\">Repayments</span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('chronic')\">
                                            <a href=\"#chronic\">
                                                <span class=\"icon\"><ion-icon name=\"warning-outline\"></ion-icon></span>
                                                <span class=\"title\">Chronic</span>
                                            </a>
                                        </li>
                                    ";

                                }elseif ($_SESSION['department'] == 'managers') {
                                    echo "
                                        <li class=\"list\">
                                            <a href=\"#customers\" onclick=\"openTab('customers')\" id=\"subdropdLink\" >
                                                <span class=\"icon\"><ion-icon name=\"people-circle-outline\"></ion-icon></span>
                                                <span class=\"title\">Customers </span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                    <li class=\"list\" onclick=\"openBTab(event,'Approval')\" >
                                        <a href=\"#registration\" class=\"dropdown_button\">
                                            <span class=\"icon\"><ion-icon name=\"person-add-outline\"></ion-icon></span>
                                            <span class=\"title arrowIndicator\">Approve</span>
                                        </a>
                                    </li>
                                    ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('chronic')\">
                                            <a href=\"#chronic\">
                                                <span class=\"icon\"><ion-icon name=\"warning-outline\"></ion-icon></span>
                                                <span class=\"title\">Chronic</span>
                                            </a>
                                        </li>
                                    ";


                                }elseif ($_SESSION['department'] == 'finance') {
                                    echo "
                                        <li class=\"list\">
                                            <a href=\"#customers\" onclick=\"openTab('customers')\" id=\"subdropdLink\" >
                                                <span class=\"icon\"><ion-icon name=\"people-circle-outline\"></ion-icon></span>
                                                <span class=\"title\">Customers </span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                    <li class=\"list\" onclick=\"openBTab(event,'disbursement')\" >
                                        <a href=\"#registration\" class=\"dropdown_button\">
                                            <span class=\"icon\"><ion-icon name=\"person-add-outline\"></ion-icon></span>
                                            <span class=\"title arrowIndicator\">Disbursement</span>
                                        </a>
                                    </li>
                                    ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('repayments')\">
                                            <a href=\"#repayments\">
                                                <span class=\"icon\"><ion-icon name=\"wallet-outline\"></ion-icon></span>
                                                <span class=\"title\">Repayments</span>
                                            </a>
                                        </li>
                                    ";


                                }elseif ($_SESSION['department'] == 'Admin') {
                                    echo "
                                        <li class=\"list active\" onclick=\"openTab('dashboard')\">
                                            <a href=\"#dashbaord\">
                                                <span class=\"icon\"><ion-icon name=\"stats-chart-outline\"></ion-icon></span>
                                                <span class=\"title\">Dashboard</span>
                                            </a>
                                        </li>
                                        ";

                                    echo "
                                        <li class=\"list\">
                                            <a href=\"#customers\" onclick=\"openTab('customers')\" id=\"subdropdLink\" >
                                                <span class=\"icon\"><ion-icon name=\"people-circle-outline\"></ion-icon></span>
                                                <span class=\"title\">Customers </span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                        <li class=\"list\" onclick=\"openBTab(event,'add_borrower')\" >
                                            <a href=\"#registration\" class=\"dropdown_button\">
                                                <span class=\"icon\"><ion-icon name=\"person-add-outline\"></ion-icon></span>
                                                <span class=\"title arrowIndicator\">Registration <ion-icon class=\"arrowRight\" name=\"chevron-forward-outline\"></ion-icon></span>
                                            </a>
                                        </li>
                                        ";

                                    echo "
                                        <div class=\"dropdownContent dropstyles animate-top\">
                                            <a href=\"#\" class=\"link_clear sub_active\" onclick=\"openBTab(event,'add_borrower')\">Add Borrower</a>
                                            <a href=\"#\" class=\"link_clear\" onclick=\"openBTab(event,'assessment')\">Assement</a>
                                            <a href=\"#\" class=\"link_clear\" onclick=\"openBTab(event,'Approval')\">Aprrovals</a>
                                            <a href=\"#\" class=\"link_clear\" onclick=\"openBTab(event,'disbursement')\">Disbursment</a>
                                        </div>
                                    ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('chronic')\">
                                            <a href=\"#chronic\">
                                                <span class=\"icon\"><ion-icon name=\"warning-outline\"></ion-icon></span>
                                                <span class=\"title\">Chronic</span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                        <li class=\"list\" onclick=\"openTab('employees')\">
                                            <a href=\"#employees\">
                                                <span class=\"icon\"><ion-icon name=\"people-outline\"></ion-icon></span>
                                                <span class=\"title\">employees</span>
                                            </a>
                                        </li>
                                        ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('repayments')\">
                                            <a href=\"#repayments\">
                                                <span class=\"icon\"><ion-icon name=\"wallet-outline\"></ion-icon></span>
                                                <span class=\"title\">Repayments</span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('blacklist')\">
                                            <a href=\"#blacklist\">
                                                <span class=\"icon\"><ion-icon name=\"alert-circle-outline\"></ion-icon></span>
                                                <span class=\"title\">Blacklist</span>
                                            </a>
                                        </li>
                                    ";

                                    echo "
                                        <li  class=\"list\" onclick=\"openTab('defaulters')\">
                                            <a href=\"#Defaulters\">
                                                <span class=\"icon\"><ion-icon name=\"close-circle-outline\"></ion-icon></span>
                                                <span class=\"title\">Defauters</span>
                                            </a>
                                        </li>
                                    ";

                                    //INTEREST ACCRUED;
                                    echo "
                                        <li class=\"list\" onclick=\"openReportTab(event,'interestAccrued')\" >
                                            <a href=\"#interestAccrued\" class=\"dropdown_button2\">
                                                <span class=\"icon\"><ion-icon name=\"trending-up-outline\"></ion-icon></span>
                                                <span class=\"title arrowIndicator\">Reports <ion-icon class=\"arrowRight2\" name=\"chevron-forward-outline\"></ion-icon></span>
                                            </a>
                                        </li>
                                        ";

                                    echo "
                                        <div class=\"dropdownContent2 dropstyles animate-top\">
                                            <a href=\"#\" class=\"link_clear sub_active\" onclick=\"openReportTab(event,'interestAccrued')\">Accrued Interest</a>
                                            <a href=\"#\" class=\"link_clear\" onclick=\"openReportTab(event,'expenses')\">Expenses</a>

                                        </div>
                                    ";
                                }
                            }
                        ?>



                    </ul>
                </div>


                <div class="legal">
                    <!-- &copy; 2021 by PacsTech -->
                </div>
            </nav>

            <main class="main__content">

                <div id="formErrorContainer">
                    <span id="formMessage">
                    </span>
                </div>
                <div id="formSuccessContainer">
                    <span id="formMessageSuccess">
                    </span>
                </div>
                <div class="tab__centent">
                    <!-- DISPLAYING CONTENT BASED ON USER; -->
                    <?php
                        $dashboard = '
                        <div class="tab__content-item active" id="customers">
                            <div class="customer__container fadIn">
                                <div class="table__container">

                                    <div class="customer__header">
                                        <h2>Customers</h2>
                                        <a href="#" class="btn btn--primary"  onclick="openBTab(event,\'add_borrower\')">+ Add Borrower</a>
                                    </div>
                                    <div class="custom-table">
                                        <div class="table-header1">
                                            <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                                <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                            <th>Name</th>
                                                            <th>Occupation</th>
                                                            <th>Loan</th>
                                                            <th>Pending</th>
                                                            <th>Tel number</th>
                                                            <th>Next payment</th>
                                                            <th>Track</th>
                                                            <th>Ref number</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>

                                        <div class="table-content">
                                            <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                                                <tbody>
                                                    <!-- table content wil be loaded by javascript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
                       if ($_SESSION['department'] == 'managers') {
                            $GLOBALS['dashboard'];

                            echo '<div class="tab__content-item " id="customers"">
                                    <div class="customer__container fadIn">
                                        <div class="table__container">

                                            <div class="customer__header">
                                                <h2>Customers</h2>

                                            </div>
                                            <div class="custom-table">
                                                <div class="table-header1">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                    <th>Name</th>
                                                                    <th>Occupation</th>
                                                                    <th>Loan</th>
                                                                    <th>Pending</th>
                                                                    <th>Tel number</th>
                                                                    <th>Next payment</th>
                                                                    <th>Track</th>
                                                                    <th>Ref number</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <div class="table-content">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }


                    ?>

                    <?php
                        if ($_SESSION['department'] == 'Admin') {
                            echo '
                                <div class="tab__content-item active" id="dashboard">
                                    <div class="dashboard__container fadIn">
                                    <h1>Dashboard</h1>

                                    <div class="statistics__container">
                                        <div class="first_statistics_container">
                                            <div class="stat_one_container">
                                                <div class="figure">
                                                    <span>'.$totalCleints[0]["totalClients"].' Clients'.'</span>
                                                    <span><a href="#" class="link_clear"><?php echo $highestLoanerName ?></a></span>
                                                </div>
                                                <div class="rounder_svg">
                                                    <div class="svg_container">
                                                        <ion-icon name="people-circle-outline" class="statIcon"></ion-icon>
                                                    </div>
                                                    <span>Total Clients</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="first_statistics_container second_stats">
                                            <div class="stat_one_container">
                                                <div class="figure">
                                                    <span>GH&#162;'.$totalChronicAmount.''.'</span>
                                                    <span><a href="#" class="link_clear">Total Chronic</a></span>
                                                </div>
                                                <div class="rounder_svg">
                                                    <div class="svg_container">
                                                        <ion-icon name="archive-outline" class="statIcon"></ion-icon>
                                                    </div>
                                                    <span>Total Chronic</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="first_statistics_container third_stats">
                                            <div class="stat_one_container">
                                                <div class="figure">
                                                    <span>GH&#162;'. $moneyOut[0]["amoutgivenout"].''.'</span>
                                                    <span>Total</span>
                                                </div>
                                                <div class="rounder_svg">
                                                    <div class="svg_container ">
                                                        <ion-icon name="card-outline" class="statIcon"></ion-icon>
                                                    </div>
                                                    <span>Money Give out</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="first_statistics_container fourth_stats">
                                            <div class="stat_one_container">
                                                <div class="figure">
                                                    <span>GH&#162;'. $interest[0]["Interest"] .''.'</span>
                                                    <span>Total</span>
                                                </div>
                                                <div class="rounder_svg">
                                                    <div class="svg_container">
                                                        <ion-icon name="analytics-outline" class="statIcon"></ion-icon>
                                                    </div>
                                                    <span>Total Interest</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="first_statistics_container fith_stats">
                                            <div class="stat_one_container">
                                                <div class="figure">
                                                    <span>GH&#162;'.$moneyPending .''.'</span>
                                                    <span>Total</span>
                                                </div>
                                                <div class="rounder_svg">
                                                    <div class="svg_container">
                                                        <ion-icon name="pulse-outline" class="statIcon"></ion-icon>
                                                    </div>
                                                    <span>Money Pending</span>
                                                </div>
                                            </div>
                                        </div>

                                        </div>

                                        <div class="second_statistics_container">
                                            <div class="chart__container">
                                                <h2>Piechart overview</h2>
                                                <div id="piechart_3d" style="width: 100%; height: 90%;">
                                                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                                    <script src="../js/pie_script.js"></script>


                                                </div>

                                            </div>

                                            <div class="activities__cotainer">
                                                <h2>BarChart data</h2>
                                                <div id="top_x_div" style="width: 85%; height: 90%; margin: 0 auto;"></div>
                                                <script src="../js/barchart_script.js"></script>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab__content-item " id="customers" style="display:none">
                                    <div class="customer__container fadIn">
                                        <div class="table__container">

                                            <div class="customer__header">
                                                <h2>Customers</h2>
                                                <a href="#" class="btn btn--primary"  onclick="openBTab(event,\'add_borrower\')">+ Add Borrower</a>
                                            </div>
                                            <div class="custom-table">
                                                <div class="table-header1">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                    <th>Name</th>
                                                                    <th>Occupation</th>
                                                                    <th>Loan</th>
                                                                    <th>Pending</th>
                                                                    <th>Tel number</th>
                                                                    <th>Next payment</th>
                                                                    <th>Track</th>
                                                                    <th>Ref number</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <div class="table-content">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            ';
                        }

                        if ($_SESSION['department'] == 'callCenter') {
                            echo '<div class="tab__content-item " id="customers"">
                                    <div class="customer__container fadIn">
                                        <div class="table__container">

                                            <div class="customer__header">
                                                <h2>Customers</h2>

                                            </div>
                                            <div class="custom-table">
                                                <div class="table-header1">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                    <th>Name</th>
                                                                    <th>Occupation</th>
                                                                    <th>Loan</th>
                                                                    <th>Pending</th>
                                                                    <th>Tel number</th>
                                                                    <th>Next payment</th>
                                                                    <th>Track</th>
                                                                    <th>Ref number</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <div class="table-content">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }

                        if ($_SESSION['department'] == 'loanOfficer') {
                            echo '<div class="tab__content-item " id="customers"">
                                    <div class="customer__container fadIn">
                                        <div class="table__container">

                                            <div class="customer__header">
                                                <h2>Customers</h2>

                                            </div>
                                            <div class="custom-table">
                                                <div class="table-header1">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                    <th>Name</th>
                                                                    <th>Occupation</th>
                                                                    <th>Loan</th>
                                                                    <th>Pending</th>
                                                                    <th>Tel number</th>
                                                                    <th>Next payment</th>
                                                                    <th>Track</th>
                                                                    <th>Ref number</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <div class="table-content">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }

                        if ($_SESSION['department'] == 'finance') {
                            echo '<div class="tab__content-item " id="customers"">
                                    <div class="customer__container fadIn">
                                        <div class="table__container">

                                            <div class="customer__header">
                                                <h2>Customers</h2>

                                            </div>
                                            <div class="custom-table">
                                                <div class="table-header1">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                    <th>Name</th>
                                                                    <th>Occupation</th>
                                                                    <th>Loan</th>
                                                                    <th>Pending</th>
                                                                    <th>Tel number</th>
                                                                    <th>Next payment</th>
                                                                    <th>Track</th>
                                                                    <th>Ref number</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                                <div class="table-content">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }
                    ?>

                    <!-- <div class="tab__content-item active" id="dashboard">
                        <div class="dashboard__container fadIn">
                           <h1>Dashboard</h1>

                           <div class="statistics__container">
                            <div class="first_statistics_container">
                                <div class="stat_one_container">
                                    <div class="figure">
                                        <span>GH&#162;<?php echo $higerstloan[0]['MAX(loanAmount)']  ?> </span>
                                        <span><a href="#" class="link_clear"><?php echo $highestLoanerName ?></a></span>
                                    </div>
                                    <div class="rounder_svg">
                                        <div class="svg_container">
                                            <ion-icon name="cash-outline" class="statIcon"></ion-icon>
                                        </div>
                                        <span>Highest Loan</span>
                                    </div>
                                </div>
                            </div>

                            <div class="first_statistics_container second_stats">
                                <div class="stat_one_container">
                                    <div class="figure">
                                        <span>GH&#162; <?php echo $totalChronicAmount ?></span>
                                        <span><a href="#" class="link_clear">Total Chronic</a></span>
                                    </div>
                                    <div class="rounder_svg">
                                        <div class="svg_container">
                                            <ion-icon name="archive-outline" class="statIcon"></ion-icon>
                                        </div>
                                        <span>Total Chronic</span>
                                    </div>
                                </div>
                            </div>

                            <div class="first_statistics_container third_stats">
                                <div class="stat_one_container">
                                    <div class="figure">
                                        <span>GH&#162;<?php echo $moneyOut[0]["amoutgivenout"]?></span>
                                        <span>Total</span>
                                    </div>
                                    <div class="rounder_svg">
                                        <div class="svg_container ">
                                            <ion-icon name="card-outline" class="statIcon"></ion-icon>
                                        </div>
                                        <span>Money Give out</span>
                                    </div>
                                </div>
                            </div>

                            <div class="first_statistics_container fourth_stats">
                                <div class="stat_one_container">
                                    <div class="figure">
                                        <span>GH&#162;<?php echo $interest[0]['Interest']; ?></span>
                                        <span>Total</span>
                                    </div>
                                    <div class="rounder_svg">
                                        <div class="svg_container">
                                            <ion-icon name="analytics-outline" class="statIcon"></ion-icon>
                                        </div>
                                        <span>Total Interest</span>
                                    </div>
                                </div>
                            </div>

                            <div class="first_statistics_container fith_stats">
                                <div class="stat_one_container">
                                    <div class="figure">
                                        <span>GH&#162; <?php echo $moneyPending ?></span>
                                        <span>Total</span>
                                    </div>
                                    <div class="rounder_svg">
                                        <div class="svg_container">
                                            <ion-icon name="pulse-outline" class="statIcon"></ion-icon>
                                        </div>
                                        <span>Money Pending</span>
                                    </div>
                                </div>
                            </div>

                            </div>

                            <div class="second_statistics_container">
                                <div class="chart__container">
                                    <h2>Statistic overview</h2>
                                    chart
                                </div>

                                <div class="activities__cotainer">
                                    <h2>Repayments</h2>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab__content-item " id="customers" style="display:none">
                        <div class="customer__container fadIn">
                            <div class="table__container">

                                <div class="customer__header">
                                    <h2>Customers</h2>
                                    <a href="#" class="btn btn--primary"  onclick="openBTab(event,'add_borrower')">+ Add Borrower</a>
                                </div>
                                <div class="custom-table">
                                    <div class="table-header1">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                        <th>Name</th>
                                                        <th>Weeks</th>
                                                        <th>Loan</th>
                                                        <th>Pending</th>
                                                        <th>Tel number</th>
                                                        <th>Next payment</th>
                                                        <th>Track</th>
                                                        <th>Ref number</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-content">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="tab__content-item " id="registration" style="display:none">
                        <div class="registration__container fadIn">
                            <div class="customer__form">
                                <form action="../handlers/registerClinetHandler.php" method="POST" class="form">
                                    <div class="form_container">
                                        <div class="first_column">
                                            <h4>Client details</h4>
                                            <div class="one">
                                                <input type="text" placeholder="Enter firstname" name="f_name" required>
                                                <input type="text" placeholder="Enter Lastname" name="l_name" required>
                                            </div>
                                            <div class="two">
                                                <input type="text" placeholder="Occupation" name="occupation" required>
                                                <input type="text" placeholder="Phone number" name="tel" required>
                                            </div>
                                            <div class="three">
                                                <input type="number" placeholder="Loan amount" name="amount_borrowed" required>
                                                <input type="text" placeholder="Location" name="location" required>
                                            </div>

                                        </div>

                                        <div class="second_column">
                                            <h4>Gurantors Details</h4>
                                            <div class="four">
                                                <input type="text" placeholder="Gurantors firstname" name="g_fname">
                                                <input type="text" placeholder="Gurantors Lastname" name="g_lname">
                                            </div>
                                            <div class="six">
                                                <input type="text" placeholder="Gurantors Location" name="g_location">
                                                <input type="number" placeholder="Gurantors Phone number" name="g_number">
                                            </div>
                                            <div class="six">
                                                <input type="date" placeholder="Received On" required name="dmr">
                                                <input type="number" placeholder="Age" required name="age">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" name="register_c" value="Register" class="btn btn--primary">
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab__content-item fadIn" id="chronic" style="display:none">
                        <div class="chronic__container fadIn">
                            <div class="table__container">
                                <div class="customer__header">
                                    <h2>Chronic Customers </h2>
                                    <p class="date">Date: <span id="datetime"></span></p>
                                    <a href="../pages/Print/Chronic.php" class="btn btn--primary" target="_blank" >Print</a>
                                </div>

                                <div class="custom-table">
                                    <div class="table-header1">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                            <thead>
                                                <tr>
                                                    <th>image</th>
                                                    <th>Name</th>
                                                    <th>Loan Amount</th>
                                                    <th>Pending balance</th>
                                                    <th>Tel number</th>
                                                    <th>Complete Payment</th>
                                                    <th>Status</th>

                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-content">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="chronicTable">
                                            <tbody>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab__content-item fadIn" id="employees" style="display: none;">
                        <div class="employees__container fadIn">
                            <div class="table__container">


                                <div class="customer__header">
                                    <h2>Employees Table </h2>

                                    <a href="#" class="btn btn--primary" onclick="openRegTab(event,'register_emp')">+ Add Employee</a>

                                </div>

                                <div class="custom-table">
                                    <div class="table-header1">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                            <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Number</th>
                                                <th>Location</th>
                                                <th>Department</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-content">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="employeeTable">
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div id="register_emp" class="tab__content-item" style="display: none;">
                        <div class="reg__employees-container fadIn">
                            <div class="customer__header mag-1">
                                <h2>Add an Employee </h2>

                                <a href="#" class="btn btn--primary" onclick="openTab('employees')">All Employee</a>

                            </div>

                           <div class="form__container">

                               <form action="" method="" class="reg__form" id="employeeForm">
                                  <div class="divider">
                                    <div class="left__container">
                                        <input type="text" placeholder="Full name" name="emp_fullname" class="employeeFormData" required>
                                        <input type="text" placeholder="Location" name="emp_location" class="employeeFormData" required>
                                        <input type="number" placeholder="Tel number" name="emp_tel" class="employeeFormData" required>
                                        <input type="password" name="emp_password" id="" placeholder="Enter password" class="employeeFormData" required>
                                    </div>

                                    <div class="right__container">
                                        <select name="departments" class="privilegde employeeFormData" required>
                                            <option value="">Select Departments</option>
                                            <option value="callCenter">Call Center</option>
                                            <option value="loanOfficer">Loan Officers</option>
                                            <option value="managers">Managers</option>
                                            <option value="finance">Finance</option>
                                        </select>
                                         <input type="text" placeholder="employee id is auto generated" name="emp_id" disabled required>
                                         <input type="text" placeholder="Username is auto generated" disabled name="emp_username"  required>
                                         <input type="password" name="emp_confirmpassword" placeholder="Confirmpassword" class="employeeFormData" required>
                                     </div>
                                  </div>

                                <button type="submit" class="btn btn--primary mag-1" name="register_employee" id="addAnEmployee" onclick="submitEmployeeForm(); return false">Add</button>
                               </form>
                           </div>
                        </div>
                    </div>

                    <div class="tab__content-item " id="repayments" style="display: none;">
                        <div class="repayments__container fadIn">
                            <div class="table__container">


                                <div class="customer__header">
                                    <h2>All Repayment for: &nbsp;  <span id="day"></span> </h2>
                                    <p class="date">Date: <span id="printDate"></span></p>
                                    <a href="./Print/repayment.php" class="btn btn--primary" target="_blank">+ Print Table</a>

                                </div>

                                <div class="custom-table">
                                    <div class="table-header1">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                            <thead>
                                            <tr>
                                                <th>Fullname</th>
                                                <th>Number</th>
                                                <th>Location</th>
                                                <th>Amount</th>
                                                <th>Loan Amount</th>
                                                <th>Pending Balance</th>

                                            </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-content">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="repaymenttable">
                                        <tbody>

                                        <?php


                                            if ($allNextRepayment) {
                                                foreach ($allNextRepayment as $dataname => $value) {
                                                    if ($value['nextPayment'] == $today) {
                                                        // calculate the weekly payment
                                                        $weekleyPayment = $value['pendingBalance'] / 16;

                                                        $clientID = $value["client_id"];
                                                        $paymentIsToday = $clientOBject->getSpecificNextPayment($value["client_id"]);

                                                        echo "<tr>
                                                            <td data-label='name'>{$value['fullname']}</td>
                                                            <td data-label='loan amount'>{$value['telephone']}</td>
                                                            <td data-label='loan amount'>{$value['location']}</td>
                                                            <td data-label='balance'>gh{$weekleyPayment}</td>
                                                            <td data-label='balance'>gh{$value['loanAmount']}.00</td>
                                                            <td data-label='balance'>gh{$value['pendingBalance']}.00</td>

                                                        </tr>
                                                        ";
                                                    }
                                                }
                                            }


                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab__content-item " id="blacklist" style="display: none;">
                        <div class="repayments__container fadIn">
                            <div class="table__container">

                                <div class="customer__header">
                                    <h2>Blacklist &nbsp;  <span id="day"></span> </h2>
                                    <a href="./Print/Blacklist.php" class="btn btn--primary" target="_blank">+ Print Table</a>

                                </div>

                                <div class="custom-table">
                                    <div class="table-header1">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                            <thead>
                                            <tr>
                                                <th>img</th>
                                                <th>Fullname</th>
                                                <th>Number</th>
                                                <th>Location</th>
                                                <th>Voters ID</th>
                                                <th>Date registered</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-content">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="blacklisttable">
                                        <tbody>
                                            <!-- DATA WILL BE LOADED BY JS -->
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab__content-item " id="defaulters" style="display: none;">
                        <div class="repayments__container fadIn">
                            <div class="table__container">

                                <div class="customer__header">
                                    <h2>Defaulters &nbsp;  <span id="day"></span> </h2>
                                    <a href="./Print/defaulters.php" class="btn btn--primary" target="_blank">+ Print Table</a>

                                </div>

                                <div class="custom-table">
                                    <div class="table-header1">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                            <thead>
                                            <tr>

                                                <th>Fullname</th>
                                                <th>Number</th>
                                                <th>Location</th>
                                                <th>Defaultered Amount</th>
                                                <th>Date defaulteed</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-content">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="defaulters">
                                        <tbody>
                                            <?php
                                                $allDefaulters = $accountObject->getAllDefaulters($con);

                                                if ($allDefaulters) {

                                                    for ($i=0; $i < count($allDefaulters); $i++) {
                                                        $defaultedAmount = number_format($allDefaulters[$i]['defaultedAmount']);
                                                        echo "<tr>
                                                        <td>{$allDefaulters[$i]['fullname']}</td>
                                                        <td>{$allDefaulters[$i]['telephone']}</td>
                                                        <td>{$allDefaulters[$i]['location']}</td>
                                                        <td>GH&#8373;{$defaultedAmount}</td>
                                                        <td>{$allDefaulters[$i]['defaultDate']}</td>

                                                        <td>
                                                            <a href='../pages/clients/profile.php?cliendID={$allDefaulters[$i]['client_id']}' class='link_clear ontime'>View</a>
                                                        </td>
                                                    </tr>";
                                                    }
                                                }else {
                                                    echo "<h1>Sorry no defaulters for now</h1>";
                                                }
                                            ?>

                                            <!-- DATA WILL BE LOADED BY JS -->
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- REPORTS TAB CONTENT -->
                      <div class="tab__content-item " id="interestAccrued" style="display: none;">
                          <div class="repayments__container fadIn">
                              <div class="table__container">
                                  <div class="customer__header">
                                      <h2>Accrued Interst Total: &nbsp;  <span class="ref">GH&#8373;<?php echo($accountObject->sumAccruedInterest() )?> </span> </h2>
                                      <a href="./Print/defaulters.php" class="btn btn--primary" target="_blank">+ Print Table</a>

                                  </div>

                                  <div class="custom-table">
                                      <div class="table-header1">
                                          <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                              <thead>
                                                  <tr>
                                                    <th>Fullname</th>
                                                    <th>Number</th>
                                                    <th>Location</th>
                                                    <th>Date Completed</th>
                                                    <th>Interest Accrued</th>
                                                  </tr>
                                              </thead>
                                        </table>
                                      </div>

                                      <div class="table-content">
                                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="AccruedInterest">
                                            <tbody>
                                              <?php

                                                // POPULATIONG THE INTEREST ACCRUED;
                                                $accruedInterst = $accountObject->getAllInterestAccrued();

                                                if ($accruedInterst) {
                                                  for ($i=0; $i < count($accruedInterst); $i++) {
                                                      echo "<tr>
                                                      <td>{$accruedInterst[$i]['fullname']}</td>
                                                      <td>{$accruedInterst[$i]['telnumber']}</td>
                                                      <td>{$accruedInterst[$i]['location']}</td>
                                                      <td><span class='ref'> {$accruedInterst[$i]['accruedDate']} </span></td>
                                                      <td><span  class='ontime'> GH&#8373;{$accruedInterst[$i]['accruedLoanInterest']} </span></td>


                                                  </tr>";
                                                  }
                                                }else {
                                                  echo "<h1>Sorry no Interest Accrued</h1>";
                                                }
                                              ?>
                                            </tbody>
                                        </table>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- EXPENSES TAB CONTENT -->
                    <div class="tab__content-item" id="expenses" style="display: none">
                        <div class="repayments__container fadIn">
                            <div class="expense__container">
                                <div class="expense__header">
                                    <div class="expenseBudge budget__totalInterest">
                                      <div class="expenseBudged-leftitems">
                                        <h4>Total Interest</h4>
                                        <span>GH&#8373;<?php echo $interest[0]['Interest'] ?></span>
                                      </div>
                                    </div>

                                    <div class="expenseBudge budget__totalInterestAccrued">
                                      <div class="expenseBudged-leftitems">
                                        <h4>Total Interest Accrued</h4>
                                        <span>GH&#8373;<?php echo($accountObject->sumAccruedInterest()); ?></span>
                                      </div>
                                    </div>

                                    <div class="expenseBudge budget__currentInterestAccrued">
                                      <div class="expenseBudged-leftitems">
                                        <h4>Current Interest Accrued</h4>
                                        <span>GH&#8373; <?php echo($accountObject->currentInterestAccrued()) ?></span>
                                      </div>
                                    </div>

                                    <div class="expenseBudge budget__totalExpenses">
                                      <div class="expenseBudged-leftitems">
                                        <h4>Total Expense</h4>
                                        <span>GH&#8373;<?php echo($accountObject->sumAllExpense()) ?></span>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="expense__content fadIn">
                          <div class="expense__content-leftcontent">
                            <div class="expense-calculator__container">
                                <h3>Expenses Calculator</h3>
                                <form class="expenseForm" action="" method="" class="expenseForm" id="expenseForm">
                                    <div class="descriptionContainer">
                                      <textarea name="expenseDescription" rows="8" cols="80" style="width: 376px; height: 200px;" class="expenseFormData"></textarea>
                                    </div>

                                    <div class="amountContainer">
                                      <input type="number" name="expenseAmount" value="" class="expenseFormData" placeholder="Expense amount">
                                    </div>

                                    <div class="amountContainer">
                                      <input type="date" name="expenseDate" value="" class="expenseFormData" placeholder="date">
                                    </div>

                                    <div class="buttonContainer">
                                      <button type="button" name="addExpense" id="expenseSubtractButton" onclick="submitExpense(); return false" class="btn btn--primary">Add Expense</button>
                                    </div>
                                </form>
                            </div>
                          </div>

                          <div class="expense__content-rightcontent">
                            <div class="custom__table">
                              <div class="table-header1">
                                  <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                      <thead>
                                          <tr>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                          </tr>
                                      </thead>
                                </table>
                              </div>

                              <div class="table-content">
                                <table cellpadding="0" cellspacing="0" border="0" class="expenseTable" id="expenseTable">
                                  <tbody>
                                    <!-- DATA WILL BE LOADED BY JAVASCRIPT -->
                                  </tbody>
                                </table>
                              </div>

                            </div>
                          </div>
                        </div>
                    </div>

                    <!-- ADD BORROWER TAB CONTENT -->
                    <div id="add_borrower" class="tab__content-item" style="display: none;">
                        <div class="add_borrowerContainer fadIn">

                            <div class="fixedContainer">
                                <div class="seven">
                                    <h1>Add Borrower</h1>
                                </div>
                            </div>


                           <div class="borrowerForm">
                               <span id="borrowerFormResponse"></span>
                               <span id="bErrorMessage"></span>
                               <!-- <h3 class="error_alert" id="borrowerFormErrorMessage"></h3> -->
                               <!-- linl = ../handlers/borrowerHandler.php -->
                               <form action="" method="" class="reg__form" id="borrower_Form">
                                    <div class="">
                                        <div class="form-control">
                                            <label for="">Enter Borrower Fullname</label>

                                            <input type="text" placeholder="Full name"  name="b_fullname" class="borrower_data" required>
                                        </div>

                                        <div class="form-control">
                                            <label for="">Enter the Business type</label>

                                            <input type="text" placeholder="Businees type?" name="b_busineesTypes" class="borrower_data" required>
                                        </div>

                                        <div class="form-control">
                                            <label for="">Enter the Business location</label>

                                            <input type="text" placeholder="Business location" name="b_businessLocation" class="borrower_data" required>
                                        </div>

                                        <div class="form-control">
                                            <label for="">Enter Borrower's mobile number</label>

                                            <input type="number" placeholder="Mobile number" name="b_mobileNumber" class="borrower_data" required>
                                        </div>

                                        <div class="form-control">
                                            <label for="">Enter Borrower's Voters ID</label>

                                            <input type="text" placeholder="Voters ID" name="b_votersID" class="borrower_data" required>
                                        </div>

                                        <div class="form-control">
                                            <label for="">Enter requested amount</label>
                                            <input type="number" placeholder="Amount Requested" name="b_requestedAmount" class="borrower_data" required>
                                        </div>

                                        <div class="form-control">
                                            <label for="">Select Mode of disbursment</label>
                                            <select id="disbursementMode" name="disburementMode" class="privilegde borrower_data" required onchange="showInputField(this)">
                                                <option value="">Mode of disbursement</option>
                                                <option value="momo" id="momo">Mobile Money</option>
                                                <option value="cash" id="physicalCash">Physical Cash</option>
                                                <option value="account" id="bankAccount">Bank account</option>
                                            </select>
                                        </div>
                                        <!-- DISPLAY INFOR UPON DISPURSEMENT MODE -->
                                        <div class="mobileMoney" id="momoDiv" style="display:none;">
                                            <label for="momoNumber">Enter Mobile Money Number</label>
                                        </div>

                                        <div class="account" id="accountHolderDiv" style="display:none;">
                                            <label for="accountName">Enter Account Details</label>
                                        </div>

                                    </div>

                                <button type="submit" class="btn btn--primary mag-1" name="submit" id="submitBorrower" onclick="submitBorrowerForm(); return false">Add Borrower</button>
                               </form>
                           </div>

                        </div>
                    </div>

                    <!-- ASSESSMENT TAB CONTENT -->
                    <div id="assessment" class="tab__content-item" style="display: none;">

                        <div class="assementContainer fadIn">
                            <div class="print__table">
                                <a href="./Print/assessment.php" class="btn btn--primary" target="_blank">+ Print Table</a>
                            </div>
                            <div class="seven">
                                <h1>assessment Table</h1>

                            </div>


                            <div class="custom-table">
                                <div class="table-header1">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                        <thead>
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Businees</th>
                                            <th>location</th>
                                            <th>Contact</th>
                                            <th>disbursment</th>
                                            <th>request Amount</th>

                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="table-content">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="borrowerTable">
                                        <tbody>
                                            <!-- content will be automatically be loaded by javascript -->

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- APPROVAL TAB CONTENT -->
                    <div id="Approval" class="tab__content-item" style="display: none;">
                        <div class="aprovalContainer fadIn">
                            <div class="seven">
                                <h1>Approval Table</h1>
                            </div>

                            <div class="custom-table">
                                <div class="table-header1">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                        <thead>
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Businees</th>
                                            <th>location</th>
                                            <th>Contact</th>
                                            <th>disbursment</th>
                                            <th>request Amount</th>
                                            <th>recommended</th>

                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="table-content">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="accessmentTable">
                                        <tbody>
                                            <!-- CONTENT WILL BE LOADED BY javascript -->


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DISBURSMENT TAB CONTENT -->
                    <div id="disbursement" class="tab__content-item" style="display: none;">
                        <div class="disbursementContainer fadIn">
                            <div class="seven">
                                <h1>Disbursment Table</h1>
                            </div>

                            <div class="custom-table">
                                <div class="table-header1">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                                        <thead>
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Location</th>
                                            <th>number</th>
                                            <th>amount approved</th>
                                            <th>disbursement mode</th>
                                            <th>disbursement infor</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="table-content">
                                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="disbursementTable">
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ASSESSMENT MODEL FOR THE GURANTORS TABLE -->


                    <!-- ORGINAL CODES  MODAL CODE FOR ASSEMENT TABLE-->
                    <div class="model__container fadIn">

                        <div class="modal">
                        <span  class="close" id="closeModal" title="Close Modal">&times;</span>
                            <h1>Enter Guarantors Information</h1>

                            <div class="guarntor__form">

                                <form action="" method="" id="fieldForm">
                                    <input type="text" name="g_name" id="guarantorname" placeholder="Gurantor name" class="recommendCLeint" required>
                                    <input type="text" name="g_location" id="guarantorlocation" placeholder="Guarantor location"class="recommendCLeint" required>
                                    <input type="text" name="g_number" id="guarantornumber" placeholder="Guarantor number" class="recommendCLeint" required>

                                    <label for="recommendAmount">Recommend Amount</label>
                                    <input type="text" name="recommendedAmount" id="recommendAmount" class="recommendCLeint" required>

                                    <button type="submit" id="recommendtheClient" name="recommendBorrower" class="btn btn--primary" onclick="recommendClient(); return false;" >Submitszz</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL CODE FOR APPROVAL TABLE -->
                    <div class="model__container1 fadIn">
                        <div class="modal1">
                            <span onclick="" class="close1" id="closeModal1" title="Close Modal">&times;</span>
                            <h1 class="mag-2">Enter recommended amount</h1>

                            <div class="guarntor__form">
                                <!-- form location ../handlers/formHandler.php -->
                                <form action="#" method="" id="changeAmountadmin">
                                    <input type="number" name="adminRecommendAmount" id="adminRecommendAmount" placeholder="gh1000" required>
                                    <input type="submit" value="confirm" name="changeRecAmount" class="btn btn--primary" onclick="recommendamountbyAdmin(); return false;">
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL CODE FOR VIEW EMPLOYEE -->
                    <div class="model__container2  fadIn">
                        <div class="modal2">
                            <span onclick="" class="close2" id="closeModal2" title="Close Modal">&times;</span>

                            <h1>Employee information</h1>

                            <div class="employeeDisplaycontainer">
                                <form action="" method="post" id="employeeDisplayFOrm">
                                    <div class="labelOne">
                                        <label for="employeeName">Name</label>
                                        <input type="text" name="employeeName" id="name" class="employeeFormFieldData">
                                    </div>

                                    <div class="labelOne">
                                        <label for="employeeName">Number</label>
                                        <input type="text" name="employeenumber" id="name" class="employeeFormFieldData">
                                    </div>

                                    <div class="labelOne">
                                        <label for="employeeName">Location</label>
                                        <input type="text" name="employeelocation" id="name" class="employeeFormFieldData">
                                    </div>

                                    <div class="labelOne">
                                        <label for="employeeName">Department</label>
                                        <input type="text" name="employeeDepartement" id="name" class="employeeFormFieldData">
                                    </div>

                                    <div class="labelOne">
                                        <label for="employeeName">Employee ID</label>
                                        <input type="text" name="emplyeeid" id="name" class="employeeFormFieldData">
                                    </div>

                                    <div class="labelOne">
                                        <label for="employeeName">Password</label>
                                        <input type="text" name="employeePassword" id="name" class="employeeFormFieldData">
                                    </div>

                                    <input type="submit" value="Update" class="btn btn--primary">
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </main>
        </div>
    </div>


    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


    <script src="../js/script.js"></script>
    <script>


        // loadAllBorrowers();
        document.addEventListener("DOMContentLoaded", () => {
            loadAllBorrowers();
            getAllApprovedCLients();
            getAllDisbursementList();
            getAllRegisteredClient();
            getChronicCLients();
            loadAllEmployees();
            getBlacklistedClients();
            loadAllExpenses();


        });

        window.addEventListener('load', (event) => {
            document.getElementsByClassName('spinner__container')[0].style.display = 'none';
        });

    </script>
</body>
</html>
