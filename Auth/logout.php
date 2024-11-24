<?php
// After session destruction and logout logic
$redirect = $_GET['redirect'] ?? '/lms_system/Auth/login.php';
header("Location: " . $redirect);
exit();
