<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

include_once dirname(__DIR__) . "/../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $age = trim($_POST['age']);
    $sex = $_POST['sex'];
    $new_password = trim($_POST['new_password']);

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($age) || empty($sex)) {
        $_SESSION['error'] = "All fields are required except password.";
        header("Location: profile.php");
        exit();
    }

    // Update query
    if (!empty($new_password)) {
        $sql = "UPDATE students SET name=?, email=?, phone=?, age=?, sex=?, password=? WHERE username=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sssssss", $name, $email, $phone, $age, $sex, $new_password, $_SESSION['username']);
    } else {
        $sql = "UPDATE students SET name=?, email=?, phone=?, age=?, sex=? WHERE username=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $phone, $age, $sex, $_SESSION['username']);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }

    header("Location: profile.php");
    exit();
}
