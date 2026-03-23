<?php

declare(strict_types=1);

session_start();

$configPath = __DIR__ . '/../config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo 'File config.php belum ada. Copy dari config.example.php';
    exit;
}

$config = require $configPath;

$pdo = new PDO(
    $config['database']['dsn'],
    $config['database']['username'],
    $config['database']['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/ViefaucetClient.php';
require_once __DIR__ . '/AntiCheatService.php';
