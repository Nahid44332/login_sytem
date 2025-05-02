<?php include "db.php"; session_start(); ?>

<?php
$msg = "";
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $row['name'];
            $_SESSION['user_id'] = $row['id'];
            header("Location: dashboard.php");
        } else {
            $msg = "❌ Invalid password!";
        }
    } else {
        $msg = "❌ User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background: #f8f9fa;
    }
    .container {
        max-width: 400px;
        margin-top: 80px;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="container">
  <h3 class="text-center mb-4">Login</h3>
  <?php if ($msg) echo "<div class='alert alert-danger'>$msg</div>"; ?>
  <form method="POST">
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Email Address" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button name="login" class="btn btn-success w-100">Login</button>
    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
  </form>
</div>
</body>
</html>
