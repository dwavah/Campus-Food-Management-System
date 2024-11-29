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
    if (empty($_POST["name"]) || empty($_POST["balance"]) || empty($_POST["password"])) {
        $err = "Blank Values Not Accepted";
    } else {
        $name = $_POST['name'];
        $balance = $_POST['balance'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Generate a random restaurant ID
        $restaurant_id = generateRandomId();

        // Start the transaction
        $conn->begin_transaction();

        try {
            // Insert into Restaurants table
            $stmt1 = $conn->prepare("INSERT INTO Restaurants (restaurant_id, name, balance, password) VALUES (?, ?, ?, ?)");
            $stmt1->bind_param("ssds", $restaurant_id, $name, $balance, $password);
            $stmt1->execute();

            // Check if the restaurant was inserted successfully
            if ($stmt1->affected_rows === 0) {
                throw new Exception("Failed to insert into restaurants table.");
            }

            // Commit the transaction
            $conn->commit();
            $success = "Restaurant added successfully!";
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $conn->rollback();
            $err = "Transaction failed: " . $e->getMessage();
        }

        // Close the statements
        if (isset($stmt1)) {
            $stmt1->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Restaurant</title>
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
        <h2>Add Restaurant</h2>
        <?php if (isset($err)) { echo "<div class='alert alert-danger'>$err</div>"; } ?>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <form action="add_restaurant.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="balance">Balance:</label>
                <input type="number" class="form-control" id="balance" name="balance" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Restaurant</button>
        </form>
    </div>
</body>
</html>