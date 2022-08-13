<?php 
  include "../../config/config.php";
  include "../../includes/class/Borrower.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../clients/styles1.css">
    <title>Chronic Customers</title>
</head>
<body>
    <style>
      .header_{
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .print_{
        border: none;
        background-color: #841207;
        padding: 2rem 4rem;
        border-radius: 5px;
        color: white;
        font-weight: bolder;

        cursor: pointer;
        margin: 4rem;
      }

      h1{
        margin: 2rem;
        font-size: 2rem;
        width: 100vw;
        line-height: 3rem;
        text-align: center;
        /* border-bottom: 4px solid black; */
      }

      h2{
        text-align: center;
        text-transform: uppercase;
        font-weight: bolder;
        font-size: 3rem;
      }

      .chronic_{
        font-size: 1.8rem;
        line-height: 2rem;
        display: block;
      }

      .print_table-container{
        margin: 0 auto;
        width: 85vw;
      }

      #cdate{
        text-align: center;
      }

      @media print { 
      /* All your print styles go here */
      .print_button { display: none !important; } 
      .print_table-container {width: 100vw;}
      .table-content{ overflow-y: hidden; font-weight: 400 !important;}
      .table-header1{
        font-size: .9rem;
      }
      footer{
        margin-top: 15rem; 
        text-align: center; 
        text-transform: uppercase; 
        font-weight: bold; 
        font-size: 1.5rem;
      }
      }
    </style>




    <div class="header_">
        <h1> PRIMEBOND MICROCREDIT ENTERPRISE
        </h1>

      <div class="print_button">
        <button class="print_" onclick="window.print();">PRINT</button>
      </div>
    </div>
    <h2>Chronic </h2>
   


   <div class="print_table-container">
      <div class="custom-table">
        
        <div class="table-header1">
            <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                <thead>
                <tr>
                    <th>Full</th>
                    <th>Loan amount</th>
                    <th>Balance</th>
                    <th>Telephone</th>
                    <th>Location</th>
                    <th>Complete</th>
                </tr>
                </thead>
            </table>
        </div>

        <div class="table-content">
            <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="employeeTable">
                <tbody>
                <?php 
                  $sql = "SELECT clients.fullname, clients.telephone,clients.client_id,clients.location,clients.votersID, account.loanAmount,account.laonReceivedOn, account.pendingBalance,account.completePayment FROM clients INNER JOIN account ON account.cleintID_fk = clients.client_id INNER JOIN chronic ON chronic.clientID_fk = clients.client_id AND account.cleintID_fk = chronic.clientID_fk";

                  $query = $con->prepare($sql);
                  $query->execute();
          
                  $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);

                  if ($queryResult) {
                    foreach ($queryResult as $key => $value) {
                      echo "
                        <tr class='customer__table-body-row' style='text-transform: uppercase;'>
                            <td>{$value['fullname']}</td>
                            <td>GH{$value['loanAmount']}.00</td>
                            <td>GH{$value['pendingBalance']}.00</td>
                            <td>{$value['telephone']}</td>
                            <td>{$value['location']}</td>
                            <td>{$value['completePayment']}</td>
                        </tr>
                      ";
                    }
                  }else {
                    echo "<h2>Sorry no chronic clients for now</h2>";
                  }

                  
                ?>
                
                </tbody>
            </table>
        </div>
      </div>   
   </div>

   <footer>
     <h4 id="cdate">PrimeBond Limited  <script> dateTime</script></h4>
   </footer>
    <script>
      let c_date = document.getElementById('cdate');
      
      let today = new Date();
      let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
      let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      let dateTime = date+' '+time;
      c_date.innerText = "PRIMEBOND LIMITED " + dateTime
    </script>
</body>
</html>