<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Website Logo/Name -->
        <a class="navbar-brand d-flex align-items-center" href="/lms_system/index.php">
            <div class="d-flex align-items-center">
                <i class="bi bi-book-half text-primary fs-2 me-2"></i>
                <div>
                    <span class="fs-4">LIbrary Management System</span>
                </div>
            </div>
        </a>

        <!-- User Profile Dropdown -->
        <div class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <!-- Profile Toggle Button -->
                <a class="nav-link dropdown-toggle d-flex align-items-center"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <?php
                    $student_name = $student['name'] ?? 'Student';
                    echo htmlspecialchars($student_name);
                    ?>
                </a>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end">
                    <!-- User Info Section -->
                    <li>
                        <div class="dropdown-item-text">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle fs-4 me-2"></i>
                                <div>
                                    <?php
                                    $student_name = $student['name'] ?? 'Student';
                                    $student_email = $student['email'] ?? '';
                                    ?>
                                    <div class="fw-bold"><?php echo htmlspecialchars($student_name); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($student_email); ?></small>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <!-- Menu Items -->
                    <li>
                        <a class="dropdown-item" href="/lms_system/views/profile.php">
                            <i class="bi bi-person me-2"></i>My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <!-- Logout Button -->
                    <li>
                        <a class="dropdown-item text-danger" href="/lms_system/Auth/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </li>
        </div>
    </div>
</nav>