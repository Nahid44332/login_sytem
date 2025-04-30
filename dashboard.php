<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "db.php";
$id = $_SESSION['user_id'];
$res = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $res->fetch_assoc();

// ✅ Profile update handler
if (isset($_POST['update_profile'])) {
    $newName = $conn->real_escape_string($_POST['name']);
    $newEmail = $conn->real_escape_string($_POST['email']);
    $update = $conn->query("UPDATE users SET name='$newName', email='$newEmail' WHERE id=$id");
    if ($update) {
        $_SESSION['msg'] = "প্রোফাইল সফলভাবে আপডেট হয়েছে!";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['msg'] = "কিছু সমস্যা হয়েছে!";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      padding-top: 60px;
    }
    .sidebar a {
      color: #fff;
      display: block;
      padding: 15px 20px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .content {
      margin-left: 260px;
      padding: 20px;
    }
    .card {
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<!-- ✅ Sidebar -->
<div class="sidebar">
  <h4 class="text-center text-light">🌐 My Dashboard</h4>
  <a href="#" onclick="showSection('dashboard')">🏠 Dashboard</a>
  <a href="#" onclick="showSection('profile')">👤 Profile</a>
  <a href="#" onclick="showSection('settings')">⚙️ Settings</a>
  <a href="logout.php">🚪 Logout</a>
</div>

<!-- ✅ Main Content -->
<div class="content">
  <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-info"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
  <?php endif; ?>

  <!-- ✅ Dashboard Section (Welcome Page) -->
  <div id="dashboardSection">
    <div class="card bg-light shadow-lg border-0 p-4 text-center">
      <h2 class="text-primary mb-3">👋 স্বাগতম <?= $user['name'] ?>!</h2>
      <p class="lead">আপনার ড্যাশবোর্ডে স্বাগতম। এখানে আপনি আপনার প্রোফাইল দেখতে, সেটিংস পরিবর্তন করতে এবং আরও অনেক কিছু করতে পারবেন।</p>
      
      <div class="row mt-4">
        <div class="col-md-4">
          <div class="card bg-white border-0 shadow-sm p-3">
            <h5>👤 প্রোফাইল</h5>
            <p>আপনার প্রোফাইল তথ্য দেখুন</p>
            <button class="btn btn-outline-primary btn-sm" onclick="showSection('profile')">প্রোফাইল দেখুন</button>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-white border-0 shadow-sm p-3">
            <h5>⚙️ সেটিংস</h5>
            <p>আপনার তথ্য আপডেট করুন</p>
            <button class="btn btn-outline-secondary btn-sm" onclick="showSection('settings')">সেটিংস এ যান</button>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-white border-0 shadow-sm p-3">
            <h5>🚪 লগআউট</h5>
            <p>সিস্টেম থেকে বের হয়ে যান</p>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">লগআউট করুন</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ✅ Profile Section -->
  <div id="profileSection" style="display: none;">
    <div class="card p-4">
      <h4>👤 প্রোফাইল তথ্য</h4>
      <hr>
      <p><strong>নাম:</strong> <?= $user['name'] ?></p>
      <p><strong>ইমেইল:</strong> <?= $user['email'] ?></p>
      <p><strong>ইউজার আইডি:</strong> <?= $user['id'] ?></p>
      <p><strong>রেজিস্ট্রেশনের সময়:</strong> <?= $user['created_at'] ?? 'N/A' ?></p>
    </div>
  </div>

  <!-- ✅ Settings Section (Profile Edit) -->
  <div id="settingsSection" style="display: none;">
    <div class="card p-4">
      <h4>✏️ প্রোফাইল এডিট করুন</h4>
      <form method="POST" action="">
        <div class="mb-3">
          <label for="name" class="form-label">নাম</label>
          <input type="text" class="form-control" name="name" value="<?= $user['name'] ?>" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">ইমেইল</label>
          <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>" required>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">আপডেট করুন</button>
      </form>
    </div>
  </div>
</div>

<!-- ✅ Script for switching sections -->
<script>
  function showSection(id) {
    document.getElementById('dashboardSection').style.display = 'none';
    document.getElementById('profileSection').style.display = 'none';
    document.getElementById('settingsSection').style.display = 'none';

    document.getElementById(id + 'Section').style.display = 'block';
  }
</script>

</body>
</html>
