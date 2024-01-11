<?php
namespace MyApp\Model;
use MyApp\Config\DbConnection;
use PDO;
use PDOException;

class Wiki
{
    private $db;

    public function __construct()
    {
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function getAllWikis()
    {
        $stmt = $this->db->prepare("SELECT * FROM Wikis");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addWiki($title, $content, $author_id, $category_id, $tags)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO Wikis (title, content, author_id, category_id) VALUES (:title, :content, :author_id, :category_id)");
            $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':author_id' => $author_id,
                ':category_id' => $category_id
            ]);
            $wikiId = $this->db->lastInsertId();

            foreach ($tags as $tagId) {
                $tagStmt = $this->db->prepare("INSERT INTO WikiTags (wiki_id, tag_id) VALUES (:wiki_id, :tag_id)");
                $tagStmt->execute([
                    ':wiki_id' => $wikiId,
                    ':tag_id' => $tagId
                ]);
            }

            return $wikiId;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    public function getWikiById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Wikis WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateWiki($id, $title, $content, $category_id)
    {
        $stmt = $this->db->prepare("UPDATE Wikis SET title = :title, content = :content, category_id = :category_id WHERE id = :id");
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':category_id' => $category_id,
            ':id' => $id
        ]);
        return $stmt->rowCount();
    }

    public function deleteWiki($id)
    {
        $stmt = $this->db->prepare("DELETE FROM Wikis WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    public function getWikisByCategory($categoryId) {
        $stmt = $this->db->prepare("SELECT * FROM Wikis WHERE category_id = :categoryId");
        $stmt->execute([':categoryId' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWikisByAuthor($authorId) {
        $stmt = $this->db->prepare("SELECT * FROM Wikis WHERE author_id = :authorId");
        $stmt->execute([':authorId' => $authorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentWikis($limit = 10) {
        $stmt = $this->db->prepare("SELECT * FROM Wikis ORDER BY id DESC LIMIT :limit");
        $stmt->execute([':limit' => $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWikisByTag($tagId) {
        $stmt = $this->db->prepare("SELECT Wikis.* FROM Wikis JOIN WikiTags ON Wikis.id = WikiTags.wiki_id WHERE WikiTags.tag_id = :tagId");
        $stmt->execute([':tagId' => $tagId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArchivedWikis() {
        $stmt = $this->db->prepare("SELECT * FROM Wikis WHERE is_archived = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsByWikiId($wikiId)
    {
        $stmt = $this->db->prepare("SELECT tag_id FROM WikiTags WHERE wiki_id = :wiki_id");
        $stmt->execute([':wiki_id' => $wikiId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function updateWikiTags($wikiId, array $tags)
    {
        $sqlDelete = "DELETE FROM WikiTags WHERE wiki_id = :wiki_id";
        $stmtDelete = $this->db->prepare($sqlDelete);
        $stmtDelete->execute([':wiki_id' => $wikiId]);

        $sqlInsert = "INSERT INTO WikiTags (wiki_id, tag_id) VALUES (:wiki_id, :tag_id)";
        $stmtInsert = $this->db->prepare($sqlInsert);

        foreach ($tags as $tagId) {
            $stmtInsert->execute([
                ':wiki_id' => $wikiId,
                ':tag_id' => $tagId
            ]);
        }
    }
    public function archiveWiki($id) {
        $stmt = $this->db->prepare("UPDATE Wikis SET is_archived = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function unarchiveWiki($id) {
        $stmt = $this->db->prepare("UPDATE Wikis SET is_archived = 0 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
