<?php
require 'config.php';
session_start();

function transferFunds($student_id, $amount) {
    global $conn;

    // Get the logged-in restaurant's ID from the session
    if (!isset($_SESSION['restaurant_id'])) {
        echo "Restaurant not logged in.";
        return;
    }
    $restaurant_id = $_SESSION['restaurant_id'];

    // Start the transaction
    $conn->begin_transaction();

    try {
        // Debit the student's account
        $stmt = $conn->prepare("UPDATE students SET balance = balance - ? WHERE student_id = ?");
        $stmt->bind_param("ds", $amount, $student_id); // Changed to "ds" for string student_id
        $stmt->execute();

        // Check if the student's balance is sufficient
        if ($stmt->affected_rows === 0) {
            throw new Exception("Insufficient balance or student not found.");
        }

        // Credit the restaurant's account
        $stmt = $conn->prepare("UPDATE restaurants SET balance = balance + ? WHERE restaurant_id = ?"); // Changed to restaurant_id
        $stmt->bind_param("ds", $amount, $restaurant_id); // Changed to "ds" for string restaurant_id
        $stmt->execute();

        // Check if the restaurant exists
        if ($stmt->affected_rows === 0) {
            throw new Exception("Restaurant not found.");
        }

        // Commit the transaction
        $conn->commit();
        echo "Transaction successful!";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }
}

// Example usage (You'll likely handle this in a separate form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id']; 
    $amount = $_POST['amount']; 

    transferFunds($student_id, $amount);
}

$conn->close();
?>