<?php
session_start();

// সেশন ডিস্ট্রয় (logout)
session_unset();
session_destroy();

// ইউজারকে লগইন পেজে রিডিরেক্ট করুন
header("Location: index.php");
exit;
?>
