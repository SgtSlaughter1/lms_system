<?php
session_start();

// Check if user is logged in as student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../controllers/TransactionController.php";

$transactionController = new TransactionController($connect);
$borrowedBooks = $transactionController->getBorrowedBooks($_SESSION['student_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowed Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <?php include dirname(__DIR__) . "/../includes/navbar.php"; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-book"></i> My Borrowed Books</h2>
            <a href="../books.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Borrow More Books
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <?php if (!empty($borrowedBooks)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Author</th>
                                    <th>Borrow Date</th>
                                    <th>Expected Return</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($borrowedBooks as $book): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($book['borrow_date'])); ?></td>
                                        <td>
                                            <?php 
                                            if ($book['status'] === 'returned') {
                                                echo date('M d, Y', strtotime($book['return_date']));
                                            } else {
                                                $returnDate = new DateTime($book['expected_return_date']);
                                                $today = new DateTime();
                                                $isOverdue = $returnDate < $today;
                                            ?>
                                            <span class="<?php echo $isOverdue ? 'text-danger' : 'text-success'; ?>">
                                                <?php echo $returnDate->format('M d, Y'); ?>
                                                <?php if ($isOverdue): ?>
                                                    <span class="badge bg-danger">Overdue</span>
                                                <?php endif; ?>
                                            </span>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm"
                                                    onclick="showReturnModal(<?php echo $book['book_id']; ?>, '<?php echo htmlspecialchars($book['title']); ?>')">
                                                <i class="bi bi-arrow-return-left"></i> Return
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3">You haven't borrowed any books yet.</p>
                        <a href="../books.php" class="btn btn-primary">Browse Books</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Return Confirmation Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Return Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to return: <strong id="returnBookTitle"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <form action="return_book.php" method="POST">
                        <input type="hidden" name="book_id" id="returnBookId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Confirm Return</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showReturnModal(bookId, bookTitle) {
        document.getElementById('returnBookId').value = bookId;
        document.getElementById('returnBookTitle').textContent = bookTitle;
        new bootstrap.Modal(document.getElementById('returnModal')).show();
    }
    </script>
</body>
</html> 