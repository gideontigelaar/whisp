<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/pdo-connect.php";

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

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT user_id FROM posts WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($post['user_id'] === $user_id) {
    deleteReplies($pdo, $post_id);

    $stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post_id]);
} else {
    http_response_code(403);
    exit();
}
?>