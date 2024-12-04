<?php
session_start();
require_once dirname(__DIR__) . '/../config/database.php';
require_once dirname(__DIR__) . '/../controllers/BookController.php';
include dirname(__DIR__) . '/../includes/navbar.php';

// Check if user is admin
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
//     header("location: /lms_system/Auth/login.php");
//     exit();
// }

// Create controller instance
$bookController = new BookController($connect);

// Get search term if exists
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get books (either all or searched)
$books = $bookController->searchBooks($searchTerm);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Books</h2>

            <a href="admin.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Back to Admin
            </a>
        </div>
        <a href="/lms_system/views/admin/addbooks.php" class="btn btn-primary my-3">
                    <i class="bi bi-plus-circle"></i> Add New Book
                </a>
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-6"> 
                <form action="" method="GET" >
                    <div class="input-group" style="border:1px solid red;">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search books..."
                            value="<?php echo htmlspecialchars($searchTerm); ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                        
                        <?php if (!empty($searchTerm)): ?>
                            <a href="adminbooks.php" class="btn btn-secondary">Clear</a>
                        <?php endif; ?>
                    </div>

                </form>
                
            </div>
            
        </div>

        <!-- Books Table -->

        <div class="table-container">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Total Copies</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                                <td><?php echo htmlspecialchars($book['available_copies']); ?></td>

                                <td>
                                    <a href="/lms_system/views/admin/editbook.php?id=<?php echo $book['id']; ?>"
                                        class="btn btn-sm btn-warning me-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="/lms_system/views/admin/deletebook.php?id=<?php echo $book['id']; ?>"
                                        class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this book?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No books found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include dirname(__DIR__) . '/../includes/footer.php'; ?>

</body>

</html>