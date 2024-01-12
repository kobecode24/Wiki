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

    public function getWikisByAuthor($authorId) {
        $stmt = $this->db->prepare("SELECT * FROM Wikis WHERE author_id = :authorId");
        $stmt->execute([':authorId' => $authorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentWikis($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT * FROM Wikis 
            WHERE is_archived = 0 
            ORDER BY id DESC 
            LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
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

    public function getWikiDetails($wikiId) {
        $stmt = $this->db->prepare("
            SELECT Wikis.id, Wikis.title, Wikis.content, Wikis.is_archived,
                   Users.username AS author, Categories.name AS category,
                   GROUP_CONCAT(Tags.name ORDER BY Tags.name ASC SEPARATOR ', ') AS tags
            FROM Wikis
                     INNER JOIN Users ON Wikis.author_id = Users.id
                     INNER JOIN Categories ON Wikis.category_id = Categories.id
                     LEFT JOIN WikiTags ON Wikis.id = WikiTags.wiki_id
                     LEFT JOIN Tags ON WikiTags.tag_id = Tags.id
            WHERE Wikis.id = :wikiId
            GROUP BY Wikis.id
        ");
        $stmt->execute([':wikiId' => $wikiId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchWikis($searchTerm) {
        $query = "SELECT * FROM Wikis WHERE title LIKE :searchTerm AND is_archived = 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalWikiCount() {
        $sql = "SELECT COUNT(*) FROM Wikis";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }




}
