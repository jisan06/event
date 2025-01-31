<?php

namespace App\Utils;

use mysqli;

class Database
{
    private static $instance = null; // Holds the single instance
    private $connection;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $this->loadEnv();
        $host = getenv('DB_HOST') ?: 'localhost';
        $username = getenv('DB_USERNAME') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $database = getenv('DB_NAME') ?: 'event';

        $this->connection = new mysqli($host, $username, $password, $database);

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
    public function __wakeup(){}

    // Load .env file variables
    private function loadEnv()
    {
        $envPath = __DIR__ . '/../../.env'; // Adjust path as needed
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    putenv(trim($key) . '=' . trim($value));
                }
            }
        }
    }
}
