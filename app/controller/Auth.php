<?php

namespace App\Controller;

use App\Model\UserModel;

class Auth
{
    private $userDB;

    public function __construct()
    {
        $this->userDB = new UserModel();
    }

    public function register()
    {
        // Check if the form is submitted via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim(isset($_POST['name']) ? $_POST['name'] : ''),
                'email' => trim(isset($_POST['email']) ? $_POST['email'] : ''),
                'password' => trim(isset($_POST['password']) ? $_POST['password'] : ''),
                'confirm_pass' => trim(isset($_POST['confirm_pass']) ? $_POST['confirm_pass'] : ''),
            ];

            $errors = [];
            if (empty($data['name'])) {
                $errors[] = "Username is required.";
            }

            if (empty($data['email'])) {
                $errors[] = "Email is required.";
            } elseif (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }

            if (empty($data['password'])) {
                $errors[] = "Password is required.";
            }

            if ($data['password'] !== $data['confirm_pass']) {
                $errors[] = "Confirm Password not matched.";
            }

            // If there are no errors, create the user
            if (empty($errors)) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

                $result = $this->userDB->create($data);
                if ($result) {
                    header('Location: /login');
                    exit;
                } else {
                    $errors['general'] = 'Registration failed. Please try again.';
                }
            }

            // If errors exist, load the register view with errors
            require __DIR__ . '/../../view/auth/register.php';
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Sanitize inputs
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validate inputs
            if (empty($email)) {
                $errors['email'] = 'Email is required.';
            }
            if (empty($password)) {
                $errors['password'] = 'Password is required.';
            }

            // If no errors, check credentials
            if (empty($errors)) {
                $user = $this->userDB->findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    // Set user session
                    session_start();
                    $_SESSION['user'] = $user;
                    header('Location: /events');
                    exit;
                } else {
                    $errors['general'] = 'Invalid email or password.';
                }
            }

            // If errors exist, load the login view with errors
            require __DIR__ . '/login';
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        // Redirect to the login page
        header('Location: /login');
        exit;
    }
}
