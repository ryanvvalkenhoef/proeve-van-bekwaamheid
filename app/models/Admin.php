<?php
class Admin {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function login($email, $password) {
        $this->db->preparedStmt('SELECT * FROM `admin-users` WHERE `email`=:email');

        // Execute statement with binded values
        $this->db->execute(array(':email' => $email));

        // Get customer and it's hashed password
        $row = $this->db->single();
        $hashedPassword = $row->password;

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            return $row;
        } else {
            return false;
        }
    }
    
}