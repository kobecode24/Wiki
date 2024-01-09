<?php
namespace MyApp\Controller;
require_once '../../vendor/autoload.php';

 use MyApp\Model\User;
 use PDOException;

 class SignupController{

    private $userModel;

    public function __construct()
    {
        $this->userModel= new User();
    }

    public function register($formData)
    {
        $errors=[];
        session_start();
        if (empty($formData['username'])) {
            $errors['username'] = 'Username is required.';
        }

        if (empty($formData['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($formData['password'])) {
            $errors['password'] = 'Password is required.';
        }

        if (empty($formData['role_id'])) {
            $errors['role_id'] = 'Role is required.';
        }

        if ($this->userModel->emailExists($formData["email"])){
            $errors['email'] = 'This email is already registered. Please use a different email.';
        }

        if (empty($errors)){
            try {
                $userId = $this->userModel->createUser(
                    $formData['username'],
                    $formData['email'],
                    $formData['password'],
                    $formData['role_id']
                );
                $_SESSION['user_id'] = $userId;
                header("Location: ../../View/auth/login.php");
                exit;
            } catch (PDOException $e) {
                $errors['general'] = 'An error occurred during registration. Please try again.';
            }

        }
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: ../../View/auth/signup.php");
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD']=="POST"){
    $signupController= new SignupController();
    $signupController->register($_POST);
}