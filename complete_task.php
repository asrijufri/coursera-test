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

$step = (int) ($_POST['step'] ?? 0);
$token = trim((string) ($_POST['token'] ?? ''));

if ($step < 1 || $step > 2) {
    $antiCheat->registerAbuse('invalid_task_step', $fingerprint, $ip);
    http_response_code(422);
    echo 'Step task tidak valid.';
    exit;
}

if (!hash_equals((string) $challenge['token'], $token)) {
    $antiCheat->registerAbuse('invalid_task_token', $fingerprint, $ip);
    http_response_code(403);
    echo 'Token task tidak valid.';
    exit;
}

if (!hash_equals((string) $challenge['fingerprint'], $fingerprint)) {
    $antiCheat->registerAbuse('task_fingerprint_mismatch', $fingerprint, $ip);
    http_response_code(403);
    echo 'Fingerprint berubah.';
    exit;
}

if (($step === 2) && empty($challenge['tasks'][1]['completed_at'])) {
    $antiCheat->registerAbuse('task2_before_task1', $fingerprint, $ip);
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

$openedAt = (int) ($challenge['tasks'][$taskIdx]['opened_at'] ?? 0);
if ($openedAt === 0) {
    $antiCheat->registerAbuse('task_complete_without_open', $fingerprint, $ip);
    http_response_code(403);
    echo 'Buka smartlink task dulu.';
    exit;
}

$minimum = (int) $config['security']['task_min_seconds'];
if ((time() - $openedAt) < $minimum) {
    $antiCheat->registerAbuse('task_completed_too_fast', $fingerprint, $ip);
    http_response_code(403);
    echo 'Task terlalu cepat diselesaikan. Tunggu sebentar.';
    exit;
}

$_SESSION['challenge']['tasks'][$taskIdx]['completed_at'] = time();
header('Location: /go.php?c=' . urlencode((string) $challenge['link_code']) . '&uid=' . urlencode((string) $challenge['uid']) . '&resume=1');
exit;
