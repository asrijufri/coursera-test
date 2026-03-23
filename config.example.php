<?php

declare(strict_types=1);

return [
    'app' => [
        'base_url' => 'http://localhost:8000',
        'admin_secret' => 'ganti_dengan_secret_admin',
        'countdown_seconds' => 10,
        'session_ttl' => 300,
    ],
    'database' => [
        'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=adlink_lite;charset=utf8mb4',
        'username' => 'root',
        'password' => '',
    ],
    'security' => [
        'max_clicks_per_hour' => 30,
        'cooldown_per_link_seconds' => 86400,
        'max_failed_attempts_per_day' => 10,
        'task_min_seconds' => 15,
    ],
    'viefaucet' => [
        'callback_url' => 'https://your-viefaucet-site.com/api/shortlinks/callback',
        'api_key' => 'isi_api_key_viefaucet',
        'timeout_seconds' => 8,
    ],
];
