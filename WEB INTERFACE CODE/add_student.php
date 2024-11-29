<?php
session_start();
// Include database connection file
require_once 'config.php';

function generateRandomId() {
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digits = '0123456789';
    $randomLetters = '';
    $randomDigit = '';

    // Generate three random letters
    for ($i = 0; $i < 3; $i++) {
        $randomLetters .= $letters[rand(0, strlen($letters) - 1)];
    }

    // Generate one random digit
    $randomDigit = $digits[rand(0, strlen($digits) - 1)];

    // Concatenate the letters and digit
    return $randomLetters . $randomDigit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if any required field is empty
    if (empty($_POST["name"]) || empty($_POST["access_number"]) || empty($_POST["uid"]) || empty($_POST["password"]) || empty($_POST["balance"])) {
        $err = "Blank Values Not Accepted";
    } else {
        $name = $_POST['name'];
        $access_number = $_POST['access_number'];
        $uid = $_POST['uid'];
        $balance = $_POST['balance'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Generate a random student ID
        $student_id = generateRandomId();

        // Start the transaction
        $conn->begin_transaction();

        try {
            // Insert into students table using student_id
            $stmt = $conn->prepare("INSERT INTO students (student_id, name, access_number, uid, password, balance) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssd", $student_id, $name, $access_number, $uid, $password, $balance);
            $stmt->execute();

            // Check if the student was inserted successfully
            if ($stmt->affected_rows === 0) {
                throw new Exception("Failed to insert into students table.");
            }

            // Commit the transaction
            $conn->commit();
            $success = "Student added successfully!";
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $conn->rollback();
            $err = "Error: " . $e->getMessage();
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>
<body>
  <!-- Navbar start -->
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php"><i class="fas fa-pizza-slice"></i>&nbsp;&nbsp;Campus Resto</a>
   
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php"><i class="fas fa-mobile-alt mr-2"></i>Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="checkout.php"><i class="fas fa-money-check-alt mr-2"></i>Checkout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="add_student.php"><i class="fas fa-user-graduate mr-2"></i>Add Student</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_restaurant.php"><i class="fas fa-utensils mr-2"></i>Add Restaurant</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Navbar end -->

    <div class="container mt-5">
        <h2>Add Student</h2>
        <?php if (isset($err)) { echo "<div class='alert alert-danger'>$err</div>"; } ?>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <form action="add_student.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="access_number">Access Number:</label>
                <input type="text" class="form-control" id="access_number" name="access_number" required>
            </div>
            <div class="form-group">
                <label for="uid">UID:</label>
                <input type="text" class="form-control" id="uid" name="uid" required>
            </div>
            <div class="form-group">
                <label for="balance">Balance:</label>
                <input type="number" class="form-control" id="balance" name="balance" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Student</button>
        </form>
    </div>
</body>
</html>