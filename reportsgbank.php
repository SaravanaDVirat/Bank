<?php
session_start();
error_reporting(0);
include('assets/db.php');
include('assets/autoloader.php');
include('assets/function.php');

// Check if session is active
if (strlen($_SESSION['adminId']) == 0) {
    header('location:logout.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bank Management System</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/app.css">
    <style>
        .loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #F5F8FA;
            z-index: 9998;
            text-align: center;
        }

        .plane-container {
            position: absolute;
            top: 50%;
            left: 50%;
        }
    </style>
</head>
<body class="bg-gradient-seconday"><br><br><br>
<!-- Pre-loader -->
<div id="loader" class="loader">
    <div class="plane-container">
        <div class="preloader-wrapper small active"></div>
    </div>
</div>

<div id="app">
<div class="page has-sidebar-left bg-light height-full">
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card my-3 no-b">
                    <div class="card-body">
                        <header class="blue accent-3 relative nav-sticky">
                            <div class="container-fluid text-white">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="my-3">Bank Account Reports</h3>
                                    </div>
                                </div>
                            </div>
                        </header><br />
                        
                        <?php
                        // Retrieve and validate dates from POST
                        $fdate = date("Y-m-d", strtotime($_POST['fromdate']));
                        $tdate = date("Y-m-d", strtotime($_POST['todate']));
                        ?>
                        <h4 align="center" style="color:blue">
                            Bank Account Report from <?php echo htmlspecialchars($fdate); ?> to <?php echo htmlspecialchars($tdate); ?>
                        </h4>

                        <table class="table table-bordered table-hover data-tables" data-options='{ "paging": false; "searching": false }'>
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Branch Name</th>
                                    <th>IFSC</th>
                                    <th>Account Holder Name</th>
                                    <th>Account No</th>
                                    <th>Balance</th>       
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // SQL query to fetch report data within the specified date range
                                $stmt = $con->prepare("SELECT *
                                                      FROM useraccounts
                                                      LEFT JOIN branch ON useraccounts.branch = branch.branchId
                                                      WHERE DATE(date) BETWEEN ? AND ?");
                                $stmt->bind_param("ss", $fdate, $tdate);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                $cnt = 1;
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlspecialchars($row['branchName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['IFSC']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['accountNo']); ?></td>
                                    <td><?php echo htmlspecialchars($row['balance']); ?></td>
                                </tr>
                                <?php
                                    $cnt++;
                                }
                                $stmt->close();
                                ?>          
                            </tbody>
                        </div>
                        </table>
                         <!-- Print Button -->
 <div style="text-align: center; margin-bottom: 15px;">
                            <button onclick="window.print();" class="btn btn-primary">Print Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
 <a class="navbar-brand" href="#">
    <img src="images/logo.png" style="object-fit:cover;object-position:center center" width="30" height="30" class="d-inline-block align-top" alt="">
   <!--  <i class="d-inline-block  fa fa-building fa-fw"></i> --><?php echo bankname; ?>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
        <a class="nav-link" href="mindex.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item ">  <a class="nav-link" href="cindex.php">Withdraw and Deposit</a></li>
      <li class="nav-item ">  <a class="nav-link" href="maccounts.php">Accounts</a></li>
      <li class="nav-item ">  <a class="nav-link" href="maddnew.php">Add New Account</a></li>
      <li class="nav-item ">  <a class="nav-link" href="mfeedback.php">Feedback</a></li>
      <li class="nav-item ">  <a class="nav-link active" href="bankaccountreports.php">Reports</a></li>
      <!-- <li class="nav-item ">  <a class="nav-link" href="transfer.php">Funds Transfer</a></li> -->
      <!-- <li class="nav-item ">  <a class="nav-link" href="profile.php">Profile</a></li> -->


    </ul>
    <?php include 'msideButton.php'; ?>
    
  </div>
    </nav>

<!--/#app -->
<script src="assets/js/app.js"></script>
</body>
</html>
<?php } ?>
