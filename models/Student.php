<?php

class Student {
    private $connect;
    
    public function __construct($connect) {
        $this->connect = $connect;
    }
    
    public function getStudentById($id) {
        $sql = "SELECT * FROM students WHERE id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function updateProfile($id, $data) {
        $sql = "UPDATE students SET name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("sssi", $data['name'], $data['email'], $data['phone'], $id);
        
        if($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Profile updated successfully'];
        }
        return ['status' => 'error', 'message' => 'Failed to update profile'];
    }
    
    public function updatePassword($id, $password) {
        $sql = "UPDATE students SET password = ? WHERE id = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("si", $password, $id);
        
        if($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Password updated successfully'];
        }
        return ['status' => 'error', 'message' => 'Failed to update password'];
    }

    public function validateProfileData($data) {
        if(empty($data['name']) || empty($data['email'])) {
            return ['status' => 'error', 'message' => 'Name and email are required'];
        }
        return ['status' => 'success'];
    }

    public function validatePassword($password, $confirmPassword) {
        if(empty($password)) {
            return ['status' => 'error', 'message' => 'Password cannot be empty'];
        }
        if($password !== $confirmPassword) {
            return ['status' => 'error', 'message' => 'Passwords do not match'];
        }
        return ['status' => 'success'];
    }

    public function getAllStudentsOrdered() {
        $sql = "SELECT * FROM students ORDER BY name ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        
        return $students;
    }
}
