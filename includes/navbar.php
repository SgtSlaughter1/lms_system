<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$user_type = '';
if (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];
}

// Initialize user data
$user_name = 'User';
$user_email = '';

// Get user data from session based on type
if ($user_type == 'admin') {
    $user_name = $_SESSION['admin_name'] ?? 'Administrator';
    $user_email = $_SESSION['admin_email'] ?? '';
} else {
    $user_name = $_SESSION['student_name'] ?? 'Student';
    $user_email = $_SESSION['student_email'] ?? '';
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/lms_system/index.php">
            <div class="d-flex align-items-center">
                <i class="bi bi-book-half text-primary fs-2 me-2"></i>
                <div>
                    <span class="fs-4">LIbrary Management System</span>
                </div>
            </div>
        </a>

        <div class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <?php echo htmlspecialchars($user_name); ?>
                </a>
                <?php if ($user_type == 'admin') { ?>
                    <!-- Admin Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($user_name); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($user_email); ?></small>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-shield-check me-1"></i>Administrator
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="/lms_system/views/admin/admin.php">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/lms_system/views/admin/adminbooks.php">
                                <i class="bi bi-book me-2"></i>Books
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/lms_system/views/students/students.php">
                                <i class="bi bi-people me-2"></i>Students
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="/lms_system/Auth/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                <?php } else { ?>
                    <!-- Student Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($user_name); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($user_email); ?></small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="/lms_system/views/students/profile.php">
                                <i class="bi bi-person me-2"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/lms_system/views/books.php">
                                <i class="bi bi-book me-2"></i>Books
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="/lms_system/Auth/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                <?php } ?>
            </li>
        </div>
    </div>
</nav>