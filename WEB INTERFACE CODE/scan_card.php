<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'];
    error_log("Received UID: $uid");

    $stmt = $conn->prepare("SELECT student_id, balance FROM students WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        error_log("Card verified");
        echo "Card verified";
    } else {
        error_log("Card not recognized");
        echo "Card not recognized";
    }
}
?>
