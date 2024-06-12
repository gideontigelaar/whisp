<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/pdo-connect.php";

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

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($_POST['password'], $user['password'])) {
    sendError('Password is not correct.');
}

if ($_POST['confirm_deletion'] !== 'DELETE') {
    sendError('Confirmation is not correct.');
}

function deleteReplies($pdo, $post_id) {
    $stmt = $pdo->prepare("SELECT post_id FROM posts WHERE reply_to_post_id = :post_id");
    $stmt->execute(['post_id' => $post_id]);
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($replies as $reply) {
        deleteReplies($pdo, $reply['post_id']);
        $stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = :post_id");
        $stmt->execute(['post_id' => $reply['post_id']]);
    }
}

$stmt = $pdo->prepare("SELECT post_id FROM posts WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    deleteReplies($pdo, $post['post_id']);

    $stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post['post_id']]);
}

$stmt = $pdo->prepare("DELETE FROM sessions WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

$stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

sendSuccess();
session_destroy();
header('Location: /');
exit();
?>