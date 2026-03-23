<?php

declare(strict_types=1);

final class ViefaucetClient
{
    public function __construct(private array $config)
    {
    }

    public function sendReward(string $uid, string $reference, float $amount): array
    {
        $payload = [
            'api_key' => $this->config['viefaucet']['api_key'],
            'uid' => $uid,
            'reference' => $reference,
            'amount' => $amount,
        ];

        $ch = curl_init($this->config['viefaucet']['callback_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_TIMEOUT => (int) $this->config['viefaucet']['timeout_seconds'],
        ]);

        $body = curl_exec($ch);
        $errNo = curl_errno($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errNo !== 0) {
            return [false, 'Gagal koneksi ke VieFaucet callback'];
        }

        if ($status < 200 || $status >= 300) {
            return [false, 'VieFaucet callback mengembalikan HTTP ' . $status];
        }

        return [true, (string) $body];
    }
}
