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
$transactions = $transactionController->getStudentTransactions($_SESSION['student_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Transaction History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <?php include dirname(__DIR__) . "/../includes/navbar.php"; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-clock-history"></i> My Borrowing History</h2>
            <a href="../students/students.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="bi bi-book"></i> Book</th>
                                <th><i class="bi bi-calendar-check"></i> Borrowed On</th>
                                <th><i class="bi bi-calendar-x"></i> Returned On</th>
                                <th><i class="bi bi-info-circle"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($transaction['title']); ?>
                                            <small class="text-muted d-block">
                                                by <?php echo htmlspecialchars($transaction['author']); ?>
                                            </small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($transaction['borrow_date'])); ?></td>
                                        <td>
                                            <?php 
                                            if ($transaction['status'] === 'returned') {
                                                echo date('M d, Y', strtotime($transaction['return_date']));
                                            } else {
                                                $returnDate = new DateTime($transaction['expected_return_date']);
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
                                            <?php if ($transaction['status'] === 'returned'): ?>
                                                <span class="badge bg-success">Returned</span>
                                            <?php elseif ($isOverdue): ?>
                                                <span class="badge bg-danger">Overdue</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Borrowed</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No transaction history found.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 