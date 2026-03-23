<?php

declare(strict_types=1);

require __DIR__ . '/src/bootstrap.php';

$antiCheat = new AntiCheatService($pdo, $config);
$fingerprint = fingerprint();
$ip = clientIp();

$challenge = $_SESSION['challenge'] ?? null;
if (!$challenge) {
    http_response_code(400);
    echo 'Sesi challenge tidak ditemukan.';
    exit;
}

$step = (int) ($_GET['step'] ?? 0);
$token = trim((string) ($_GET['token'] ?? ''));

if (!hash_equals((string) $challenge['token'], $token)) {
    $antiCheat->registerAbuse('invalid_open_task_token', $fingerprint, $ip);
    http_response_code(403);
    echo 'Token task tidak valid.';
    exit;
}

if (!hash_equals((string) $challenge['fingerprint'], $fingerprint)) {
    $antiCheat->registerAbuse('open_task_fingerprint_mismatch', $fingerprint, $ip);
    http_response_code(403);
    echo 'Fingerprint berubah.';
    exit;
}

if ($step < 1 || $step > 2) {
    http_response_code(422);
    echo 'Step task tidak valid.';
    exit;
}

if (($step === 2) && empty($challenge['tasks'][0]['completed_at'])) {
    $antiCheat->registerAbuse('open_task2_before_task1', $fingerprint, $ip);
    http_response_code(403);
    echo 'Selesaikan task 1 terlebih dahulu.';
    exit;
}

$taskIdx = $step - 1;
$task = $challenge['tasks'][$taskIdx] ?? null;
if (!$task || empty($task['url'])) {
    http_response_code(404);
    echo 'Task tidak tersedia.';
    exit;
}

$_SESSION['challenge']['tasks'][$taskIdx]['opened_at'] = time();
header('Location: ' . $task['url']);
exit;
