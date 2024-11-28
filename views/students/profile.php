<?php
session_start();

// Check if user is logged in as a student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student' || !isset($_SESSION['student_id'])) {
    header("Location: /lms_system/Auth/login.php");
    exit();
}

// Include database connection
include_once dirname(__DIR__) . "/../config/database.php";

// Get the logged-in student's details using student_id
$student_id = $_SESSION['student_id'];
$stmt = $connect->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header("Location: /lms_system/Auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <?php include_once dirname(__DIR__) . "/../includes/navbar.php"; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">My Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <p class="border-bottom pb-2"><?php echo htmlspecialchars($student['name']); ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Username</label>
                                <p class="border-bottom pb-2"><?php echo htmlspecialchars($student['UserName']); ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <p class="border-bottom pb-2"><?php echo htmlspecialchars($student['email']); ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <p class="border-bottom pb-2"><?php echo htmlspecialchars($student['phone']); ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Age</label>
                                <p class="border-bottom pb-2"><?php echo htmlspecialchars($student['age']); ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Gender</label>
                                <p class="border-bottom pb-2">
                                    <?php 
                                        $genders = [
                                            'M' => 'Male',
                                            'F' => 'Female',
                                            'O' => 'Other'
                                        ];
                                        echo htmlspecialchars($genders[$student['sex']] ?? $student['sex']); 
                                    ?>
                                </p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="bi bi-pencil-square me-2"></i>Edit Profile
                            </button>
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
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="update_profile.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" 
                                value="<?php echo htmlspecialchars($student['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" 
                                value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" 
                                value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" name="age" 
                                value="<?php echo htmlspecialchars($student['age']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="sex" required>
                                <option value="M" <?php echo $student['sex'] === 'M' ? 'selected' : ''; ?>>Male</option>
                                <option value="F" <?php echo $student['sex'] === 'F' ? 'selected' : ''; ?>>Female</option>
                                <option value="O" <?php echo $student['sex'] === 'O' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" name="new_password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include_once dirname(__DIR__) . "/../includes/footer.php"; ?>
</body>