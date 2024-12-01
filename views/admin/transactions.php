<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../controllers/TransactionController.php";

$transactionController = new TransactionController($connect);
$transactions = $transactionController->getAdminTransactions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Transactions - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <?php include dirname(__DIR__) . "/../includes/navbar.php"; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-clock-history"></i> Transaction History</h2>
            <a href="admin.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="bi bi-person"></i> Student</th>
                                <th><i class="bi bi-book"></i> Book</th>
                                <th><i class="bi bi-calendar-check"></i> Borrow Date</th>
                                <th><i class="bi bi-calendar-x"></i> Return Date</th>
                                <th><i class="bi bi-info-circle"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($transaction['student_name']); ?>
                                            <small class="text-muted d-block">
                                                @<?php echo htmlspecialchars($transaction['student_username']); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($transaction['book_title']); ?>
                                            <small class="text-muted d-block">
                                                by <?php echo htmlspecialchars($transaction['book_author']); ?>
                                            </small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($transaction['borrow_date'])); ?></td>
                                        <td>
                                            <?php echo $transaction['return_date'] 
                                                ? date('M d, Y', strtotime($transaction['return_date']))
                                                : '<span class="text-warning">Not returned</span>'; ?>
                                        </td>
                                        <td>
                                            <?php if ($transaction['status'] === 'borrowed'): ?>
                                                <span class="badge bg-warning">Borrowed</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Returned</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No transactions found</td>
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