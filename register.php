<?php
include "db.php";  // ডাটাবেজ কানেকশন ফাইল
session_start();

$msg = "";  // মেসেজটি স্টোর করার জন্য

// রেজিস্ট্রেশন ফর্ম সাবমিট হলে কোড শুরু হবে
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $referred_by = $_POST['referred_by'];  // Optional: রেফারার কোড

    // ✅ নিজের রেফারাল কোড জেনারেট
    $referral_code = strtoupper(substr(preg_replace("/[^A-Za-z]/", "", $name), 0, 4)) . rand(1000, 9999);

    // ✅ চেক করা হচ্ছে ইমেইল ডুপ্লিকেট কিনা
    $check_email = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check_email->num_rows > 0) {
        $msg = "❌ এই ইমেইলটি আগে থেকেই রেজিস্টার করা আছে!";
    } else {
        // ✅ রেজিস্ট্রেশন ডাটাবেজে ইনসার্ট করা
        $sql = "INSERT INTO users (name, email, password, referral_code, referred_by) 
                VALUES ('$name', '$email', '$password', '$referral_code', '$referred_by')";
        
        if ($conn->query($sql)) {
            
            // ✅ রেফারাল কোড দেওয়া হলে রেফারারের পয়েন্ট আপডেট করা
            if (!empty($referred_by)) {
                // রেফারাল কোড চেক করা হচ্ছে
                $check_ref = $conn->query("SELECT * FROM users WHERE referral_code='$referred_by'");
                if ($check_ref->num_rows > 0) {
                    // রেফারার পয়েন্ট ১০ বাড়ানো হচ্ছে
                    $conn->query("UPDATE users SET refer_points = refer_points + 10 WHERE referral_code = '$referred_by'");
                }
            }

            $msg = "✅ রেজিস্ট্রেশন সফল হয়েছে! <a href='index.php'>এখানে লগইন করুন</a>";
        } else {
            $msg = "❌ সমস্যা: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    
    <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?> <!-- Message display -->

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>
        <div class="mb-3">
            <input type="text" name="referred_by" class="form-control" placeholder="Referral Code (optional)">
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button name="register" class="btn btn-primary w-100">Register</button>
        <p class="mt-3 text-center">Already have an account? <a href="index.php">Login</a></p>
    </form>
</div>
</body>
</html>
