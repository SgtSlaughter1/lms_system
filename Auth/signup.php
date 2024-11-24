<?php
include dirname(__DIR__) . "/config/database.php";

// Initialize variables
$errors = [];
$success = false;

// Form validation and processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = trim($_POST["name"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $cpassword = $_POST["cpassword"] ?? '';
    $age = trim($_POST["age"] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $sex = trim($_POST["sex"] ?? '');
    $email = trim($_POST["email"] ?? '');

    // Validation rules using regex
    $nameRegex = "/^[a-zA-Z ]{2,50}$/";
    $usernameRegex = "/^[a-zA-Z0-9_]{5,20}$/";
    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $phoneRegex = "/^[0-9]{11}$/";
    $ageRegex = "/^[0-9]{1,2}$/";

    // Validate each field
    if (!preg_match($nameRegex, $name)) {
        $errors['name'] = "Name must be 2-50 characters long and contain only letters and spaces";
    }

    if (!preg_match($usernameRegex, $username)) {
        $errors['username'] = "Username must be 5-20 characters long and contain only letters, numbers, and underscores";
    }

    if (!preg_match($emailRegex, $email)) {
        $errors['email'] = "Please enter a valid email address";
    }

    if (!preg_match($phoneRegex, $phone)) {
        $errors['phone'] = "Phone number must be 11 digits";
    }

    if (!preg_match($ageRegex, $age) || $age < 16 || $age > 99) {
        $errors['age'] = "Age must be between 16 and 99";
    }

    if (!in_array($sex, ['M', 'F', 'O'])) {
        $errors['sex'] = "Please select a valid gender";
    }

    if (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long";
    }

    if ($password !== $cpassword) {
        $errors['cpassword'] = "Passwords do not match";
    }

    // If no errors, check if username exists and insert into database
    if (empty($errors)) {
        // Check if username exists using prepared statement
        $stmt = $connect->prepare("SELECT username FROM students WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors['username'] = "Username already exists";
        } else {
            // Insert new student using prepared statement
            $stmt = $connect->prepare("INSERT INTO students (username, password, name, age, sex, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
            // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sssssss", $username, $password, $name, $age, $sex, $phone, $email);

            if ($stmt->execute()) {
                $success = true;
                header("location: /lms_system/Auth/login.php");
                exit();
            } else {
                $errors['general'] = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Student Registration</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="alert alert-success">Registration successful!</div>
                        <?php endif; ?>

                        <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                        <?php endif; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                           id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                           id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                                    <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                           id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                           id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control <?php echo isset($errors['age']) ? 'is-invalid' : ''; ?>" 
                                           id="age" name="age" value="<?php echo htmlspecialchars($age ?? ''); ?>" required>
                                    <?php if (isset($errors['age'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['age']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="sex" class="form-label">Gender</label>
                                    <select class="form-select <?php echo isset($errors['sex']) ? 'is-invalid' : ''; ?>" 
                                            id="sex" name="sex" required>
                                        <option value="" selected disabled>Choose...</option>
                                        <option value="M" <?php echo ($sex ?? '') === 'M' ? 'selected' : ''; ?>>Male</option>
                                        <option value="F" <?php echo ($sex ?? '') === 'F' ? 'selected' : ''; ?>>Female</option>
                                        <option value="O" <?php echo ($sex ?? '') === 'O' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                    <?php if (isset($errors['sex'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['sex']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                           id="password" name="password" required>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="cpassword" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control <?php echo isset($errors['cpassword']) ? 'is-invalid' : ''; ?>" 
                                           id="cpassword" name="cpassword" required>
                                    <?php if (isset($errors['cpassword'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['cpassword']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                        <div class="text-center mt-3 d-flex justify-content-center gap-2 align-items-center">
                            <p class="mb-0">Already have an account?</p>
                            <a href="/lms_system/Auth/login.php" class="btn btn-outline-primary">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>