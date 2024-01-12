<?php
session_start();

require_once '../../app/Config/DbConnection.php';
require_once '../../app/Controller/WikiController.php';
use MyApp\Controller\WikiController;

$wikiId = $_GET['id'] ?? null;
$wikiController = new WikiController();

if (!$wikiId) {
    echo "Wiki ID is missing.";
    exit;
}

$wikiDetails = $wikiController->getWikiDetails($wikiId);
if (!$wikiDetails) {
    echo "Wiki not found.";
    exit;
}

if ($wikiDetails["is_archived"]) {
    echo "Sorry The Wiki is archived.";
    exit;
}
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
    <link href="../../Assets/css/authstyle.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="../../index.php">Wiki</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav mx-auto">
                <a class="nav-link" href="../../index.php">Home</a>
                <a class="nav-link" href="../author/author_dashboard.php">Author dashboard</a>
                <a class="nav-link" href="../admin/admin_dashboard.php">Admin dashboard</a>
            </div>
        </div>
    </div>
</nav>


<div class="container mt-4">
    <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
        <div class="col-md-6 px-0">
            <h1 class="display-4"><?= htmlspecialchars($wikiDetails['title']) ?></h1>
            <p class="lead my-3"><i class="fas fa-folder"></i>  Category: <?= htmlspecialchars($wikiDetails['category']) ?></p>
            <p class="lead mb-0"> <i class="fas fa-user"></i>  <?= htmlspecialchars($wikiDetails['author']) ?></p>
        </div>
    </div>

    <div class="mb-3">
        <strong>Tags:</strong>
        <?php if (isset($wikiDetails['tags'])): ?>
            <?php foreach (explode(', ', $wikiDetails['tags']) as $tag): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        <?php else: ?>
            No Tags
        <?php endif; ?>
    </div>

    <div class="content mt-3">
        <p class="lead"><?= nl2br(htmlspecialchars($wikiDetails['content'])) ?></p>
    </div>
</div>

<?php include '../templates/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
