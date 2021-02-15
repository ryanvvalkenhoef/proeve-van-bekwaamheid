<?php

ini_set('display_errors', 1);

trait AdminUsers {

    use DOMElements, Inputs;

    public function read($getsUpdated) {
        $addition = $getsUpdated ? " WHERE `id`=:id" : "";
        $query = "SELECT * FROM `tbl_users`" . $addition;

        try {
            $stmt = $this->connect()->prepare($query);

            // Execute the command and check for errors
            if ($getsUpdated) { // Read by id when update page gets loaded
                if (!$stmt->execute(array(":id" => $_GET['upd']))) {
                    print_r($stmt->errorInfo());
                } else {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $user;
                }
            } else {
                if (!$stmt->execute()) print_r($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $stmt;
    }

    public function update() {
        // Validate inputs and return inputs
        $inputs = $this->getValidated();

        // Make query for database
        $query = "UPDATE `tbl_users` ";
        $query .= "SET `firstname`=?, `lastname`=?, `email`=?, `username`=?, `password`=?";
        $query .= "WHERE `id`=?";

        try {
            // Set stmt (statement) to prepared query
            $stmt = $this->connect()->prepare($query);
            
            // Update item
            $stmt->execute(array($inputs[0], 
                                $inputs[1], 
                                $inputs[2], 
                                $inputs[3], 
                                $inputs[4], 
                                strval($_GET['id']) ?? null));
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->__destruct();

        // Display message that a new person is inserted
        echo '<script type="text/javascript">alert("Person\'s information was succesfully updated")
        </script>';
        header("refresh:0.5; ./index.php");
    }

    public function create() {

        // Validate inputs and return inputs
        $inputs = $this->getValidated();

        // Make query for database
        $query = "INSERT INTO `tbl_users` ";
        $query .= "(`id`, `firstname`, `lastname`, `email`, `username`, `password`, `registration`) ";
        $query .= "VALUES(NULL, ?, ?, ?, ?, ?, ?) ";

        try {
            // Set stmt (statement) to prepared query
            $stmt = $this->connect()->prepare($query);
            // Insert item
            $stmt->execute(array(
                $inputs[0],
                $inputs[1],
                $inputs[2],
                $inputs[3],
                $inputs[4],
                $inputs[5]));
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->__destruct();

        // Display message that a new person is inserted
        echo '<script type="text/javascript">alert("Person was succesfully added to the admin registry")
        </script>';
        header("refresh:1; ./index.php");
    }

    public function delete() {

        // Make query for database
        $query = "DELETE FROM `tbl_users` ";
        $query .= "WHERE id=:id ";
        $query .= "LIMIT 1";

        try {
            $stmt = $this->connect()->prepare($query);
            $stmt->execute(array(':id' => $_GET['del'] ?? null));
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        echo '<script type="text/javascript">alert("Person was succesfully deleted from the admin registry")
        </script>';
        header("refresh:1; ./index.php");
    }
}

trait DOMElements {

    public function generateTable() {
        $stmt = $this->read(false);
        // Make sure table container is empty
        echo "<script type=\"text/javascript\">document.getElementsByClass('table-responsive')[0].innerHTML = \"\";</script>";
        // Create table and form before underlying inputs
        echo "<table class=\"table table-striped\"><thead><tr>
        <th>Firstname</th><th>Lastname</th><th>Email</th><th>Username</th><th>Password</th></tr></thead><tbody>";
        // Fetch one row and place the columns in an associative array 
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $user['firstname']  . '</td>';
            echo '<td>' . $user['lastname']   . '</td>';
            echo '<td>' . $user['email']      . '</td>';
            echo '<td>' . $user['username']   . '</td>';
            echo '<td>' . $user['password']   . '</td>';
            echo '<td><a href="action_completed.php?del='.$user['id'].'" class="btn btn-sm btn-danger">Delete</a></td>';
            echo '<td><a href="update.php?upd='.$user['id'].'" class="btn btn-sm btn-warning">Update</a></td>';
            echo '</tr>';
        }
        echo "</tbody></table>";
        $this->__destruct();
    }
}

trait Inputs {

    public function getValidated() {
        // -- Get POST values if they are present
        $values = array('firstname', 'lastname', 'email', 'username', 'password');
        foreach ($values as $value) {
            if (!isset($_POST[$value])) {
                echo 'Not all fields have been filled in'; exit;
            } else { define($value, trim($_POST[$value])); }
        }

        // -- Get current datetime
        $dt = new DateTime(null, new DateTimeZone('Europe/Amsterdam'));
        $datetime = $dt->format('d-m-Y H:i:s');

        // -- Keep track of validated values
        $valid = array('firstname' => false, 'lastname' => false, 'email' => false, 'username' => false, 'password' => false);
    
        // -- Validate first- and lastname
        $valid['firstname'] = $this->validateName(constant('firstname'));
        $valid['lastname'] = $this->validateName(constant('lastname'));
    
        // -- Validate username
        $email = constant('email');
        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $valid['email'] = true;
            } else {
                echo 'Email is invalid!<br />';
            }
        } else {
            echo 'Email cannot be blank!<br />';
        }
    
        // -- Validate username
        $username = constant('username');
        if (!empty($username)) {
            if (strlen($username) >= 6 && strlen($username) <= 16) {
                // Check if username does not satisfy the regular expression
                if (!preg_match('/[^a-zA-Z\d_.]/', $username)) {
                    $valid['username'] = true;
                } else {
                    echo 'Username can contain only letters!<br />';
                }
            } else {
                echo 'Username must be between 6 and 16 characters long!<br />';
            }
        } else {
            echo 'Username cannot be blank!<br />';
        }

        // -- Validate password
        $password = constant('password');
        if (!empty($password)) {
            if (strlen($password) >= 8 && strlen($password) <= 32) {
                $valid['password'] = true;
                // Hash the password with Bcrypt
                $password = password_hash($password, PASSWORD_BCRYPT, ["cost"=>8]);
            } else {
                echo 'Password must be between 8 and 32 characters<br />';
            }
        } else {
            echo 'Password cannot be blank!<br />';
        }
        // Return validated inputs
        if ($valid['firstname'] && $valid['lastname'] && $valid['email'] && $valid['username'] && $valid['password']) {
            return [constant('firstname'), constant('lastname'), $email, $username, $password, $datetime];
        } else {
            exit;
        }
    }
    
    public function validateName($name) {
        // -- Define variable name
        $varName = ($name == constant('firstname')) ? 'firstname' : 'lastname';
        if (!empty($name)) {
            // Check if string length is between 2 and 40
            if (strlen($name) >= 2 && strlen($name) <= 40) {
                // Check if firstname does not satisfy the regular expression
                if (!preg_match('/[^a-zA-Z\s]/', $name)) {
                    return true;
                } else {
                    echo $varName . ' can contain only letters! <br />';
                }
            } else {
                echo $varName . ' must be between 2 and 40 characters long!<br />';
            }
        } else {
            echo $varName . ' cannot be blank!<br />';
        }
        return false;
    }
}

class CRUD extends DB {

    use AdminUsers;
}