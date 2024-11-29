<?php
require 'config.php';

function transferFunds($student_id, $restaurant_id, $amount) {
    global $conn;

    // Start the transaction
    $conn->begin_transaction();

    try {
        // Debit the student's account
        $stmt = $conn->prepare("UPDATE students SET balance = balance - ? WHERE student_id = ?");
        $stmt->bind_param("di", $amount, $student_id);
        $stmt->execute();

        // Check if the student's balance is sufficient
        if ($stmt->affected_rows === 0) {
            throw new Exception("Insufficient balance or student not found.");
        }

        // Close the statement
        $stmt->close();

        // Credit the restaurant's account
        $stmt = $conn->prepare("UPDATE restaurants SET balance = balance + ? WHERE _id = ?");
        $stmt->bind_param("di", $amount, $restaurant_id);
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

    // Close the statement
    $stmt->close();
}

// Example usage
$student_id = 1; // Replace with the actual student _id
$restaurant_id = 1; // Replace with the actual restaurant _id
$amount = 50.00; // Replace with the actual amount to transfer

transferFunds($student_id, $restaurant_id, $amount);

$conn->close();
?>