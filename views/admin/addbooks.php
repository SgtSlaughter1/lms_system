<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

include dirname(__DIR__) . "/../config/database.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $image_url = trim($_POST['image_url']);
    $copies = (int)$_POST['copies'];
    
    $query = "INSERT INTO books (title, author, isbn, available_copies, image_url) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("sssis", $title, $author, $isbn, $copies, $image_url);
    
    if ($stmt->execute()) {
        $success = "Book added successfully!";
    } else {
        $error = "Error adding book. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <?php include dirname(__DIR__) . "/../includes/navbar.php"; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Add New Book</h2>
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
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>

                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                    </div>

                    <div class="mb-3">
                        <label for="copies" class="form-label">Number of Copies</label>
                        <input type="number" class="form-control" id="copies" name="copies" 
                               min="1" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="image_url" name="image_url" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>