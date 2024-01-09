<?php

namespace MyApp\Config;

require_once __DIR__ . "/../../vendor/autoload.php";

use Dotenv\Dotenv;
use PDO;
use PDOException;

class DbConnection{
    private static $instance=null;
    private $connection;

private function __construct()
    {
        $dotenv= Dotenv::createImmutable(__DIR__.'/../..');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $database = $_ENV['DB_NAME'];

        $dsn= "mysql:host=$host;dbname=$database";

        try {
            $this->connection= new PDO($dsn,$username,$password);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
public static function getInstance()
    {
        if (!self::$instance){
            self::$instance=new DbConnection();
        }
        return self::$instance;
    }
public function getConnection(){
    return $this->connection;
}
}