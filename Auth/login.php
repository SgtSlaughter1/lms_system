<?php
include dirname(__DIR__) . "/config/database.php";

session_start();

if (isset($_POST['log'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check in students table first
    $sql = "SELECT * FROM students WHERE username = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Check password
        if ($password === $row['password']) {
            // Set student session variables
            $_SESSION['username'] = $row['UserName'];
            $_SESSION['role'] = 'student';
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['student'] = array(
                'name' => $row['name'],
                'email' => $row['email']
            );

            
            // Redirect to student dashboard
            header("location: /lms_system/views/students/students.php");
            exit();
        }
    }

    // Check in admins table if not found in students
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Check password
        if ($password === $row['password']) {
            // Set admin session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = 'admin';
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            
            // Correct path to admin dashboard
            header("location: /lms_system/views/admin/admin.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body class="bg-light">

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Login</h3>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST" id="loginForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username"
                        value="<?php if (isset($_POST['username'])) echo htmlspecialchars($_POST['username']); ?>"
                        class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password"
                        class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="log" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3 d-flex justify-content-center gap-2 align-items-center">
                <p class="mb-0">Don't have an account?</p>
                <a href="/lms_system/Auth/signup.php" class="btn btn-outline-primary">Sign Up</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    </script>
</body>

</html>