<?php

declare(strict_types=1);

function clientIp(): string
{
    $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $value = trim(explode(',', (string) $_SERVER[$key])[0]);
            if (filter_var($value, FILTER_VALIDATE_IP)) {
                return $value;
            }
        }
    }

    return '0.0.0.0';
}

function clientUserAgent(): string
{
    return substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'), 0, 255);
}

function fingerprint(): string
{
    return hash('sha256', clientIp() . '|' . clientUserAgent());
}

function render(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../templates/' . $template . '.php';
}

function randomCode(int $length = 8): string
{
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }

    return $result;
}
