<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class PasswordResetRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function createToken(string $email): string
    {
        $email = strtolower(trim($email));
        $token = bin2hex(random_bytes(32));

        // Invalidate old tokens for this email
        $deleteStmt = $this->db->prepare('DELETE FROM `password_resets` WHERE `email` = :email');
        $deleteStmt->execute(['email' => $email]);

        // Insert new token with 1 hour expiration
        $insertStmt = $this->db->prepare('
            INSERT INTO `password_resets` (`email`, `token`, `created_at`, `expires_at`)
            VALUES (:email, :token, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))
        ');
        $insertStmt->execute([
            'email' => $email,
            'token' => $token,
        ]);

        return $token;
    }

    public function findByToken(string $token): ?array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM `password_resets` 
            WHERE `token` = :token AND `expires_at` > NOW() 
            LIMIT 1
        ');
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function deleteToken(string $token): void
    {
        $stmt = $this->db->prepare('DELETE FROM `password_resets` WHERE `token` = :token');
        $stmt->execute(['token' => $token]);
    }

    public function deleteExpired(): void
    {
        $this->db->exec('DELETE FROM `password_resets` WHERE `expires_at` <= NOW()');
    }
}
