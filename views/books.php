<?php
session_start();

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/controllers/BookController.php';
include dirname(__DIR__) . '/includes/navbar.php';

// if (!isset($_SESSION['user_type'])) {
//     header("location: /lms_system/Auth/login.php");
//     exit();
// }

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
                                                        <button type="button" class="btn btn-primary btn-sm" 
                                                                onclick="showBorrowModal(<?php echo $book['id']; ?>, '<?php echo htmlspecialchars($book['title']); ?>')">
                                                            <i class="bi bi-plus-circle"></i> Borrow
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-sm" disabled>
                                                            <i class="bi bi-x-circle"></i> Not Available
                                                        </button>
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

    <!-- Borrow Modal -->
    <div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrowModalLabel">Borrow Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="students/borrow_book.php" method="POST" id="borrowForm">
                    <div class="modal-body">
                        <input type="hidden" name="book_id" id="modalBookId">
                        <p>You are borrowing: <strong id="modalBookTitle"></strong></p>
                        
                        <div class="mb-3">
                            <label for="expected_return_date" class="form-label">Expected Return Date</label>
                            <input type="date" class="form-control" id="expected_return_date" name="expected_return_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Borrow</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="mt-3" id="successMessage"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showBorrowModal(bookId, bookTitle) {
        // Set the book details in the modal
        document.getElementById('modalBookId').value = bookId;
        document.getElementById('modalBookTitle').textContent = bookTitle;
        
        // Set date constraints
        const today = new Date();
        const maxDate = new Date();
        maxDate.setDate(today.getDate() + 30);
        
        const returnDateInput = document.getElementById('expected_return_date');
        returnDateInput.min = today.toISOString().split('T')[0];
        returnDateInput.max = maxDate.toISOString().split('T')[0];
        
        // Show the modal
        new bootstrap.Modal(document.getElementById('borrowModal')).show();
    }

    // Show success modal if there's a success message
    <?php if (isset($_SESSION['success'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('successMessage').textContent = <?php echo json_encode($_SESSION['success']); ?>;
            new bootstrap.Modal(document.getElementById('successModal')).show();
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    </script>
</body>

</html>