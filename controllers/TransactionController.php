<?php
require_once dirname(__DIR__) . '/models/Transaction.php';

class TransactionController {
    private $transactionModel;
    
    public function __construct($connect) {
        $this->transactionModel = new Transaction($connect);
    }
    
    // For admin view
    public function getAdminTransactions() {
        return $this->transactionModel->getAdminTransactions();
    }
    
    // For student view
    public function getStudentTransactions($studentId) {
        return $this->transactionModel->getStudentTransactions($studentId);
    }
    
    public function handleBorrowBook($studentId, $bookId, $returnDate) {
        try {
            // Validate inputs
            if (!$studentId || !$bookId) {
                return ['status' => false, 'message' => 'Invalid request'];
            }
            
            // Format the return date to MySQL timestamp format
            $formattedReturnDate = date('Y-m-d H:i:s', strtotime($returnDate));
            
            // Process borrow request
            $result = $this->transactionModel->borrowBook($studentId, $bookId, $formattedReturnDate);
            return $result;
            
        } catch (Exception $e) {
            error_log("Controller error handling borrow: " . $e->getMessage());
            return ['status' => false, 'message' => 'An error occurred'];
        }
    }
    
    public function handleReturnBook($studentId, $bookId) {
        try {
            return $this->transactionModel->returnBook($studentId, $bookId);
        } catch (Exception $e) {
            error_log("Controller error handling return: " . $e->getMessage());
            return ['status' => false, 'message' => 'An error occurred'];
        }
    }
    
    public function getBorrowedBooks($studentId) {
        return $this->transactionModel->getStudentBorrowedBooks($studentId);
    }
} 