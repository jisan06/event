<?php

namespace App\Model;

use App\Utils\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create( array $data )
    {
        // Prepare the data from the array
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        // Use positional placeholders "?"
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        // Prepare the query
        $user = $this->db->prepare($query);

        // Bind the values to the placeholders
        $user->bind_param('sss', $name, $email, $password);

        // Execute the query
        return $user->execute();
    }

    public function getUserByEmail($email)
    {
        $user = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $user->bind_param("s", $email);
        $user->execute();
        return $user->get_result()->fetch_assoc();
    }
}
