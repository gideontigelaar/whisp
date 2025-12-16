<?php
function isRateLimited($pdo, $action, $intervalSeconds) {
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    $stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(MICROSECOND, last_attempt_at, CURRENT_TIMESTAMP(3)) FROM rate_limits WHERE ip_address = :ip_address AND action = :action");
    $stmt->execute(['ip_address' => $ipAddress, 'action' => $action]);
    $elapsedMicroseconds = $stmt->fetchColumn();

    if ($elapsedMicroseconds !== false && $elapsedMicroseconds < $intervalSeconds * 1000000) {
        return true;
    }

    $stmt = $pdo->prepare("INSERT INTO rate_limits (ip_address, action) VALUES (:ip_address, :action) ON DUPLICATE KEY UPDATE last_attempt_at = CURRENT_TIMESTAMP(3)");
    $stmt->execute(['ip_address' => $ipAddress, 'action' => $action]);

    return false;
}
?>