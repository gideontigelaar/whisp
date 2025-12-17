<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";

function sendError($message) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => $message]);
    exit();
}

function sendSuccess() {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit();
}

$content = trim($_POST['content'] ?? '');
$recipientId = $_POST['recipient_id'] ?? '';
$senderId = $_SESSION['user_id'];

if (empty($content)) {
    sendError('Content is required');
}

if (mb_strlen($content) > 250) {
    sendError('Message is too long.');
}

$stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $recipientId]);
$recipient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipient || $recipient['user_id'] === $senderId) {
    sendError('Recipient not found');
}

$recipientId = $recipient['user_id'];
$content = htmlspecialchars($content);

$stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, content) VALUES (:sender_id, :recipient_id, :content)");
$stmt->execute(['sender_id' => $senderId, 'recipient_id' => $recipientId, 'content' => $content]);

sendSuccess();
?>