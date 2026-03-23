CREATE TABLE links (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    link_code VARCHAR(16) NOT NULL UNIQUE,
    title VARCHAR(120) NOT NULL,
    destination_url TEXT NOT NULL,
    reward_amount DECIMAL(18,8) NOT NULL DEFAULT 0,
    task_1_url TEXT NOT NULL,
    task_2_url TEXT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE visit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    link_code VARCHAR(16) NOT NULL,
    fingerprint CHAR(64) NOT NULL,
    ip_address VARCHAR(64) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_visit_ip_created (ip_address, created_at),
    INDEX idx_visit_fingerprint_created (fingerprint, created_at)
);

CREATE TABLE claim_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    link_code VARCHAR(16) NOT NULL,
    uid VARCHAR(128) NOT NULL,
    fingerprint CHAR(64) NOT NULL,
    ip_address VARCHAR(64) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_link_uid (link_code, uid),
    INDEX idx_claim_fingerprint_created (fingerprint, created_at)
);

CREATE TABLE abuse_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reason VARCHAR(255) NOT NULL,
    fingerprint CHAR(64) NOT NULL,
    ip_address VARCHAR(64) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_abuse_fingerprint_created (fingerprint, created_at)
);
