<?php
require_once dirname(__DIR__) . '/models/Student.php';

class StudentController {
    private $studentModel;
    
    public function __construct($connect) {
        $this->studentModel = new Student($connect);
    }
    
    public function getStudentProfile($student_id) {
        return $this->studentModel->getStudentById($student_id);
    }
    
    public function updateProfile($student_id, $data) {
        if(empty($data['name']) || empty($data['email'])) {
            return ['status' => 'error', 'message' => 'Name and email are required'];
        }
        return $this->studentModel->updateProfile($student_id, $data);
    }

    public function handleProfileUpdate() {
        if (!isset($_SESSION['student_id'])) {
            header("location: /lms_system/Auth/login.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_id = $_SESSION['student_id'];
            $updateData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone']
            ];
            
            $result = $this->updateProfile($student_id, $updateData);
            
            // Handle password update if provided
            if (!empty($_POST['new_password'])) {
                if ($_POST['new_password'] !== $_POST['confirm_password']) {
                    $_SESSION['error_message'] = "Passwords do not match";
                    header("Location: profile.php");
                    exit();
                }
                
                $passwordResult = $this->updatePassword($student_id, $_POST['new_password']);
                if ($passwordResult['status'] === 'success') {
                    $_SESSION['success_message'] = "Profile and password updated successfully!";
                } else {
                    $_SESSION['error_message'] = $passwordResult['message'];
                }
            } else {
                $_SESSION['success_message'] = $result['message'];
            }
            
            header("Location: profile.php");
            exit();
        }
    }

    public function updatePassword($student_id, $password) {
        if (empty($password)) {
            return ['status' => 'error', 'message' => 'Password cannot be empty'];
        }
        return $this->studentModel->updatePassword($student_id, $password);
    }

    public function getAllStudents() {
        try {
            return $this->studentModel->getAllStudentsOrdered();
        } catch (Exception $e) {
            error_log("Error fetching students: " . $e->getMessage());
            return [];
        }
    }
}
