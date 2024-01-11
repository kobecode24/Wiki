<?php

namespace MyApp\Model;
use MyApp\Config\DbConnection;
use PDO;
use PDOException;

class Tag
{
    private $db;

    public function __construct()
    {
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function showAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM tags");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($name)
    {
            $stmt = $this->db->prepare("INSERT INTO tags(name) VALUES (:name)");
            return $stmt->execute(["name" => $name]);
    }

    public function getTagById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tags WHERE id=:id");
        $stmt->execute(["id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTag($name, $id)
    {
        $stmt = $this->db->prepare("UPDATE tags SET name=:name WHERE id=:id");
        return $stmt->execute(["name" => $name, "id" => $id]);
    }

    public function deleteTag($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tags WHERE id=:id");
        return $stmt->execute(["id" => $id]);
    }

    public function countTags()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tags");
        $stmt->execute();
        return $stmt->fetchColumn();
    }


}
