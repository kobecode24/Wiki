<?php

namespace MyApp\Model;
use MyApp\Config\DbConnection;
use PDO;
use PDOException;

class Category
{
    private $db;
    public function __construct()
    {
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function showAll()
    {
            $stmt=$this->db->prepare("SELECT * FROM categories");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function add($name)
    {
            $stmt=$this->db->prepare("INSERT INTO categories(name) VALUES (:name)");
            return $stmt->execute(["name"=>$name]);

    }

    public function getCategoryById($id)
    {
        $stmt=$this->db->prepare("SELECT * FROM categories WHERE id=:id");
        $stmt->execute(["id"=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCategory($name,$id)
    {
        $stmt=$this->db->prepare("UPDATE categories SET name=:name WHERE id=:id");
        return $stmt->execute(["name"=>$name,"id"=>$id]);
    }
    public function deleteCategory($id)
    {
        $stmt=$this->db->prepare("DELETE FROM categories WHERE id=:id");
        return $stmt->execute(["id"=>$id]);
    }
    public function getRecentCategories($limit = 5)
    {
            $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT :limit");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCategoryCount() {
        $sql = "SELECT COUNT(*) FROM Categories";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }
}