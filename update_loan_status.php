<?php
session_start();
require 'assets/db.php';
require 'assets/autoloader.php';
require 'assets/function.php';

// Check if admin is logged in (add your admin session check here)

if (isset($_GET['id']) && isset($_GET['status'])) {
    $loan_id = $_GET['id'];
    $status = $_GET['status'];

    // Update the loan application status
    $sql = "UPDATE loan_applications SET status = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $status, $loan_id);

    if ($stmt->execute()) {
        echo "Loan application status updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
    header("Location: admin_loan_management.php"); // Redirect back to the loan management page
    exit();
} else {
    echo "Invalid request.";
}
?>
