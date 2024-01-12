<?php
require_once '../../app/Config/DbConnection.php';
require_once '../../app/Controller/WikiController.php';

use MyApp\Controller\WikiController;

$wikiController = new WikiController();

$searchQuery = $_GET['search'] ?? '';

if ($searchQuery === '') {
    $wikis = $wikiController->getRecentWikis();
} else {
    $wikis = $wikiController->searchWikis($searchQuery);
}

$response = [];
foreach ($wikis as $wiki) {
    $response[] = [
        'id' => $wiki['id'],
        'title' => htmlspecialchars($wiki['title']),
        'content' => htmlspecialchars(substr($wiki['content'], 0, 150)) . '...',
        'link' => "View/User/wiki.php?id=" . $wiki['id']
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
