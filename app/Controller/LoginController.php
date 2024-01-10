<?php
namespace MyApp\Controller;
require_once '../../vendor/autoload.php';

use MyApp\Model\User;
use PDOException;

class LoginController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login($formData)
    {
        $errors = [];
        session_start();

        if (empty($formData['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($formData['password'])) {
            $errors['password'] = 'Password is required.';
        }

        if (empty($errors)) {
            try {
                $user = $this->userModel->verifyLogin($formData['email'], $formData['password']);
                if ($user) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role_id'] = $user['role_id'];
                    $_SESSION['loggedin'] = true;
                    header("Location: ../../index.php");
                    exit;
                } else {
                    $errors['general'] = 'Login failed. Please check your credentials.';
                }
            } catch (PDOException $e) {
                $errors['general'] = 'An error occurred during login. Please try again.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: ../../View/auth/login.php");
            exit;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $loginController = new LoginController();
    $loginController->login($_POST);
}
