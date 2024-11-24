<?php
require_once dirname(__DIR__) . '/models/Student.php';

class StudentController {
    private $studentModel;
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->studentModel = new Student($db);
    }
    
    public function getStudentProfile($student_id) {
        return $this->studentModel->getStudentById($student_id);
    }
    
    public function updateProfile($student_id, $data) {
        return $this->studentModel->updateProfile($student_id, $data);
    }

    public function handleProfileUpdate() {
        if (!isset($_SESSION['student_id'])) {
            header("location: /lms_system/Auth/login.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_id = $_SESSION['student_id'];
            
            // Handle profile update
            $updateData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone']
            ];
            
            $this->updateProfile($student_id, $updateData);

            // Handle password update if new password is provided
            if (!empty($_POST['new_password'])) {
                $passwordData = [
                    'new_password' => $_POST['new_password'],
                    'confirm_password' => $_POST['confirm_password']
                ];
                
                $result = $this->updatePassword($student_id, $passwordData);
                
                if ($result['status'] === 'success') {
                    $_SESSION['success_message'] = "Profile and password updated successfully!";
                } else {
                    $_SESSION['error_message'] = $result['message'];
                }
            } else {
                $_SESSION['success_message'] = "Profile updated successfully!";
            }
            
            // Redirect back to profile page
            header("Location: profile.php");
            exit();
        }
    }

    public function updatePassword($student_id, $data) {
        // Validate passwords
        if (empty($data['new_password'])) {
            return ['status' => 'error', 'message' => 'New password cannot be empty'];
        }

        if ($data['new_password'] !== $data['confirm_password']) {
            return ['status' => 'error', 'message' => 'Passwords do not match'];
        }

        // Store password directly (temporarily)
        if ($this->studentModel->updatePassword($student_id, $data['new_password'])) {
            return ['status' => 'success', 'message' => 'Password updated successfully!'];
        }
        return ['status' => 'error', 'message' => 'Error updating password'];
    }
}
