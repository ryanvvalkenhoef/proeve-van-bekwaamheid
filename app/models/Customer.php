<?php
class Customer {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function register($data) {
        try {
            $this->db->preparedStmt('INSERT INTO `users` (`username`, `email`, `password`) VALUES(NULL, ?, ?, ?)');

            // Execute the statement with binded values and return boolean based on whether it's completed or not.
            return ($this->db->execute(array(
                $data['username'],
                $data['email'],
                $data['password']
            )));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        
    }

    public function login($username_email, $password) {
        $this->db->preparedStmt('SELECT * FROM `customers` WHERE `username`=:username OR `email`=:email');

        // Execute statement with binded values
        $this->db->execute(array(':username' => $username_email, ':email' => $username_email));

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

    // Find customer by the email that's received by the controller.
    public function findCustomerByEmail($email) {
        try {
            // Prepared statement
            $stmt = $this->db->preparedStmt('SELECT * FROM `customers` WHERE `email`=:email');

            // Bind email param
            $this->db->bind(':email', $email);

            // Check if email is registered already
            return ($this->db->rowCount() > 0);

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}