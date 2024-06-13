<?php
ini_set('display_errors', 1);

class Auth extends Database {

    public function register_user($data) {
        try {
            $query = 'INSERT INTO `users`';
            $query .= ' (`id`, `role`, `name`, `username`, `email`, `password`, `hashed_password`, `created_at`, `updated_at`)';
            $query .= ' VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?)';

            $this->preparedStmt($query);
            // Execute the statement with binded values and return boolean based on whether it's completed or not.
            $this->bind(1, $data[0]);
            $this->bind(2, $data[1]);
            $this->bind(3, $data[2]);
            $this->bind(4, $data[3]);
            $this->bind(5, $data[4]);
            $this->bind(6, password_hash($data[4], PASSWORD_BCRYPT, ["cost"=>8]));
            $this->bind(7, date('Y-m-d H:i:s'));
            $this->bind(8, date('Y-m-d H:i:s'));
            
            $row = $this->single();
            if ($row) {
                return $row;
            } else {
                return true;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function login($username_email, $password) {
        try {
            $this->preparedStmt('SELECT * FROM `users` WHERE `username`=:username OR `email`=:email');

            $this->bind(':username', $username_email);
            $this->bind(':email', $username_email);

            //  Execute statement with binded values
            $this->execute();

            // Get user and it's hashed password
            $row = $this->single();
            if ($row && isset($row->hashed_password)) {
                // Verify password
                if (password_verify($password, $row->hashed_password)) {
                    return $row;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // Find user by the email that's received by the controller.
    public function findUser($value, $byEmail) {
        try {
            // Prepared statement
            $query = 'SELECT * FROM `users` ';
            $query .= ($byEmail) ? 'WHERE `email`=:email' : 'WHERE `username`=:username';
            $this->preparedStmt($query);

            // Bind email param
            ($byEmail) ? $this->bind(':email', $value) : $this->bind(':username', $value);

            // Execute the query
            $this->execute();

            // Check if email is registered already
            return ($this->rowCount() > 0);

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
}

trait ValidateInputs {

    public function validateLoginData($data) {
        $vData = $data;

        // Validate username/email
        if (empty($vData['username_email'])) {
            $vData['errors'][] = 'Vul alstublieft een gebruikersnaam of e-mailadres in.';
        } else {
            if (filter_var($vData['username_email'], FILTER_VALIDATE_EMAIL)) {
                // This is an email
                if (empty($vData['username_email'])) {
                    $vData['errors'][] = 'Vul alstublieft een e-mailadres in.';
                } elseif (!filter_var($vData['username_email'], FILTER_VALIDATE_EMAIL)) {
                    $vData['errors'][] = 'Vul alstublieft een valide e-mailadres in.';
                } else {
                    // Check if email exists
                    if (!$this->authModel->findUser($vData['username_email'], true)) {
                        $vData['errors'][] = 'Het opgegeven e-mailadres is niet geregistreerd.';
                    }
                }
            } else {
                // This is a username
                if (empty($vData['username_email'])) {
                    $vData['errors'][] = 'Vul alstublieft een gebruikersnaam in.';
                } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $vData['username_email'])) {
                    $vData['errors'][] = 'Een gebruikersnaam mag alleen letters en cijfers bevatten.';
                }
            }
        }

        // Validate password on length and numeric values
        if (empty($vData['password'])) {
            $vData['errors'][] = 'Vul alstublieft een wachtwoord in.';
        } elseif (strlen($vData['password']) < 8 && strlen($vData['password']) <= 32) {
            $vData['errors'][] = 'Het wachtwoord moet tussen de 8 en 32 tekens bevatten.';
        } elseif (!preg_match("/[0-9]/", $vData['password'])) {
            $vData['errors'][] = 'Het wachtwoord moet minstens een enkel cijfer bevatten.';
        }
        
        return $vData;
    }

    public function validateRegisData($data, $userInTreatment = null) {
        $vData = $data;
        // Validate role
        if ($vData['role'] != 'editor' && $vData['role'] != 'admin') {
            $vData['errors'][] = 'U heeft geen geldige rol opgegeven.';
        }

        // Validate full name
        if (empty($vData['name'])) {
            $vData['errors'][] = 'Vul alstublieft een volledige naam in.';
        } elseif (count(explode(' ', $data['name'])) == 1) {
            $vData['errors'][] = 'De naam die u heeft opgegeven is niet volledig.';
        } else {
            if ($vData['name'] == 'Hoofdaccount') {
                $vData['errors'][] = 'Vul alstublieft een andere naam in.';
            }
        }

        // Validate username on letters/numbers
        if (empty($vData['username'])) {
            $vData['errors'][] = 'Vul alstublieft een gebruikersnaam in.';
            // Check if the username is valid throughout a regular expression
        } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $vData['username'])) {
            $vData['errors'][] = 'Een gebruikersnaam mag alleen letters en cijfers bevatten.';
        } else {
            // Check if username exists
            $user = $this->authModel->findUser($vData['username'], false);
            if ($user && $vData['username'] != $userInTreatment['username']) {
                $vData['errors'][] = 'De opgegeven gebruikersnaam is al in gebruik.';
            }
        }

        // Validate email
        if (empty($vData['email'])) {
            $vData['errors'][] = 'Vul alstublieft een e-mailadres in.';
        } elseif (!filter_var($vData['email'], FILTER_VALIDATE_EMAIL)) {
            $vData['errors'][] = 'Vul alstublieft een valide e-mailadres in.';
        } else {
            // Check if email exists
            $user = $this->authModel->findUser($vData['email'], true);
            if ($user && $vData['email'] != $userInTreatment['email']) {
                $vData['errors'][] = 'Het opgegeven e-mailadres is al in gebruik.';
            }
        }

        // Validate password on length and numeric values
        if (empty($vData['password'])) {
            $vData['errors'][] = 'Vul alstublieft een wachtwoord in.';
        } elseif (strlen($vData['password']) < 8 && strlen($vData['password']) <= 32) {
            $vData['errors'][] = 'Het wachtwoord moet tussen de 8 en 32 tekens bevatten.';
        } elseif (!preg_match("/[0-9]/", $vData['password'])) {
            $vData['errors'][] = 'Het wachtwoord moet minstens een enkel cijfer bevatten.';
        }

        // Validate confirm password
        if (empty($vData['confirmPassword'])) {
            $vData['errors'][] = 'Bevestig alstublieft uw wachtwoord.';
        } else {
            if ($vData['password'] != $vData['confirmPassword']) {
                $vData['errors'][] = 'De wachtwoorden komen niet overeen, probeer het alstublieft opnieuw.';
            }
        }

        return $vData;
    }

    public function sanitizePostData() {
        $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if ($_POST !== null && $_POST !== false) {
                foreach ($_POST as $key => $value) {
                    $_POST[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                }
            }
    }

    public function getUserId() {
        if (isset($_GET['upd'])) {
            return $_GET['upd'];
        } else {
            die('ID is niet opgegeven');
        }
    }

}