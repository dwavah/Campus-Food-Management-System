<?php
// Include database connection file
require_once 'config.php';

// Fetch products from the database
$sql = "SELECT * FROM product";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Guild Canteen</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
  <style>
    .navbar-custom {
      background-color: #8B4513; /* SaddleBrown background for the navbar */
      height: 80px; /* Increase navbar height */
      display: flex;
      justify-content: space-between; /* Space between items */
      align-items: center; /* Center the content vertically */
      padding: 0 20px; /* Add padding */
    }
    .navbar-brand-custom {
      color: #ffcc00; /* Gold color for the brand */
      font-size: 2rem; /* Increase font size */
      font-family: 'Georgia', serif; /* Change font style */
      font-weight: bold; /* Make font bold */
      text-align: center; /* Center the brand */
      flex-grow: 1; /* Allow the brand to grow */
    }
    .nav-link-custom {
      color: #ffffff; /* White color for the nav links */
    }
    .nav-link-custom:hover {
      color: #ffcc00; /* Gold color on hover */
    }
    .welcome-message {
      font-size: 2.5rem; /* Bigger font size for the welcome message */
      text-align: center;
      margin-top: 20px;
      font-family: 'Arial', sans-serif; /* Change font style */
    }
    .navbar-nav {
      margin: 0; /* Remove margin */
    }
    .btn-custom {
      background-color: #28a745; /* Green background for buttons */
      color: #ffffff; /* White text color */
    }
    .btn-custom:hover {
      background-color: #218838; /* Darker green on hover */
    }
    body {
      background-color: #6c757d; /* Light gray background for the page */
    }
    .card-footer .btn {
      display: block;
      width: 100%;
      text-align: center;
    }
  </style>
</head>

<body>
  <!-- Navbar start -->
  <nav class="navbar navbar-expand-md navbar-custom">
    <a class="navbar-brand navbar-brand-custom" href=""><i class="fas fa-utensils"></i>&nbsp;&nbsp;Guild Canteen</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="checkout.php"><i class="fas fa-money-check-alt mr-2"></i>Checkout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-custom" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Navbar end -->

  <div class="container mt-5">
    <h2 class="welcome-message">Welcome to the Guild Canteen</h2>
    <p class="text-center">What do you want for lunch?</p>

    <div class="row">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
          <div class="card-deck">
            <div class="card p-2 border-secondary mb-2">
              <img src="<?= $row['product_image'] ?>" class="card-img-top" height="250">
              <div class="card-body p-1">
                <h4 class="card-title text-center text-info"><?= $row['product_name'] ?></h4>
                <h5 class="card-text text-center text-danger"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['product_price'],2) ?>/-</h5>
              </div>
              <div class="card-footer p-1">
                <form action="" class="form-submit">
                  <div class="row p-2">
                    <div class="col-md-6 py-1 pl-4">
                      <b>Quantity : </b>
                    </div>
                    <div class="col-md-6">
                      <input type="number" class="form-control pqty" value="1">
                    </div>
                  </div>
                  <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                  <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                  <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                  <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
                  <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                  <button class="btn btn-custom btn-block addItemBtn"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Add to cart</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Send product details in the server
    $(".addItemBtn").click(function(e) {
      e.preventDefault();
      var $form = $(this).closest(".form-submit");
      var pid = $form.find(".pid").val();
      var pname = $form.find(".pname").val();
      var pprice = $form.find(".pprice").val();
      var pimage = $form.find(".pimage").val();
      var pcode = $form.find(".pcode").val();
      var pqty = $form.find(".pqty").val();

      $.ajax({
        url: 'action.php',
        method: 'post',
        data: {
          pid: pid,
          pname: pname,
          pprice: pprice,
          pqty: pqty,
          pimage: pimage,
          pcode: pcode
        },
        success: function(response) {
          $("#message").html(response);
          window.scrollTo(0, 0);
          load_cart_item_number();
        }
      });
    });

    // Load total no.of items added in the cart and display in the navbar
    load_cart_item_number();

    function load_cart_item_number() {
      $.ajax({
        url: 'action.php',
        method: 'get',
        data: {
          cartItem: "cart_item"
        },
        success: function(response) {
          $("#cart-item").html(response);
        }
      });
    }
  });
  </script>
</body>
</html>