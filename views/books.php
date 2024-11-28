<?php
session_start();

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/controllers/BookController.php';
include dirname(__DIR__) . '/includes/navbar.php';

if (!isset($_SESSION['user_type'])) {
    header("location: /lms_system/Auth/login.php");
    exit();
}

$bookController = new BookController($connect);
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
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

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: none;
        }

        .book-image-container {
            text-align: center;
        }

        .modal-dialog {
            max-width: 500px;
        }

        .btn-close {
            z-index: 1;
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
                                    <?php if ($book['available_copies'] > 0): ?>
                                        <span class="text-success">Available</span>
                                    <?php else: ?>
                                        <span class="text-danger">Not Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#bookModal<?= $book['id'] ?>">
                                        View Details
                                    </button>

                                    <!-- Book Details Modal -->
                                    <div class="modal fade" id="bookModal<?= $book['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Borrow Book</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <strong>Title:</strong> <?= htmlspecialchars($book['title']) ?>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Author:</strong> <?= htmlspecialchars($book['author']) ?>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?>
                                                    </div>
                                                    <?php if ($book['available_copies'] > 0): ?>
                                                        <a href="/books/borrow/<?= $book['id'] ?>"
                                                            class="btn btn-primary"
                                                            onclick="return confirm('Are you sure you want to borrow this book?')">
                                                            Borrow Book
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-secondary" disabled>Currently Unavailable</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>