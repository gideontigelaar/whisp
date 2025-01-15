<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT likes FROM posts WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

$likes = $post['likes'] ? json_decode($post['likes']) : [];
if (in_array($user_id, $likes)) {
    $likes = array_diff($likes, [$user_id]);
} else {
    array_push($likes, $user_id);
}

$likes = json_encode(array_values($likes));

$stmt = $pdo->prepare("UPDATE posts SET likes = :likes WHERE post_id = :post_id");
$stmt->execute(['likes' => $likes, 'post_id' => $post_id]);
?>