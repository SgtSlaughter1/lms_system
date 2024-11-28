<?php
session_start();
require_once dirname(__DIR__) . '/../config/database.php';
require_once dirname(__DIR__) . '/../controllers/StudentController.php';
include dirname(__DIR__) . '/../includes/navbar.php';

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("location: /lms_system/Auth/login.php");
    exit();
}

$studentController = new StudentController($connect);
$student = $studentController->getStudentProfile($_SESSION['student_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .lead {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .profile-card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include dirname(__DIR__) . '/includes/navbar.php'; ?>

    <div class="container mt-4">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card profile-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-circle"></i> Student Profile
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-person-badge"></i> Student ID
                                </h6>
                                <p class="lead"><?php echo htmlspecialchars($student['id']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-person"></i> Full Name
                                </h6>
                                <p class="lead"><?php echo htmlspecialchars($student['name']); ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-envelope"></i> Email Address
                                </h6>
                                <p class="lead"><?php echo htmlspecialchars($student['email']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-telephone"></i> Phone Number
                                </h6>
                                <p class="lead"><?php echo htmlspecialchars($student['phone'] ?? 'Not provided'); ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-calendar-check"></i> Member Since
                                </h6>
                                <p class="lead">
                                    <?php echo date('F j, Y', strtotime($student['created_at'])); ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">
                                    <i class="bi bi-book"></i> Total Books Borrowed
                                </h6>
                                <p class="lead">0</p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="bi bi-pencil-square"></i> Edit Profile
                            </button>
                            <a href="students.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-circle"></i>
                        Edit Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="update_profile.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i> Full Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?php echo htmlspecialchars($student['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="bi bi-telephone"></i> Phone Number
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="bi bi-key"></i> New Password
                            </label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Leave blank to keep current password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="bi bi-key-fill"></i> Confirm New Password
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="Confirm new password">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include dirname(__DIR__) . '/includes/footer.php'; ?>
</body>