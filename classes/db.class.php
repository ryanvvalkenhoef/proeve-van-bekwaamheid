<?php
class DB {

    public $config;
    protected $conn;

    // Called automatically upon initiation
    public function __construct() {
        $this->config = parse_ini_file('config/config.ini', true);
    }

    // Connects to the database
    function connect() {
        try {
            $dsn = 'mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['name'];
            $this->conn = new PDO($dsn, $this->config['user'], $this->config['pass']); //Initiates connection
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
            $this->l = null; // Closes connection
        } catch (PDOException $e) {
            file_put_contents("log/dberror.log", "Date: " . date('M j Y - G:i:s') . " ---- Error: " . $e->getMessage().PHP_EOL, FILE_APPEND);
            die($e->getMessage());
        }
    }

}