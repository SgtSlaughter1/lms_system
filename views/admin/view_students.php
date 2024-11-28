<?php
include dirname(__DIR__) . "/../config/database.php";

// Fetch all students from the database
$query = "SELECT * FROM students";
$result = mysqli_query($connect, $query);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<?php include dirname(__DIR__) . "/../includes/navbar.php"; ?>

<div class="container mt-4">
    <h2>Manage Students</h2>
    
    <div class="mb-3">
        <a href="admin.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back to Admin
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Books Borrowed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['UserName']; ?></td>
                            <td><?php echo $row['sex']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['total_books_borrowed']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/../includes/footer.php'; ?>

