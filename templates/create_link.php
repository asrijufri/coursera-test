<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Buat Link Baru</title>
</head>
<body>
<h1>Buat Shortlink Baru</h1>
<form method="post">
    <label>Admin Secret</label><br>
    <input type="password" name="admin_secret" required><br><br>

    <label>Judul Link</label><br>
    <input type="text" name="title" required><br><br>

    <label>URL Tujuan Akhir</label><br>
    <input type="url" name="destination_url" required><br><br>

    <label>Task 1 Smartlink URL</label><br>
    <input type="url" name="task_1_url" required><br><br>

    <label>Task 2 Smartlink URL</label><br>
    <input type="url" name="task_2_url" required><br><br>

    <label>Reward Amount</label><br>
    <input type="number" step="0.0001" min="0.0001" name="reward_amount" required><br><br>

    <button type="submit">Simpan</button>
</form>
</body>
</html>
