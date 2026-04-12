<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/AddressRepository.php';

$config = require __DIR__ . '/../../config.php';

try {
    $pdo = Database::connect($config['db']);
    $repository = new AddressRepository($pdo);

    $query = isset($_GET['q']) ? (string)$_GET['q'] : '';
    $result = $repository->searchByQuery($query, 10);

    echo json_encode(
        ['items' => $result],
        JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(
        ['error' => 'Server error', 'details' => $e->getMessage()],
        JSON_UNESCAPED_UNICODE
    );
}
