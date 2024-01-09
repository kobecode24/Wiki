<?php

namespace Myapp\Model;

use MyApp\Config\DbConnection;
use PDO;
use PDOException;

class User
{
    private $db;
public function __construct()
{
    $this->db=DbConnection::getInstance()->getConnection();
}

public function emailExists($email)
{
    $stmt=$this->db->prepare("SELECT email FROM users WHERE email=:email");
    $stmt->execute(["email"=>$email]);
    return $stmt->fetchColumn()>0;
}
public function createUser($username,$email,$password,$role_id)
{
    try {
        $hashedpassword=password_hash($password,PASSWORD_DEFAULT);
        $stmt=$this->db->prepare("INSERT INTO users (username, email, password,role_id) VALUES (:username,:email,:password,:role_id)");
        $stmt->execute([
            "username"=>$username,
            "email"=>$email,
            "password"=>$hashedpassword,
            "role_id"=>$role_id
        ]);
        return $this->db->lastInsertId();
    }catch (PDOException $e){
        return false;
    }
}

public function verifyLogin($email,$password)
{
    $stmt=$this->db->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(["email"=>$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password,$user["password"])){
        return $user;
    }
    return false;
}
}
