<?php
session_start();
require 'assets/db.php';
require 'assets/autoloader.php';
require 'assets/function.php';

// Check if user is logged in (adjust session variable as per your project setup)
$user_id = $_SESSION['userId'];

// Fetch all loan applications for the logged-in user
$sql = "SELECT * FROM loan_applications WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>My Loan Applications</title>
</head>
<body class="bg-gradient-seconday">
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
        <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item ">  <a class="nav-link" href="accounts.php">Accounts</a></li>
      <li class="nav-item ">  <a class="nav-link" href="statements.php">Account Statements</a></li>
      <li class="nav-item ">  <a class="nav-link" href="transfer.php">Funds Transfer</a></li>
      <li class="nav-item ">  <a class="nav-link" href="loan_applications.php">Apply for Loan</a></li>
      <li class="nav-item ">  <a class="nav-link active" href="user_loan_status.php">Loan status</a></li>
      <!-- <li class="nav-item ">  <a class="nav-link" href="profile.php">Profile</a></li> -->


    </ul>
    <?php include 'sideButton.php'; ?>
    
  </div>
</nav><br><br><br>
    <div class="container mt-5">
        <h2>My Loan Applications</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Amount</th>
                    <th>Tenure (months)</th>
                    <th>Loan Type</th>
                    <th>Interest Rate (%)</th>
                    <th>Status</th>
                    <th>Application Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['tenure']; ?></td>
                        <td><?php echo $row['Loantype']; ?> </td>
                        <td><?php echo $row['interest_rate']; ?></td>
                        <td>
                            <?php 
                            if ($row['status'] == 'Pending') {
                                echo '<span class="badge badge-warning">Pending</span>';
                            } elseif ($row['status'] == 'Approved') {
                                echo '<span class="badge badge-success">Approved</span>';
                            } else {
                                echo '<span class="badge badge-danger">Disapproved</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo $row['application_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$con->close();
?>
