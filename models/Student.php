<?php

class Student {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getStudentById($id) {
        $sql = "SELECT * FROM students WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function updateProfile($id, $data) {
        $sql = "UPDATE students SET name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", $data['name'], $data['email'], $data['phone'], $id);
        return $stmt->execute();
    }
    
    public function updatePassword($id, $password) {
        $sql = "UPDATE students SET password = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $password, $id);
        return $stmt->execute();
    }
}
