<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

include dirname(__DIR__) . "/../config/database.php";

// Get book ID from URL
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch book details
$query = "SELECT * FROM books WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    header("Location: adminbooks.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $copies = (int)$_POST['copies'];
    
    $query = "UPDATE books SET title = ?, author = ?, isbn = ?, available_copies = ? WHERE id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("sssii", $title, $author, $isbn, $copies, $book_id);
    
    if ($stmt->execute()) {
        $success = "Book updated successfully!";
        // Refresh book data
        $result = $connect->query("SELECT * FROM books WHERE id = $book_id");
        $book = $result->fetch_assoc();
    } else {
        $error = "Error updating book. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <?php include dirname(__DIR__) . "/../includes/navbar.php"; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Book</h2>
            <a href="adminbooks.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Back to Books
            </a>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Book Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo htmlspecialchars($book['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" 
                               value="<?php echo htmlspecialchars($book['author']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" 
                               value="<?php echo htmlspecialchars($book['isbn']); ?>"
                               pattern="[0-9-]{10,17}" 
                               title="Please enter a valid ISBN (10 or 13 digits)"
                               required>
                        <small class="text-muted">Enter ISBN-10 or ISBN-13 format</small>
                    </div>

                    <div class="mb-3">
                        <label for="copies" class="form-label">Number of Copies</label>
                        <input type="number" class="form-control" id="copies" name="copies" 
                               value="<?php echo htmlspecialchars($book['available_copies']); ?>"
                               min="1" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>