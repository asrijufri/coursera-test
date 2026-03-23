<?php

declare(strict_types=1);

require __DIR__ . '/src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminSecret = trim((string) ($_POST['admin_secret'] ?? ''));

    if (!hash_equals((string) $config['app']['admin_secret'], $adminSecret)) {
        http_response_code(403);
        echo 'Admin secret salah.';
        exit;
    }

    $title = trim((string) ($_POST['title'] ?? ''));
    $destinationUrl = trim((string) ($_POST['destination_url'] ?? ''));
    $rewardAmount = (float) ($_POST['reward_amount'] ?? 0);
    $task1Url = trim((string) ($_POST['task_1_url'] ?? ''));
    $task2Url = trim((string) ($_POST['task_2_url'] ?? ''));

    if (
        $title === ''
        || !filter_var($destinationUrl, FILTER_VALIDATE_URL)
        || !filter_var($task1Url, FILTER_VALIDATE_URL)
        || !filter_var($task2Url, FILTER_VALIDATE_URL)
        || $rewardAmount <= 0
    ) {
        http_response_code(422);
        echo 'Input tidak valid.';
        exit;
    }

    $code = randomCode(8);

    $stmt = $pdo->prepare(
        'INSERT INTO links (link_code, title, destination_url, reward_amount, task_1_url, task_2_url, status)
         VALUES (:code, :title, :destination_url, :reward_amount, :task_1_url, :task_2_url, 1)'
    );
    $stmt->execute([
        'code' => $code,
        'title' => $title,
        'destination_url' => $destinationUrl,
        'reward_amount' => $rewardAmount,
        'task_1_url' => $task1Url,
        'task_2_url' => $task2Url,
    ]);

    echo 'Link berhasil dibuat: ' . htmlspecialchars($config['app']['base_url'] . '/go.php?c=' . $code . '&uid={UID}', ENT_QUOTES, 'UTF-8');
    exit;
}

render('create_link');
