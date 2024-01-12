<?php

namespace MyApp\Controller;
require_once __DIR__ . "/../../vendor/autoload.php";

use MyApp\Model\Wiki;
use MyApp\Model\Category;
use MyApp\Model\Tag;


class WikiController
{
    private $wikiModel;
    private $categoryModel;
    private $tagModel;

    public function __construct()
    {
        $this->wikiModel = new Wiki();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
    }

    public function index()
    {
        return $this->wikiModel->getAllWikis();
    }

    public function store($formData)
    {
        $title = $formData['title'];
        $content = $formData['content'];
        $author_id = $formData['author_id'];
        $category_id = $formData['category'];
        $tags = $formData['tags'] ?? [];

        $wikiId = $this->wikiModel->addWiki($title, $content, $author_id, $category_id, $tags);

        if ($wikiId) {
            header('Location: ../../View/author/mywiki.php');
        }
    }

    public function show($id)
    {
        $wiki = $this->wikiModel->getWikiById($id);
        if ($wiki) {
            $wiki['tags'] = $this->wikiModel->getTagsByWikiId($id);
        }
        return $wiki;
    }

    public function update($formData)
    {
        $wikiId = $formData['id'];
        $title = $formData['title'];
        $content = $formData['content'];
        $category_id = $formData['category'];
        $tags = $formData['tags'] ?? [];

        $this->wikiModel->updateWiki($wikiId, $title, $content, $category_id);
        $this->wikiModel->updateWikiTags($wikiId, $tags);

    }


    public function delete($id)
    {
        $this->wikiModel->deleteWiki($id);
    }

    public function getWikisByAuthor($authorId)
    {
        return $this->wikiModel->getWikisByAuthor($authorId);
    }

    public function getAllWikis()
    {
        return $this->wikiModel->getAllWikis();
    }
    public function archiveWiki($id) {
        $this->wikiModel->archiveWiki($id);
        header('Location: ../../View/admin/archive_wiki.php');
        exit;
    }
    public function unarchiveWiki($id) {
        $this->wikiModel->unarchiveWiki($id);
        header('Location: ../../View/admin/archive_wiki.php');
        exit;
    }

    public function getRecentWikis()
    {
        return $this->wikiModel->getRecentWikis();
    }

    public function getWikiDetails($wikiId)
    {
        return $this->wikiModel->getWikiDetails($wikiId);
    }

    public function searchWikis($searchTerm) {
        return $this->wikiModel->searchWikis($searchTerm);
    }

    public function getStatistics() {
        $totalWikis = $this->wikiModel->getTotalWikiCount();
        $totalCategories = $this->categoryModel->getTotalCategoryCount();
        $totalTags = $this->tagModel->getTotalTagCount();

        return [
            'totalWikis' => $totalWikis,
            'totalCategories' => $totalCategories,
            'totalTags' => $totalTags,
        ];
    }
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $wikiController = new WikiController();

    if (isset($_POST['submit_new_wiki'])) {
        $wikiController->store($_POST);
    }
    if (isset($_POST['submit_delete_wiki'])) {
        $wikiId = $_POST['id'] ?? null;
        if ($wikiId) {
            $wikiController->delete($wikiId);
            header('Location: ../../View/author/mywiki.php');
            exit;
        }
    }
    if (isset($_POST['update_wiki'])) {
        $wikiId = $_POST['id'] ?? null;
        if ($wikiId) {
            $wikiController->update($_POST);
            header('Location: ../../View/author/mywiki.php');
            exit;
        }
    }
    if (isset($_POST['archive_wiki'])) {
        $wikiId = $_POST['id'] ?? null;
        if ($wikiId) {
            $wikiController->archiveWiki($wikiId);
        }
    }

    if (isset($_POST['unarchive_wiki'])) {
        $wikiId = $_POST['id'] ?? null;
        if ($wikiId) {
            $wikiController->unarchiveWiki($wikiId);
        }
    }
}

