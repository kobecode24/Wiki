<?php

namespace MyApp\Controller;
require_once __DIR__ . "/../../vendor/autoload.php";

use MyApp\Model\Tag;

class TagController {
    private $tagModel;

    public function __construct() {
        $this->tagModel = new Tag();
    }

    public function listTags() {
        $tags = $this->tagModel->showAll();
        return $tags;
    }

    public function store($formData) {
        if (isset($formData['name'])) {
            $this->tagModel->add($formData['name']);
        }
        header('Location: ../../View/admin/tags.php');
        exit;
    }

    public function update($formData) {
        if (isset($formData['id']) && isset($formData['name'])) {
            $tagId = $formData['id'];
            $tagName = $formData['name'];
            $success = $this->tagModel->updateTag($tagName, $tagId);
            if ($success) {
                header('Location: ../../View/admin/tags.php');
                exit;
            }
        }
    }

    public function delete($formData) {
        if (isset($formData['id'])) {
            $tagId = $formData['id'];
            $success = $this->tagModel->deleteTag($tagId);
            if ($success) {
                header('Location: ../../View/admin/tags.php');
                exit;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $tagController = new TagController();

    if (isset($_POST['add'])) {
        $tagController->store($_POST);
    } elseif (isset($_POST['update'])) {
        $tagController->update($_POST);
    } elseif (isset($_POST['delete'])) {
        $tagController->delete($_POST);
    }
}
