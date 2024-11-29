<?php
session_start();
if (!isset($_SESSION['username']) && !isset($_SESSION['id'])) { ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Campus Resto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>
      <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <form class="border shadow p-3 rounded" action="check-login.php" method="post" style="width: 450px;">
              <h1 class="text-center p-3">LOGIN</h1>
              <?php if (isset($_GET['error'])) { ?>
              <div class="alert alert-danger" role="alert">
                  <?= $_GET['error'] ?>
              </div>
              <?php } ?>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" required>
          </div>
          <div class="mb-1">
            <label class="form-label">Role:</label>
          </div>
          <select class="form-select mb-3" name="role" aria-label="Default select example">
              <option selected value="student">student</option>
              <option value="admin">admin</option>
              <option value="restaurant">restaurant</option>
          </select>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
      </div>
</body>
</html>
<?php } else {
    header("Location: redirect.php"); // Redirect to redirect.php if already logged in
    exit();
} ?>