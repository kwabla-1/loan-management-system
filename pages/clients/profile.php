<?php 
    include "../../config/config.php";
    include "../../includes/class/Customers.php";

    $customerObject = new Customer($con);
    // $allClientDetails = $customerObject->getAllCLientInfor(($con))
    if (isset($_GET['cliendID'])) {
        $clientID = $_GET['cliendID'];
        $clientData = $customerObject->getClientById($clientID,$con);
        var_dump($clientData[0]);
    }
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
    <header class="logo__container">
        <span class="logo__text">PRIMEBOND</span>

        <div class="user__profile-settings">
            <a href="#" class="link-normalize">
                <div class="name">
                    <span>Tupack Shakur</span>
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
                <div class="top__section">
                    <img src="../../img/img_avatar.png" alt="client image" class="user-icon">
                    <h2><?php echo $clientData[0]['fullname'] ?></h2>
                    <h3><?php echo $clientData[0]['telephone'] ?></h3>
                    <h3><?php echo $clientData[0]['location'] ?></h3>
                </div>
    
                <div class="bottom__section">
                    <div class="pair">
                        <div class="one">
                            <span>Fullname: <span class="bold"><?php echo $clientData[0]['fullname'] ?></span></span>
                            <span>Location: <span class="bold"><?php echo $clientData[0]['location'] ?></span></span>
                            <span>Number: <span class="bold"><?php echo $clientData[0]['telephone'] ?></span></span>
                        </div>
                        
                        <div class="one">
                            <span>Loan: <span class="bold"><?php echo $clientData[0]['loanAmount'] ?></span></span>
                            <span>Pending: <span class="bold">Gh$<?php echo $clientData[0]['pendingBalance'] ?></span></span>
                            <span>Total payment <span class="bold">Gh$<?php echo $clientData[0]['laonInterest'] ?></span></span>
                        </div>

                        <div class="one">
                            <span>Complete payment: <span class="bold"><?php echo $clientData[0]['completePayment'] ?></span></span>
                            <span>Next payment: <span class="bold"><?php echo $clientData[0]['nextPayment'] ?></span></span>
                            <span>Reference: <span class="bold">#<?php echo $clientData[0]['reference_code'] ?></span></span>
                        </div>

                        <div class="one">
                            <span>Status: <span class="bold"><?php echo $clientData[0]['status'] ?></span></span>
                            <span>Chronic: <span class="bold">No</span></span>
                            <span>Weeks: <span class="bold"><?php echo $clientData[0]['daysLeft'] ?></span></span>
                        </div>

                        <div class="one">
                            <span>Gurantors: <span class="bold"><?php echo $clientData[0]['gfullname'] ?></span></span>
                            <span>Location: <span class="bold"><?php echo $clientData[0]['glocation'] ?></span></span>
                            <span>Number: <span class="bold"><?php echo $clientData[0]['gtelephone'] ?></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right__content">
               <nav>
                   <ul class="userTabs-link">
                       <li><a href="#" class="selected-tab tabLink" onclick="openSelectTac(event,'accoutnUPdate')">Account Update</a></li>
                       <li><a href="#" class="tabLink" onclick="openSelectTac(event,'paymentHistory')">Payment History</a></li>
                      
                   </ul>
               </nav>

               <div class="accountupdate fadIn tab" id="accoutnUPdate">
                <div class="customer__form">
                    <form action="" method="POST" class="form">
                        <div class="form_container">
                            <div class="first_column">
                                <h4>Client details</h4>
                                <div class="two">
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
                        <input type="submit" name="register_c" value="Update" class="btn btn--primary">
                    </form>
                </div>
               </div>

                <div class="paymentHistory fadIn tab" id="paymentHistory" style="display:none">
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
                                <th>Date</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                        <div class="table-content">
                          <table cellpadding="0" cellspacing="0" border="0" class="repaymentTable">
                            <tbody> 
                              <tr>
                                <td>1</td>
                                <td>Derrick</td>
                                <td>#23f3</td>
                                <td>Gh$200</td>
                                <td>12th July 2021</td>
                              </tr>
                              <tr>
                                <td>1</td>
                                <td>Derrick</td>
                                <td>#23f3</td>
                                <td>Gh$200</td>
                                <td>12th July 2021</td>
                              </tr>

                            </tbody>
                          </table>
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
    
</body>
</html>