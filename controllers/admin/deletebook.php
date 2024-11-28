<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

include_once dirname(__DIR__) . "/../config/database.php";

// Check if book ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /lms_system/views/admin/adminbooks.php");
    exit();
}

$book_id = (int)$_GET['id'];

// Delete the book
$query = "DELETE FROM books WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $book_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Book deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting book. Please try again.";
}

// Redirect back to books page
header("Location: /lms_system/views/admin/adminbooks.php");
exit();
