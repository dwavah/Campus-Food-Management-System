<?php
// Start the session
session_start();

// Include the database configuration file
include "config.php"; 

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);

    if (empty($username)) {
        header("Location: index.php?error=User Name is Required");
        exit();
    } else if (empty($password)) {
        header("Location: index.php?error=Password is Required");
        exit();
    } else {
        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT id, password, resto_id FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $resto_id);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $id;
                $_SESSION['role'] = $role;

                if ($role === 'restaurant') {
                    // Use the resto_id directly from the Users table
                    $_SESSION['restaurant_id'] = $resto_id; 
                }

                header("Location: redirect.php");
                exit();
            } else {
                header("Location: index.php?error=Incorrect password");
                exit();
            }
        } else {
            header("Location: index.php?error=Username or role not found");
            exit();
        }
    }
} else {
    header("Location:index.php");
    exit();
}
?>