<?php
class Database {
    
    public $config;
    protected $conn;

    private $statement;

    // Called automatically upon initiation
    public function __construct() {
        $this->config = parse_ini_file(APPROOT . '/config/config.ini', true);
        $this->connect();
    }

    // Connects to the database
    function connect() {
        try {
            $dsn = 'mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['name'];
            $this->conn = new PDO($dsn, $this->config['user'], $this->config['pass']); // Initiates connection
            $this->conn->setAttribute(PDO::ATTR_PERSISTENT, true); // Sets persistent attribute to true
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Sets error mode
        } catch (PDOException $e) {
            file_put_contents("log/dberror.log", "Date: " . date('M j Y - G:i:s') . " ---- Error: " . $e->getMessage().PHP_EOL, FILE_APPEND);
            die($e->getMessage()); // Log and display error in the event that there is an issue connecting
        }
        $conn = $this->conn;
        return $conn;
    }

    // Called automatically when there are no further references to object
    function __destruct() {
        try {
            $this->conn = null; // Closes connection
        } catch (PDOException $e) {
            file_put_contents("log/dberror.log", "Date: " . date('M j Y - G:i:s') . " ---- Error: " . $e->getMessage().PHP_EOL, FILE_APPEND);
            die($e->getMessage());
        }
    }

    // Prepares a statement
    public function preparedStmt($sql) {
        $this->statement = $this->conn->prepare($sql);
    }

    // Binds values
    public function bind($parameter, $value, $type = null) {
        switch (is_null($type)) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
        $this->statement->bindValue($parameter, $value, $type);
    }

    // Executes the prepared statement
    public function execute() {
        return $this->statement->execute();
    }

    // Returns an array
    public function resultSet() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    // Returns a specific row as an object
    public function single() {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    // Gets the row count
    public function rowCount() {
        return $this->statement->rowCount();
    }

    public function fetchColumn() {
        return $this->statement->fetchColumn();
    }

    // Gets the error info
    public function errorInfo() {
        return $this->statement->errorInfo();
    }
}