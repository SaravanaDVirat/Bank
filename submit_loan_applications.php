<?php
session_start();
require 'assets/db.php';
require 'assets/autoloader.php';
require 'assets/function.php';

// Assuming the user is logged in and their user ID is stored in the session
$user_id = $_SESSION['userId'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $tenure = $_POST['tenure'];
    $loan_type = $_POST['Loantype'];
    $interest_rate = $_POST['interest_rate'];

    // Insert loan application into the database
    $sql = "INSERT INTO loan_applications (user_id, amount, tenure,Loantype, interest_rate)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iidsd", $user_id, $amount, $tenure, $loan_type, $interest_rate);

    if ($stmt->execute()) {
        echo "<script>alert('Loan application submitted successfully');window.location.href='user_loan_status.php'</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    echo "Invalid request method.";
}
?>
