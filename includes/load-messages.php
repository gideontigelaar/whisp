<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";

$conversationUserId = $user_id ?? ($_GET['user_id'] ?? null);
$afterMessageId = (int) ($_GET['after_id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM messages WHERE ((sender_id = :sender_id AND recipient_id = :recipient_id) OR (sender_id = :recipient_id AND recipient_id = :sender_id)) AND message_id > :after_id ORDER BY message_id ASC");
$stmt->execute(['sender_id' => $_SESSION['user_id'], 'recipient_id' => $conversationUserId, 'after_id' => $afterMessageId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    $isOwnMessage = $message['sender_id'] == $_SESSION['user_id'];
    $content = $message['content'];
    $messageCreatedAt = date('H:i', strtotime($message['created_at']));

    include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/messages.php";
}
?>