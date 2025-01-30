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
        session_start();
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

            $user = $this->userDB->findByEmail($_POST['email']);
            if($user) {
                $errors[] = 'This email already registered';
            }

            // If there are no errors, create the user
            if (empty($errors)) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

                $result = $this->userDB->create($data);
                if ($result) {
                    $_SESSION['success'] = 'Your registration complete, you can login now';
                    header("Location: " . BASE_URL . "login");
                    exit;
                } else {
                    $errors[] = 'Registration failed. Please try again.';
                }
            }

            // If there are validation errors
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: " . BASE_URL . "register");
                exit;
            }
        }
    }

    public function login()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Sanitize inputs
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Validate inputs
            if (empty($email)) {
                $errors[] = 'Email is required.';
            }
            if (empty($password)) {
                $errors[] = 'Password is required.';
            }

            // If no errors, check credentials
            if (empty($errors)) {
                $user = $this->userDB->findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: " . BASE_URL . "events");
                    exit;
                } else {
                    $errors[] = 'Invalid email or password.';
                }
            }

            // If there are validation errors
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: " . BASE_URL . "login");
                exit;
            }
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        // Redirect to the login page
        header("Location: " . BASE_URL . "login");
        exit;
    }
}
