<?php
session_start();
require_once dirname(__DIR__) . '/../config/database.php';
require_once dirname(__DIR__) . '/../controllers/StudentController.php';
include dirname(__DIR__) . '/../includes/navbar.php';  // Fixed path

if (!isset($_SESSION['student_id'])) {
    header("location: /lms_system/Auth/login.php");
    exit();
}

$studentController = new StudentController($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['student_id'];
    
    $studentController->handleProfileUpdate();
    
    if (isset($_SESSION['success_message'])) {
        header("Location: profile.php");
        exit();
    } else {
        header("Location: profile.php");
        exit();
    }
}

// If not POST request, redirect to profile
header("Location: profile.php");
exit();
