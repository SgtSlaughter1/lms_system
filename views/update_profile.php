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

    // Handle profile update
    $updateData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone']
    ];

    $studentController->updateProfile($student_id, $updateData);

    // Handle password update if new password is provided
    if (!empty($_POST['new_password'])) {
        $passwordData = [
            'new_password' => $_POST['new_password'],
            'confirm_password' => $_POST['confirm_password']
        ];

        $result = $studentController->updatePassword($student_id, $passwordData);

        if ($result['status'] === 'success') {
            $_SESSION['success_message'] = "Profile and password updated successfully!";
        } else {
            $_SESSION['error_message'] = $result['message'];
        }
    } else {
        $_SESSION['success_message'] = "Profile updated successfully!";
    }

    // Redirect back to profile page
    header("Location: profile.php");
    exit();
}

// If not POST request, redirect to profile
header("Location: profile.php");
exit();
