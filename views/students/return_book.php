<?php
session_start();

// Check if user is logged in as student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../controllers/TransactionController.php";

// Check if book_id is provided
if (!isset($_POST['book_id'])) {
    $_SESSION['error'] = "Invalid request";
    header("Location: borrowed_books.php");
    exit();
}

$bookId = (int)$_POST['book_id'];
$studentId = $_SESSION['student_id'];

$transactionController = new TransactionController($connect);
$result = $transactionController->handleReturnBook($studentId, $bookId);

if ($result['status']) {
    $_SESSION['success'] = $result['message'];
} else {
    $_SESSION['error'] = $result['message'];
}

header("Location: borrowed_books.php");
exit();
?> 