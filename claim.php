<?php

declare(strict_types=1);

require __DIR__ . '/src/bootstrap.php';

$antiCheat = new AntiCheatService($pdo, $config);
$fingerprint = fingerprint();
$ip = clientIp();

[$attemptAllowed, $attemptReason] = $antiCheat->checkClaimAttempt($fingerprint);
if (!$attemptAllowed) {
    http_response_code(429);
    echo htmlspecialchars($attemptReason, ENT_QUOTES, 'UTF-8');
    exit;
}

$challenge = $_SESSION['challenge'] ?? null;
if (!$challenge) {
    $antiCheat->registerAbuse('missing_session', $fingerprint, $ip);
    http_response_code(400);
    echo 'Sesi challenge tidak ditemukan.';
    exit;
}

$token = trim((string) ($_POST['token'] ?? ''));
$answer = (int) ($_POST['answer'] ?? -1);
$honeypot = trim((string) ($_POST['website'] ?? ''));

if ($honeypot !== '') {
    $antiCheat->registerAbuse('honeypot_filled', $fingerprint, $ip);
    http_response_code(403);
    echo 'Bot terdeteksi.';
    exit;
}

if (!hash_equals((string) $challenge['token'], $token)) {
    $antiCheat->registerAbuse('invalid_token', $fingerprint, $ip);
    http_response_code(403);
    echo 'Token challenge tidak valid.';
    exit;
}

if (!hash_equals((string) $challenge['fingerprint'], $fingerprint)) {
    $antiCheat->registerAbuse('fingerprint_mismatch', $fingerprint, $ip);
    http_response_code(403);
    echo 'Perangkat/IP berubah saat proses berlangsung.';
    exit;
}

if ((time() - (int) $challenge['created_at']) < (int) $config['app']['countdown_seconds']) {
    $antiCheat->registerAbuse('countdown_bypass', $fingerprint, $ip);
    http_response_code(403);
    echo 'Countdown belum selesai.';
    exit;
}

if ((time() - (int) $challenge['created_at']) > (int) $config['app']['session_ttl']) {
    $antiCheat->registerAbuse('session_expired', $fingerprint, $ip);
    http_response_code(403);
    echo 'Sesi kadaluarsa. Ulangi dari awal.';
    exit;
}


if (empty($challenge['tasks'][0]['completed_at']) || empty($challenge['tasks'][1]['completed_at'])) {
    $antiCheat->registerAbuse('claim_before_two_tasks_completed', $fingerprint, $ip);
    http_response_code(403);
    echo 'Selesaikan 2 task smartlink terlebih dahulu.';
    exit;
}

if ($answer !== (int) $challenge['answer']) {
    $antiCheat->registerAbuse('wrong_math_answer', $fingerprint, $ip);
    http_response_code(403);
    echo 'Jawaban verifikasi salah.';
    exit;
}

$viefaucet = new ViefaucetClient($config);
$reference = $challenge['link_code'] . '-' . time();
[$ok, $resp] = $viefaucet->sendReward((string) $challenge['uid'], $reference, (float) $challenge['reward_amount']);

if (!$ok) {
    $antiCheat->registerAbuse('viefaucet_callback_failed:' . $resp, $fingerprint, $ip);
    http_response_code(502);
    echo 'Reward gagal dikirim ke VieFaucet: ' . htmlspecialchars($resp, ENT_QUOTES, 'UTF-8');
    exit;
}

$antiCheat->registerClaim((string) $challenge['link_code'], $fingerprint, $ip, (string) $challenge['uid']);
unset($_SESSION['challenge']);

header('Location: ' . $challenge['redirect_url']);
exit;
