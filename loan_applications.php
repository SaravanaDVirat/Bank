<?php
session_start();
require 'assets/db.php';
require 'assets/autoloader.php';
require 'assets/function.php';

$user_id = $_SESSION['userId'];

// Fetch user details to check eligibility
$sql = "SELECT age, monthly_income FROM useraccounts WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$is_eligible = true;
$eligibility_message = "";


// Query to check if the user maintained a minimum balance of 5000 over the last month

// Check if balance was above the required amount for at least 25 days in the last month (for example)

// Eligibility criteria checks
if ($user['age'] < 18) {
    $is_eligible = false;
    $eligibility_message = "You must be at least 18 years old to apply for a loan.";
} elseif ($user['monthly_income'] < 2000) {
    $is_eligible = false;
    $eligibility_message = "Your monthly income must be at least 2000 to apply for a loan.";
} else {
    $sql = "SELECT COUNT(*) AS active_loans FROM loan_applications WHERE user_id = ? AND status = 'Approved'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $loan_result = $stmt->get_result();
    $active_loans = $loan_result->fetch_assoc()['active_loans'];

    if ($active_loans >= 1) {
        $is_eligible = false;
        $eligibility_message = "You cannot apply for a new loan while an existing loan is active.";
    }
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Apply for Loan</title>
    <script>
        function setInterestRate() {
            const loanType = document.getElementById('Loantype').value;
            let interestRate = 0;

            switch (loanType) {
                case 'Agriculture':
                    interestRate = 5; // Example rate for Agriculture
                    break;
                case 'Health':
                    interestRate = 4; // Example rate for Health
                    break;
                case 'Business':
                    interestRate = 7; // Example rate for Business
                    break;
                default:
                    interestRate = 0;
            }

            document.getElementById('interest_rate').value = interestRate;
        }
    </script>
</head>
<body class="bg-gradient-secondary">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
 <a class="navbar-brand" href="#">
    <img src="images/logo.png" width="30" height="30" class="d-inline-block align-top" alt=""><?php echo bankname; ?>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="accounts.php">Accounts</a></li>
      <li class="nav-item"><a class="nav-link" href="statements.php">Account Statements</a></li>
      <li class="nav-item"><a class="nav-link" href="transfer.php">Funds Transfer</a></li>
      <li class="nav-item"><a class="nav-link active" href="loan_applications.php">Apply for Loan</a></li>
      <li class="nav-item"><a class="nav-link" href="user_loan_status.php">Loan Status</a></li>
    </ul>
    <?php include 'sideButton.php'; ?>
  </div>
</nav><br><br><br>

<div class="container mt-5">
    <h2>Loan Application</h2>
    <?php if (!$is_eligible): ?>
        <div class="alert alert-danger">
            <?php echo $eligibility_message; ?>
        </div>
    <?php else: ?>
        <form action="submit_loan_applications.php" method="POST">
            <div class="form-group">
                <label for="amount">Loan Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="tenure">Tenure (in months)</label>
                <input type="number" class="form-control" id="tenure" name="tenure" required>
            </div>
            <div class="form-group">
                <label for="loan_type">Loan Type</label>
                <select class="form-control" id="Loantype" name="Loantype" onchange="setInterestRate()" required>
                    <option value="">Select Loan Type</option>
                    <option value="Agriculture">Agriculture</option>
                    <option value="Health">Health</option>
                    <option value="Business">Business</option>
                </select>
            </div>
            <div class="form-group">
                <label for="interest_rate">Interest Rate (%)</label>
                <input type="number" class="form-control" id="interest_rate" name="interest_rate" readonly required>
            </div>
            <button type="submit" class="btn btn-primary">Apply</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
