<?php
session_start();
require_once dirname(__DIR__) . "/config/database.php";
require_once dirname(__DIR__) . "/controllers/StudentController.php";

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("location: /lms_system/Auth/login.php");
    exit();
}

$studentController = new StudentController($connect);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $result = $studentController->updateProfile($_SESSION['student_id'], $_POST);
    $_SESSION['success_message'] = $result['message'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get student information
$student = $studentController->getStudentProfile($_SESSION['student_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include dirname(__DIR__) . '/includes/navbar.php'; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <!-- Success Modal -->
        <div class="modal fade show" id="successModal" tabindex="-1" style="display: block;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="btn-close btn-close-white" onclick="closeSuccessModal()"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="mt-3"><?php echo htmlspecialchars($_SESSION['success_message']); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="closeSuccessModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>

        <script>
            function closeSuccessModal() {
                document.getElementById('successModal').style.display = 'none';
                document.querySelector('.modal-backdrop').remove();
            }
        </script>

        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>


    <div class="container mt-4">
        <div class="row">
            <!-- Quick Stats -->
            <div class="col-md-12 mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-book text-primary"></i>
                                    Books Borrowed
                                </h5>
                                <p class="card-text display-6">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar-event text-warning"></i>
                                    Books Due
                                </h5>
                                <p class="card-text display-6">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-lightning-charge"></i>
                                    Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="#" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Search Books
                                    </a>
                                    <a href="#" class="btn btn-info">
                                        <i class="bi bi-clock-history"></i> View History
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowed Books and Notifications Row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                <i class="bi bi-book-half"></i>
                                Currently Borrowed Books
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-book"></i> Book Title</th>
                                        <th><i class="bi bi-calendar-check"></i> Borrowed Date</th>
                                        <th><i class="bi bi-calendar-due"></i> Due Date</th>
                                        <th><i class="bi bi-info-circle"></i> Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <i class="bi bi-inbox text-muted" style="font-size: 1.5rem;"></i><br>
                                            No books currently borrowed
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                <i class="bi bi-bell"></i>
                                Notifications
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                No new notifications
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <!-- <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-circle"></i>
                        Edit Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">
                                <i class="bi bi-person-badge"></i>
                                Student ID
                            </label>
                            <input type="text" class="form-control" id="student_id"
                                value="<?php echo htmlspecialchars($student['id']); ?>" readonly>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Student ID cannot be changed
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i>
                                Full Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?php echo htmlspecialchars($student['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i>
                                Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="bi bi-telephone"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include dirname(__DIR__) . '/includes/footer.php'; ?>
</body>

</html>