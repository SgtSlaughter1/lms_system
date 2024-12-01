<?php

class Transaction {
    private $connect;
    
    public function __construct($connect) {
        $this->connect = $connect;
    }
    
    // For admin view - get all transactions with student details
    public function getAdminTransactions() {
        try {
            $sql = "SELECT 
                    t.*,
                    s.name as student_name,
                    s.UserName as student_username,
                    b.title as book_title,
                    b.author as book_author
                    FROM transactions t
                    JOIN students s ON t.student_id = s.id
                    JOIN books b ON t.book_id = b.id
                    ORDER BY t.borrow_date DESC";
            
            $stmt = $this->connect->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting admin transactions: " . $e->getMessage());
            return [];
        }
    }
    
    // For student view - get specific student's transactions
    public function getStudentTransactions($studentId) {
        try {
            $sql = "SELECT 
                    t.id,
                    t.student_id,
                    t.book_id,
                    t.borrow_date,
                    t.expected_return_date,
                    t.return_date,
                    t.status,
                    t.updated_at,
                    b.title as title,
                    b.author as author,
                    b.isbn,
                    CASE 
                        WHEN t.status = 'returned' THEN t.return_date
                        ELSE t.expected_return_date
                    END as display_return_date
                    FROM transactions t
                    JOIN books b ON t.book_id = b.id
                    WHERE t.student_id = ? 
                    ORDER BY t.borrow_date DESC";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting student transactions: " . $e->getMessage());
            return [];
        }
    }
    
    public function canBorrowBook($studentId, $bookId) {
        try {
            // Check if student already has this book borrowed
            $sql = "SELECT COUNT(*) as count 
                    FROM transactions 
                    WHERE student_id = ? 
                    AND book_id = ? 
                    AND status = 'borrowed'";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("ii", $studentId, $bookId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result['count'] > 0) {
                return ['status' => false, 'message' => 'You have already borrowed this book'];
            }
            
            // Check total books borrowed by student
            $sql = "SELECT COUNT(*) as count 
                    FROM transactions 
                    WHERE student_id = ? 
                    AND status = 'borrowed'";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result['count'] >= 3) {
                return ['status' => false, 'message' => 'You cannot borrow more than 3 books at a time'];
            }
            
            return ['status' => true];
        } catch (Exception $e) {
            error_log("Error checking borrow eligibility: " . $e->getMessage());
            return ['status' => false, 'message' => 'An error occurred'];
        }
    }
    
    public function borrowBook($studentId, $bookId, $returnDate) {
        try {
            $this->connect->begin_transaction();
            
            // Check if can borrow
            $canBorrow = $this->canBorrowBook($studentId, $bookId);
            if (!$canBorrow['status']) {
                return $canBorrow;
            }
            
            // Insert borrow record with expected return date
            $sql = "INSERT INTO transactions 
                    (student_id, book_id, status, expected_return_date) 
                    VALUES (?, ?, 'borrowed', ?)";
            
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("iis", $studentId, $bookId, $returnDate);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create transaction");
            }
            
            // Update book availability
            $sql = "UPDATE books 
                    SET available_copies = available_copies - 1 
                    WHERE id = ? AND available_copies > 0";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $bookId);
            
            if (!$stmt->execute() || $stmt->affected_rows === 0) {
                throw new Exception("Book not available");
            }
            
            $this->connect->commit();
            
            $displayDate = date('M d, Y', strtotime($returnDate));
            return ['status' => true, 'message' => "Book borrowed successfully. Please return by {$displayDate}"];
            
        } catch (Exception $e) {
            $this->connect->rollback();
            error_log("Error borrowing book: " . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function returnBook($studentId, $bookId) {
        try {
            // Start transaction
            $this->connect->begin_transaction();
            
            // Check if book is actually borrowed by this student
            $sql = "SELECT id FROM transactions 
                    WHERE student_id = ? 
                    AND book_id = ? 
                    AND status = 'borrowed'";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("ii", $studentId, $bookId);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows === 0) {
                return ['status' => false, 'message' => 'No active borrow record found'];
            }
            
            // Update transaction status
            $sql = "UPDATE transactions 
                    SET status = 'returned', return_date = CURRENT_TIMESTAMP 
                    WHERE student_id = ? 
                    AND book_id = ? 
                    AND status = 'borrowed'";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("ii", $studentId, $bookId);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update transaction");
            }
            
            // Update book availability
            $sql = "UPDATE books 
                    SET available_copies = available_copies + 1 
                    WHERE id = ?";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $bookId);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update book availability");
            }
            
            // Commit transaction
            $this->connect->commit();
            return ['status' => true, 'message' => 'Book returned successfully'];
            
        } catch (Exception $e) {
            // Rollback on error
            $this->connect->rollback();
            error_log("Error returning book: " . $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getStudentBorrowedBooks($studentId) {
        try {
            $sql = "SELECT t.*, b.title, b.author, b.isbn 
                    FROM transactions t
                    JOIN books b ON t.book_id = b.id
                    WHERE t.student_id = ? 
                    AND t.status = 'borrowed'
                    ORDER BY t.borrow_date DESC";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting borrowed books: " . $e->getMessage());
            return [];
        }
    }
    
    public function getStudentStats($studentId) {
        try {
            // Get total borrowed books
            $sql = "SELECT 
                    COUNT(*) as total_borrowed,
                    SUM(CASE 
                        WHEN expected_return_date <= DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY) 
                        AND expected_return_date >= CURRENT_DATE 
                        THEN 1 ELSE 0 END) as due_soon,
                    SUM(CASE 
                        WHEN expected_return_date < CURRENT_DATE 
                        THEN 1 ELSE 0 END) as overdue
                    FROM transactions 
                    WHERE student_id = ? 
                    AND status = 'borrowed'";
                    
            $stmt = $this->connect->prepare($sql);
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting student stats: " . $e->getMessage());
            return [
                'total_borrowed' => 0,
                'due_soon' => 0,
                'overdue' => 0
            ];
        }
    }
} 