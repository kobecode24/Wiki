<?php
session_start();

require_once 'app/Config/DbConnection.php';
require_once 'app/Controller/WikiController.php';
require_once 'app/Controller/CategoryController.php';
use MyApp\Controller\CategoryController;
use MyApp\Controller\WikiController;

$wikiController = new WikiController();
$categoryController = new CategoryController();
$recentWikis = $wikiController->getRecentWikis();
$recentCategories = $categoryController->getRecentCategories();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiki Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
    <link href="Assets/css/authstyle.css" rel="stylesheet">
</head>
<body>
<?php include 'View/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <h2>Recent Categories</h2>
            <div class="list-group">
                <?php foreach ($recentCategories as $category): ?>
                    <a href="#" class="list-group-item list-group-item-action">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-8">
            <h1>Welcome to Wiki</h1>
            <p>Your go-to resource for shared knowledge.</p>

            <form id="searchForm" class="d-flex my-4">
                <input id="searchInput" class="form-control me-2" type="search" placeholder="Search Wikis" aria-label="Search">
            </form>


            <h2>Recent Wikis</h2>

            <div id="resultsArea">

            </div>

        </div>
    </div>
</div>

<?php include 'View/templates/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="Assets/js/search.js"></script>
</body>
</html>
