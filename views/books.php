<?php
session_start();

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/controllers/BookController.php';
include dirname(__DIR__) . '/includes/navbar.php';


// Check if user is logged in
if (!isset($_SESSION['user_type'])) {
    header("location: /lms_system/Auth/login.php");
    exit();
}

// Debugging: Check if user is admin
// if (isset($_SESSION['user_type'])) {
//     echo "<!-- User type: " . htmlspecialchars($_SESSION['user_type']) . " -->";
// } else {
//     echo "<!-- User type not set -->";
// }

// Create controller instance
$bookController = new BookController($connect);

// Get search term if exists
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get books (either all or searched)
$books = $bookController->searchBooks($searchTerm);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Books</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <style>
        .table-container {
            background-color: white;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Library Books</h2>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="" method="GET">
                    <div class="input-group">
                        <input type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search books..."
                            value="<?= htmlspecialchars($searchTerm) ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <?php if (!empty($searchTerm)): ?>
                            <a href="books.php" class="btn btn-secondary">Clear</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admin Controls -->
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
            <div class="mb-3">
                <a href="/books/create" class="btn btn-primary">Add New Book</a>
            </div>
        <?php endif; ?>

        <!-- Books Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Available Copies</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><?= htmlspecialchars($book['isbn']) ?></td>
                                <td>
                                    <span class=" align-center <?= $book['available_copies'] > 0 ? 'text-success' : 'text-danger' ?>">
                                        <?= htmlspecialchars($book['available_copies']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                                        <!-- Admin Actions -->
                                        <a href="/books/edit/<?= $book['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="/books/delete/<?= $book['id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                                    <?php else: ?>
                                        <!-- Student Actions -->
                                        <?php if ($book['available_copies'] > 0): ?>
                                            <a href="/books/borrow/<?= $book['id'] ?>" class="btn btn-sm btn-primary">Borrow</a>
                                        <?php else: ?>
                                            <span class="text-danger">Not Available</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <?php if (!empty($searchTerm)): ?>
                                    No books found matching your search.
                                <?php else: ?>
                                    No books available.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include dirname(__DIR__) . '/includes/footer.php'; ?>
</body>

</html>