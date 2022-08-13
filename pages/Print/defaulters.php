<?php 
    include "../../config/config.php";
    include "../../includes/class/Acount.php";
    $accountObject = new Account($con);
    $today = date('Y-m-d');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../clients/styles1.css">
    <title>Defaulters</title>
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
        font-size: 5rem;
        width: 14vw;
        line-height: 5rem;

        border-bottom: 4px solid black;
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
      .table-content{ overflow-y: hidden;}
      footer{margin-top: 5rem; text-align: center; text-transform: uppercase; font-weight: bold; font-size: 1.5rem;}
      }
    </style>




    <div class="header_">
        <h1> PRIMEBOND LIMITED
         
        </h1>

      <div class="print_button">
        <button class="print_" onclick="window.print();">PRINT</button>
      </div>
    </div>
    <h2>Defaulters</h2>
   


   <div class="print_table-container">
      <div class="custom-table">
        
        <div class="table-header1">
            <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                <thead>
                <tr>
                    <th>FullNAME</th>
                    <th>NUMBER</th>
                    <th>LOCATION</th>
                    <th>DEFAULTED AMOUNT</th>
                    <th>DATE DEFAULTED</th>
                   
                </tr>
                </thead>
            </table>
        </div>

        <div class="table-content">
            <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="employeeTable">
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
                             </tr>";
                             }
                         }else {
                             echo "<h1>Sorry no defaulters for now</h1>";
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