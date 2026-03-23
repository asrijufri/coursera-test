<?php

declare(strict_types=1);

require __DIR__ . '/src/bootstrap.php';

$stmt = $pdo->query('SELECT link_code, title, reward_amount FROM links WHERE status = 1 ORDER BY id DESC LIMIT 50');
$links = $stmt->fetchAll();

render('home', [
    'links' => $links,
    'baseUrl' => $config['app']['base_url'],
]);
