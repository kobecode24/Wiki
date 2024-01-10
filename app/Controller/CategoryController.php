<?php

namespace MyApp\Controller;
require_once __DIR__ . "/../../vendor/autoload.php";

use MyApp\Model\Category;
class CategoryController
{
    private $categoryModel;
    public function __construct()
    {
        $this->categoryModel = new Category();
    }
public function store($formData)
{
    $this->categoryModel->add($formData["name"]);
    header('Location: ../../View/admin/categories.php');
    exit;
}

    public function update($formData)
    {
        if (isset($formData['id']) && isset($formData['name'])) {
            $categoryId = $formData['id'];
            $categoryName = $formData['name'];
            $success = $this->categoryModel->updateCategory($categoryName,$categoryId);
            if ($success){
            header('Location: ../../View/admin/categories.php');
                exit;
            }
        }
    }

    public function delete($formData)
    {
        if (isset($formData["id"])){
            $categoryId = $formData['id'];
            $success= $this->categoryModel->deleteCategory($categoryId);
            if ($success){
                header('Location: ../../View/admin/categories.php');
                exit;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $categoryController = new CategoryController();

    if (isset($_POST['add'])) {
        $categoryController->store($_POST);
    } elseif (isset($_POST['update'])) {
        $categoryController->update($_POST);
    } elseif (isset($_POST['delete'])) {
        $categoryController->delete($_POST);
    }
}



