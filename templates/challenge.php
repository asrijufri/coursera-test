<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Verifikasi Pengunjung</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 620px; margin: 24px auto; padding: 0 16px; }
        .hidden-field { display: none; }
        .box { border: 1px solid #ddd; border-radius: 8px; padding: 14px; margin-bottom: 12px; }
        .ok { color: #0b7a28; }
        .warn { color: #9a6400; }
        .task-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Tunggu <span id="timer"><?= (int) $countdown ?></span> detik</h2>
    <p>Selesaikan 2 task smartlink sebelum klaim reward.</p>
</div>

<div class="box">
    <h3>Task 1 Smartlink</h3>
    <?php if ($task1Completed): ?>
        <p class="ok">✅ Task 1 sudah selesai.</p>
    <?php elseif ($task1Openable): ?>
        <p class="warn">Belum selesai.</p>
        <div class="task-actions">
            <a href="/open_task.php?step=1&token=<?= htmlspecialchars($challengeToken, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Buka Task 1</a>
            <form method="post" action="/complete_task.php">
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="token" value="<?= htmlspecialchars($challengeToken, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit">Saya sudah menyelesaikan Task 1</button>
            </form>
        </div>
    <?php else: ?>
        <p>URL Task 1 belum diset admin.</p>
    <?php endif; ?>
</div>

<div class="box">
    <h3>Task 2 Smartlink</h3>
    <?php if ($task2Completed): ?>
        <p class="ok">✅ Task 2 sudah selesai.</p>
    <?php elseif ($task2Openable): ?>
        <p class="warn">Belum selesai.</p>
        <div class="task-actions">
            <a href="/open_task.php?step=2&token=<?= htmlspecialchars($challengeToken, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Buka Task 2</a>
            <form method="post" action="/complete_task.php">
                <input type="hidden" name="step" value="2">
                <input type="hidden" name="token" value="<?= htmlspecialchars($challengeToken, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit">Saya sudah menyelesaikan Task 2</button>
            </form>
        </div>
    <?php else: ?>
        <p>Selesaikan Task 1 terlebih dahulu.</p>
    <?php endif; ?>
</div>

<div class="box">
    <h3>Final Verifikasi</h3>
    <form id="claim-form" method="post" action="/claim.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($challengeToken, ENT_QUOTES, 'UTF-8') ?>">

        <label>Berapa hasil <?= (int) $questionA ?> + <?= (int) $questionB ?>?</label><br>
        <input type="number" name="answer" required><br><br>

        <div class="hidden-field">
            <label>Website</label>
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <button id="submit-btn" disabled>Klaim reward & lanjutkan</button>
    </form>
</div>

<script>
    let remaining = <?= (int) $countdown ?>;
    const timerEl = document.getElementById('timer');
    const submitBtn = document.getElementById('submit-btn');

    const updateButton = () => {
        if (remaining <= 0) {
            submitBtn.disabled = false;
        }
    };

    updateButton();

    const tick = setInterval(() => {
        remaining--;
        timerEl.textContent = Math.max(remaining, 0);

        if (remaining <= 0) {
            clearInterval(tick);
            updateButton();
        }
    }, 1000);
</script>
</body>
</html>
