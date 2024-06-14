<?php
ini_set('display_errors', 1);

class CRUD extends Database {

    public function read($table, $id = null, $searchParams = []) {
        $addition = $id ? " WHERE `id`=:id" : "";
        $search = '';
        if ($searchParams !== [] && is_array($searchParams)) {
            switch ($table) {
                case 'users':
                    $search = " WHERE `name` LIKE :searchParam OR `email` LIKE :searchParam OR `username` LIKE :searchParam";
                  break;
                case 'reservations':
                    $search = " WHERE reservations.reserved_for LIKE :searchParam OR reservations.receipt LIKE :searchParam OR elective_modules.title LIKE :searchParam";
                  break;
                case 'elective_modules':
                    $search = " WHERE `title` LIKE :searchParam OR `author` LIKE :searchParam OR `category` LIKE :searchParam";
                  break;
              }
        }
        $query = ($table == 'reservations') ? "SELECT reservations.*, elective_modules.title AS module_title FROM reservations JOIN elective_modules ON reservations.module_id = elective_modules.id" : "SELECT * FROM " . $table;

        try {
            if ($id !== null && $searchParams === []) {
                $query .= $addition;
            } else if ($id === null && $searchParams !== []) {
                $query .= $search;
            }
            $stmt = $this->conn->prepare($query);
    
            // Execute the query if $id is not null and bind the parameter :id
            if ($id !== null) $stmt->bindParam(':id', $id);
            if (!empty($searchParams)) {
                if (count($searchParams) == 1) {
                    $likeParam = '%' . $searchParams[0] . '%';
                    $stmt->bindParam(':searchParam', $likeParam, PDO::PARAM_STR);
                }
            }
    
            // Execute the query
            if (!$stmt->execute()) {
                $stmt->errorInfo();
            } else {
                // If the query is successful, return the results
                if ($id !== null) {
                    // If $id is not null, return the single row matching the specified id
                    return $stmt->fetch(PDO::FETCH_OBJ);
                } else {
                    // If $id is null, return all rows
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                }
            }
        } catch (PDOException $e) {
            // Catch any PDO exceptions and display the error message
            die($e->getMessage());
        }
    }

    public function getModuleBy($year = null, $month = null, $slug = null, $title = null) {
        $query = "";
        if ($title == null) {
            $query = "SELECT * FROM elective_modules WHERE YEAR(creation_date) = :year AND MONTH(creation_date) = :month AND title LIKE :slug";
        } else {
            $query = "SELECT * FROM elective_modules WHERE title = :title";
        }
    
        try {
            // Prepare the query
            $stmt = $this->conn->prepare($query);
    
            // Bind parameters
            if ($title == null) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);  // Assuming $year is an integer
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);  // Assuming $month is an integer
                $stmt->bindValue(':slug', '%' . $slug . '%', PDO::PARAM_STR);
            } else {
                $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            }
    
            // Execute the query
            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Query execution failed: " . $errorInfo[2]);
            }
    
            // Fetch the result
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            return $result;
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function moduleAvailability($id) {
        $query = "SELECT id, title, (amount_registered < registration_places) AS isAvailable FROM elective_modules WHERE `id` = :id";

        try {
            $this->preparedStmt($query);

            if ($id !== null) $this->bind(':id', $id, PDO::PARAM_STR);

            // Execute the query
            if (!$this->execute()) {
                print_r($this->errorInfo());
            } else {
                return $this->single();
            }
        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function countAll($table, $searchParam = null) {
        $query = "SELECT COUNT(*) FROM `" . $table . "`";
        $search = $searchParam ? " WHERE `name` LIKE :searchParam OR `email` LIKE :searchParam OR `username` LIKE :searchParam" : "";

        try {
            if ($searchParam !== null) $query .= $search;

            $this->preparedStmt($query);

            if ($searchParam !== null) {
                $likeParam = '%' . $searchParam . '%';
                $this->bind(':searchParam', $likeParam, PDO::PARAM_STR);
            }

            // Execute the query
            if (!$this->execute()) {
                print_r($this->errorInfo());
            } else {
                return $this->fetchColumn();
            }
        } catch (PDOException $e) {
            die ($e->getMessage());
        }
    }

    public function update($table, $inputs, $updateUser) {
        // Make query for database
        $query = "UPDATE `" . $table . "`";
        $query .= $updateUser ? " SET `role` = ?, `name` = ?, `username` = ?, `email` = ?, `password` = ?, `hashed_password` = ?, `updated_at` = ?"
                              : " SET `title` = ?, `author` = ?, `author_id` = ?, `creation_date` = ?, `image` = ?, `category` = ?, `text_content` = ?, `amount_registered` = ?, `registration_places` = ?";
        $query .= " WHERE `id` = ?";

        try {
            // Set stmt (statement) to prepared query
            $stmt = $this->conn->prepare($query);
            
            // Bind parameters based on whether it's updating user or module
        if ($updateUser) {
            // Update user
            $hashedPassword = password_hash($inputs[5], PASSWORD_BCRYPT, ["cost"=>8]);
            $stmt->bindParam(1, $inputs[0]);
            $stmt->bindParam(2, $inputs[1]);
            $stmt->bindParam(3, $inputs[2]);
            $stmt->bindParam(4, $inputs[3]);
            $stmt->bindParam(5, $inputs[4]);
            $stmt->bindParam(6, $hashedPassword);
            $stmt->bindParam(7, date('Y-m-d H:i:s'));
            $stmt->bindParam(8, $_GET['upd']);
        } else {
            // Update module
            $stmt->bindParam(1, $inputs[0]);
            $stmt->bindParam(2, $inputs[1]);
            $stmt->bindParam(3, $inputs[2]);
            $stmt->bindParam(4, $inputs[3]);
            $stmt->bindParam(5, $inputs[4], PDO::PARAM_LOB); // Bind BLOB as PDO::PARAM_LOB
            $stmt->bindParam(6, $inputs[5]);
            $stmt->bindParam(7, $inputs[6]);
            $stmt->bindParam(8, $inputs[7]);
            $stmt->bindParam(9, $inputs[8]);
            $stmt->bindParam(10, $_GET['upd']);
        }

        // Execute the statement
        $stmt->execute();

        // Return the updated record
        return $updateUser ? $this->read('users', $_GET['upd']) : $this->read('elective_modules', $_GET['upd']);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->__destruct();
    }

    public function create($table, $inputs) {
        // Make query for database
        $query = "INSERT INTO " . $table . " ";
        // Query parts for insertion of elective modules
        if ($table == 'elective_modules') {
            $query .= "(`id`, `title`, `author`, `author_id`, `creation_date`, `image`, `category`, `text_content`, `amount_registered`, `registration_places`) ";
            $query .= "VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else if ($table == 'reservations') {
            $query .= "(`id`, `module_id`, `reserved_for`, `reserved_at`, `receipt`, `email`, `phone_number`, `comments`) ";
            $query .= "VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)";
        }

        try {
            $stmt = $this->conn->prepare($query);
            // Insert item
            if ($stmt->execute($inputs)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->__destruct();
    }

    public function delete($table) {

        // Make query for database
        $query = "DELETE FROM " . $table . " ";
        $query .= "WHERE id=:id ";
        $query .= "LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute(array(':id' => $_GET['del']))) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}