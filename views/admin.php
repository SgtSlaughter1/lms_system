<?php
session_start();
include dirname(__DIR__) . "/config/database.php";

// Security check
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: /lms_system/Auth/login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/lms_system/assets/css/style.css">
</head>
<body>
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include dirname(__DIR__) . "/includes/navbar.php"; ?>
            
            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Library Management Dashboard</h1>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <!-- Total Books Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Books</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $query = "SELECT COUNT(*) as count FROM books";
                                            $result = mysqli_query($connect, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['count'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Books Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Available Books</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $query = "SELECT SUM(available_copies) as available FROM books";
                                            $result = mysqli_query($connect, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['available'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registered Students Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Registered Students</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $query = "SELECT COUNT(*) as count FROM students";
                                            $result = mysqli_query($connect, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['count'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Borrowings Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Active Borrowings</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $query = "SELECT COUNT(*) as count FROM transactions WHERE status='borrowed'";
                                            $result = mysqli_query($connect, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['count'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Library Management</h6>
                            </div>
                            <div class="card-body">
                                <a href="books.php" class="btn btn-primary mr-2">Manage Books</a>
                                <a href="students.php" class="btn btn-success mr-2">Manage Students</a>
                                <a href="transactions.php" class="btn btn-info mr-2">Book Transactions</a>
                                <a href="reports.php" class="btn btn-warning">Generate Reports</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Book Title</th>
                                                <th>Borrow Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT t.*, s.name as student_name, b.title as book_title 
                                                     FROM transactions t 
                                                     JOIN students s ON t.student_id = s.id 
                                                     JOIN books b ON t.book_id = b.id 
                                                     ORDER BY t.created_at DESC LIMIT 5";
                                            $result = mysqli_query($connect, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                echo "<td>{$row['student_name']}</td>";
                                                echo "<td>{$row['book_title']}</td>";
                                                echo "<td>{$row['borrow_date']}</td>";
                                                echo "<td>{$row['return_date']}</td>";
                                                echo "<td>{$row['status']}</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/lms_system/assets/js/script.js"></script>
</body>
</html>

<?php include dirname(__DIR__) . "/includes/footer.php"; ?>
