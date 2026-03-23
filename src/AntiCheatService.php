<?php

declare(strict_types=1);

final class AntiCheatService
{
    public function __construct(
        private PDO $pdo,
        private array $config
    ) {
    }

    public function checkPreVisit(string $linkCode, string $fingerprint, string $ip): array
    {
        if ($this->isSuspiciousProxy()) {
            return [false, 'Proxy/VPN terdeteksi. Nonaktifkan lalu coba lagi.'];
        }

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM visit_logs WHERE ip_address = :ip AND created_at >= (NOW() - INTERVAL 1 HOUR)'
        );
        $stmt->execute(['ip' => $ip]);

        if ((int) $stmt->fetchColumn() > (int) $this->config['security']['max_clicks_per_hour']) {
            return [false, 'Terlalu banyak klik dari IP yang sama dalam 1 jam terakhir.'];
        }

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM claim_logs WHERE link_code = :code AND fingerprint = :fingerprint
            AND TIMESTAMPDIFF(SECOND, created_at, NOW()) <= :cooldown'
        );
        $stmt->bindValue('code', $linkCode);
        $stmt->bindValue('fingerprint', $fingerprint);
        $stmt->bindValue('cooldown', (int) $this->config['security']['cooldown_per_link_seconds'], PDO::PARAM_INT);
        $stmt->execute();

        if ((int) $stmt->fetchColumn() > 0) {
            return [false, 'Kamu sudah mengklaim shortlink ini. Tunggu masa cooldown selesai.'];
        }

        return [true, null];
    }

    public function checkClaimAttempt(string $fingerprint): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM abuse_logs WHERE fingerprint = :fingerprint AND created_at >= (NOW() - INTERVAL 1 DAY)'
        );
        $stmt->execute(['fingerprint' => $fingerprint]);

        if ((int) $stmt->fetchColumn() >= (int) $this->config['security']['max_failed_attempts_per_day']) {
            return [false, 'Terlalu banyak percobaan gagal. Coba lagi besok.'];
        }

        return [true, null];
    }

    public function registerVisit(string $linkCode, string $fingerprint, string $ip): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO visit_logs (link_code, fingerprint, ip_address, user_agent)
             VALUES (:link_code, :fingerprint, :ip_address, :user_agent)'
        );

        $stmt->execute([
            'link_code' => $linkCode,
            'fingerprint' => $fingerprint,
            'ip_address' => $ip,
            'user_agent' => clientUserAgent(),
        ]);
    }

    public function registerClaim(string $linkCode, string $fingerprint, string $ip, string $uid): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO claim_logs (link_code, fingerprint, ip_address, user_agent, uid)
             VALUES (:link_code, :fingerprint, :ip_address, :user_agent, :uid)'
        );

        $stmt->execute([
            'link_code' => $linkCode,
            'fingerprint' => $fingerprint,
            'ip_address' => $ip,
            'user_agent' => clientUserAgent(),
            'uid' => $uid,
        ]);
    }

    public function registerAbuse(string $reason, string $fingerprint, string $ip): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO abuse_logs (reason, fingerprint, ip_address, user_agent)
             VALUES (:reason, :fingerprint, :ip_address, :user_agent)'
        );

        $stmt->execute([
            'reason' => $reason,
            'fingerprint' => $fingerprint,
            'ip_address' => $ip,
            'user_agent' => clientUserAgent(),
        ]);
    }

    private function isSuspiciousProxy(): bool
    {
        $suspiciousHeaders = [
            'HTTP_VIA',
            'HTTP_X_FORWARDED_HOST',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_PROXY_ID',
        ];

        foreach ($suspiciousHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                return true;
            }
        }

        return false;
    }
}
