<?php

namespace App\Utils;

use mysqli;

class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'mysql';
    private $database = 'event';
    private static $instance = null; // Holds the single instance
    private $connection;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Public method to get the single instance
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    // Get the database connection
    public function getConnection()
    {
        return $this->connection;
    }

    // This will allow us to prepare a statement in the Model
    public function prepare($query)
    {
        return $this->connection->prepare($query);
    }

    // Method to run simple queries (e.g., SELECT, INSERT, UPDATE, DELETE)
    public function query($query)
    {
        return $this->connection->query($query);
    }

    // Prevent cloning of the instance
    private function __clone()
    {
    }

    // Prevent unserialization of the instance
    public function __wakeup()
    {
    }
}
