<?php

declare(strict_types=1);

require __DIR__ . '/src/bootstrap.php';

$linkCode = strtoupper(trim((string) ($_GET['c'] ?? '')));
$uid = trim((string) ($_GET['uid'] ?? ''));
$resume = (int) ($_GET['resume'] ?? 0) === 1;

if ($linkCode === '' || $uid === '') {
    http_response_code(400);
    echo 'Parameter c dan uid wajib diisi.';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM links WHERE link_code = :code AND status = 1 LIMIT 1');
$stmt->execute(['code' => $linkCode]);
$link = $stmt->fetch();

if (!$link) {
    http_response_code(404);
    echo 'Link tidak ditemukan.';
    exit;
}

$antiCheat = new AntiCheatService($pdo, $config);
$fingerprint = fingerprint();
$ip = clientIp();

$currentChallenge = $_SESSION['challenge'] ?? null;
$canResume = $resume
    && is_array($currentChallenge)
    && ($currentChallenge['link_code'] ?? '') === $linkCode
    && ($currentChallenge['uid'] ?? '') === $uid
    && ($currentChallenge['fingerprint'] ?? '') === $fingerprint;

if (!$canResume) {
    [$allowed, $reason] = $antiCheat->checkPreVisit($linkCode, $fingerprint, $ip);
    if (!$allowed) {
        $antiCheat->registerAbuse('pre_visit_block:' . $reason, $fingerprint, $ip);
        http_response_code(429);
        echo htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
        exit;
    }

    $antiCheat->registerVisit($linkCode, $fingerprint, $ip);

    $questionA = random_int(1, 9);
    $questionB = random_int(1, 9);
    $challengeToken = bin2hex(random_bytes(16));

    $_SESSION['challenge'] = [
        'token' => $challengeToken,
        'uid' => $uid,
        'link_code' => $linkCode,
        'fingerprint' => $fingerprint,
        'question_a' => $questionA,
        'question_b' => $questionB,
        'answer' => $questionA + $questionB,
        'created_at' => time(),
        'redirect_url' => $link['destination_url'],
        'reward_amount' => (float) $link['reward_amount'],
        'tasks' => [
            [
                'url' => (string) $link['task_1_url'],
                'opened_at' => 0,
                'completed_at' => 0,
            ],
            [
                'url' => (string) $link['task_2_url'],
                'opened_at' => 0,
                'completed_at' => 0,
            ],
        ],
    ];
}

$challenge = $_SESSION['challenge'];

render('challenge', [
    'challengeToken' => $challenge['token'],
    'questionA' => (int) $challenge['question_a'],
    'questionB' => (int) $challenge['question_b'],
    'countdown' => max(0, (int) $config['app']['countdown_seconds'] - (time() - (int) $challenge['created_at'])),
    'task1Completed' => !empty($challenge['tasks'][0]['completed_at']),
    'task2Completed' => !empty($challenge['tasks'][1]['completed_at']),
    'task1Openable' => !empty($challenge['tasks'][0]['url']),
    'task2Openable' => !empty($challenge['tasks'][1]['url']) && !empty($challenge['tasks'][0]['completed_at']),
]);
