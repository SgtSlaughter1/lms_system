<?php
session_start();
require_once dirname(__DIR__) . "/config/database.php";
require_once dirname(__DIR__) . "/controllers/StudentController.php";

if (!isset($_SESSION['student_id'])) {
    header("location: /lms_system/Auth/login.php");
    exit();
}

$studentController = new StudentController($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['student_id'];
    
    $result = $studentController->handleProfileUpdate($student_id, $_POST);
    
    if ($result['status'] === 'success') {
        $_SESSION['success_message'] = $result['message'];
    } else {
        $_SESSION['error_message'] = $result['message'];
    }

    // Redirect back to profile page
    header("Location: profile.php");
    exit();
}

// If not POST request, redirect to profile
header("Location: profile.php");
exit();
