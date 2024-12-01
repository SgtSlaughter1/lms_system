<?php
session_start();

// Check if user is logged in as student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../controllers/TransactionController.php";

// Check if book_id and return_date are provided
if (!isset($_POST['book_id']) || !isset($_POST['expected_return_date'])) {
    $_SESSION['error'] = "Invalid request";
    header("Location: /lms_system/views/books.php");
    exit();
}

$bookId = (int)$_POST['book_id'];
$studentId = $_SESSION['student_id'];
$returnDate = $_POST['expected_return_date']; // Updated field name

// Validate return date
$today = new DateTime();
$returnDateTime = new DateTime($returnDate);
$diff = $today->diff($returnDateTime);

if ($diff->days > 30 || $diff->invert === 1) {
    $_SESSION['error'] = "Return date must be within 30 days from today";
    header("Location: /lms_system/views/books.php");
    exit();
}

$transactionController = new TransactionController($connect);
$result = $transactionController->handleBorrowBook($studentId, $bookId, $returnDate);

if ($result['status']) {
    $_SESSION['success'] = $result['message'];
} else {
    $_SESSION['error'] = $result['message'];
}

header("Location: /lms_system/views/books.php");
exit();
