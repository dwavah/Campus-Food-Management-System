<?php
require_once 'config.php';
session_start(); 

// Function to calculate the cart total
function getCartTotal($conn) {
    $total = 0;
    $sql = "SELECT SUM(total_price) AS grand_total FROM cart";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['grand_total'];
    }

    return $total;
}

$grand_total = getCartTotal($conn); // Get the cart total

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="Sahil Kumar">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Checkout</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="home.php"><i class="fas fa-pizza-slice"></i>&nbsp;&nbsp;Campus Resto</a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link active" href="home.php"><i class="fas fa-mobile-alt mr-2"></i>Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="checkout.php"><i class="fas fa-money-check-alt mr-2"></i>Checkout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_student.php"><i class="fas fa-user-graduate mr-2"></i>Add Student</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add-restaurant.php"><i class="fas fa-utensils mr-2"></i>Add Restaurant</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Navbar end -->

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="text-center mb-4">Checkout</h4>
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center">
            <thead class="thead-dark">
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $stmt = $conn->prepare("SELECT * FROM cart");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $row['product_name'] ?></td>
                <td><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'],2); ?></td>
                <td><?= $row['qty'] ?></td>
                <td><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['total_price'],2); ?></td>
              </tr>
              <?php endwhile; ?>
              <tr>
                <td colspan="3"><b>Grand Total</b></td>
                <td><b><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($grand_total,2); ?></b></td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- <div class="alert alert-info text-center mt-4">
          <h5>Please enter the Student ID to check out.</h5>
        </div>
        <form action="transfer.php" method="post" class="text-center mt-4">
          <div class="form-group">
            <label for="student_id">Student ID:</label>
            <input type="text" class="form-control" id="student_id" name="student_id" required>
          </div>
          <input type="hidden" name="amount" value="<?= $grand_total ?>"> 
          <button type="submit" class="btn btn-primary mt-3">Pay Now</button>
        </form> -->
      </div>
    </div>
  </div>
<!-- Add this right before </body> -->

<!-- Dependencies -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

<!-- Replace the existing form with this -->
<div class="text-center mt-4">
    <div class="alert alert-info">
        <h5>Click below to scan your student card for payment</h5>
    </div>
    <button onclick="scanCard()" class="btn btn-primary btn-lg">
        <i class="fas fa-credit-card mr-2"></i>Scan Card
    </button>
    <div id="scanStatus"></div>
</div>

<!-- Include the scanning JavaScript -->
<script>
function scanCard() {
    // Disable button while scanning
    const scanBtn = document.querySelector('button[onclick="scanCard()"]');
    scanBtn.disabled = true;
    
    // Show scanning status
    const statusDiv = document.getElementById('scanStatus');
    statusDiv.className = 'alert alert-info mt-3';
    statusDiv.innerHTML = 'Please scan your card...';

    // You'll implement WebSocket connection here
    // For now, simulate a scan after 3 seconds
    setTimeout(() => {
        const amount = <?php echo $grand_total; ?>;
        processPayment('SIMULATED_UID', amount);
    }, 3000);
}

function processPayment(uid, amount) {
    fetch('transfer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `student_id=${uid}&amount=${amount}`
    })
    .then(response => response.text())
    .then(result => {
        const statusDiv = document.getElementById('scanStatus');
        if (result.includes('successful')) {
            statusDiv.className = 'alert alert-success mt-3';
            statusDiv.innerHTML = 'Payment successful! Redirecting...';
            setTimeout(() => window.location.href = 'home.php', 2000);
        } else {
            statusDiv.className = 'alert alert-danger mt-3';
            statusDiv.innerHTML = 'Payment failed: ' + result;
            document.querySelector('button[onclick="scanCard()"]').disabled = false;
        }
    })
    .catch(error => {
        const statusDiv = document.getElementById('scanStatus');
        statusDiv.className = 'alert alert-danger mt-3';
        statusDiv.innerHTML = 'Error processing payment: ' + error;
        document.querySelector('button[onclick="scanCard()"]').disabled = false;
    });
}
</script>
</body>
</html>
</body>
</html>