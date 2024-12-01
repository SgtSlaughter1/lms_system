<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$user_type = '';
$user_name = '';
$user_email = '';

// Set user information based on role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        $user_type = 'admin';
        // Get admin details from database
        include_once dirname(__DIR__) . "/config/database.php";
        $stmt = $connect->prepare("SELECT name, email FROM admins WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $user_name = $row['name'];
            $user_email = $row['email'];
        }
    } else if ($_SESSION['role'] === 'student') {
        $user_type = 'student';
        // Get student details from database
        include_once dirname(__DIR__) . "/config/database.php";
        $stmt = $connect->prepare("SELECT name, email FROM students WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['student_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $user_name = $row['name'];
            $user_email = $row['email'];
        }
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Brand -->
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a class="navbar-brand" href="/lms_system/views/admin/admin.php">Library Management System</a>
            <?php elseif ($_SESSION['role'] === 'student'): ?>
                <a class="navbar-brand" href="/lms_system/views/students/students.php">Library Management System</a>
            <?php endif; ?>
        <?php else: ?>
            <a class="navbar-brand" href="/lms_system/index.php">Library Management System</a>
        <?php endif; ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <?php echo htmlspecialchars($user_name ?: 'Guest'); ?>
                </a>
                <?php if ($user_type === 'admin'): ?>
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
                            <a class="dropdown-item" href="/lms_system/views/admin/view_students.php">
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
                <?php elseif ($user_type === 'student'): ?>
                    <!-- Student Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($user_name); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($user_email); ?></small>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-mortarboard me-1"></i>Student
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="/lms_system/views/students/students.php">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
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
                        <li>
                            <a class="dropdown-item" href="/lms_system/views/students/borrowed_books.php">
                                <i class="bi bi-book-half me-2"></i>My Borrowed Books
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="/lms_system/Auth/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                <?php else: ?>
                    <!-- Guest/Not Logged In -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/lms_system/Auth/login.php">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/lms_system/Auth/signup.php">
                                <i class="bi bi-person-plus me-2"></i>Sign Up
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </li>
        </div>
    </div>
</nav>