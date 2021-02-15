<?php

ini_set('display_errors', 1);
define("table", $SERVER['PHP_SELF']);

class Auth extends DB {
    
    public function login() {
        session_start();

        if(isset($_REQUEST['btnlogin'])) {
            // -- Define sanitized email and password
            $username_email = strip_tags(trim($_REQUEST['username_email']));
            $password = strip_tags(trim($_REQUEST['password']));
        }

        // -- Validate inputs
        if (empty($username_email)) {
            $errorMsg[] = "Please enter username or email";
        } else if (empty($password)) {
            $errorMsg[] = "Please enter password";
        } else {
            try {
                $stmt = $this->connect()->prepare("SELECT * FROM " . constant("table") . " WHERE `username`=:username OR `email`=:email");
                $stmt->execute(array(':username' => $username_email, ':email' => $username_email));
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // -- Log in if admin account was found
                if ($stmt->rowCount() > 0) {
                    if ($username_email == $user['username'] || $username_email == $user['email']) {
                        if (password_verify($password, $user['password'])) { // Verify password
                            $_SESSION['user_login'] = $user['id'];
                            $loginMsg = "Login was succesful";
                            header("refresh:1; ./index.php");
                        } else { 
                            $errorMsg[] = "Wrong password"; 
                        }
                    } else { 
                        $errorMsg[] = "Wrong username or email";
                     }
                } else {
                    $errorMsg[] = "Wrong username or email";
                }
            } catch (PDOException $e) {
                $e->getMessage();
            }
        }
        $this->showMessages($errorMsg ?? null, $loginMsg ?? null);
    }

    public function logout() {
        // -- Log out
        // Make sure there's no session and direct back to login page
        session_start();
        header('Location:./login.php');
        session_destroy();
    }

    public function checkAuthorized() {
        session_start();
        // Direct back to login page if not authorized
        if (!isset($_SESSION['user_login'])) {
            $table = constant("table");
            $prefix = ($table == "admin-users") ? "/" . $table : "";
            header("Location: ." . $prefix ."/login.php");
        }

        // Execute query with id of the session
        $id = $_SESSION['user_login'];
        $query = "SELECT * FROM " . constant("table") . " WHERE `id`=:userid";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute(array(':userid' => $id));

        // Use statement to display the users's name
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($_SESSION['user_login'])) {
            echo 'Welcome ' . $user['firstname'] . ' ' . $user['lastname'];
            echo ' | <a href="logout.php">Logout</a><br />';
            $crudObj = new CRUD(); // Initiate CRUD class
			echo $crudObj->generateTable();
        }
    }

    public function showMessages($errorMsg, $loginMsg) {
        if (isset($errorMsg)) {
            foreach($errorMsg as $error) {
                echo '<div class="alert alert-danger">';
                echo '<strong>' . $error . '</strong>'; echo '</div>';
            }
        }
        if (isset($loginMsg)) {
            echo '<div class="alert alert-success">';
            echo '<strong>' . $loginMsg . '</strong>'; echo '</div>';
        }
    }

}