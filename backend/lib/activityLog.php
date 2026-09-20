<?php

function logActivity(?int $userId, string $action, array $detail = []): void
{
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt = db()->prepare(
            'INSERT INTO activityLog (userId, action, ip, userAgent, detail)
             VALUES (:userId, :action, :ip, :userAgent, :detail)'
        );

        $stmt->execute([
            'userId'    => $userId,
            'action'    => $action,
            'ip'        => $ip,
            'userAgent' => $userAgent ? substr($userAgent, 0, 255) : null,
            'detail'    => empty($detail) ? null : json_encode($detail, JSON_UNESCAPED_UNICODE),
        ]);
    } catch (Throwable $e) {
        error_log('activityLog error: ' . $e->getMessage());
    }
}
