<?php include "db.php"; session_start(); ?>

<?php
$msg = "";
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')";
    if ($conn->query($sql)) {
        $msg = "✅ Registration successful! <a href='login.php'>Login here</a>";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
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
  <h3 class="text-center mb-4">Create Account</h3>
  <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
  <form method="POST">
    <div class="mb-3">
      <input type="text" name="name" class="form-control" placeholder="Full Name" required>
    </div>
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Email Address" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button name="register" class="btn btn-primary w-100">Register</button>
    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
  </form>
</div>
</body>
</html>
