<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>AdLink Lite Anti-Cheat</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 780px; margin: 24px auto; padding: 0 16px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 14px; margin: 10px 0; }
    </style>
</head>
<body>
<h1>AdLink Lite (VieFaucet Ready)</h1>
<p>Contoh akses shortlink: <code><?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/go.php?c=KODELINK&uid=USER123</code></p>

<?php if (empty($links)): ?>
    <p>Belum ada link aktif.</p>
<?php else: ?>
    <?php foreach ($links as $link): ?>
        <div class="card">
            <strong><?= htmlspecialchars($link['title'], ENT_QUOTES, 'UTF-8') ?></strong><br>
            Reward: <?= htmlspecialchars((string) $link['reward_amount'], ENT_QUOTES, 'UTF-8') ?> credits<br>
            URL: <code><?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/go.php?c=<?= htmlspecialchars($link['link_code'], ENT_QUOTES, 'UTF-8') ?>&uid={UID}</code>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
