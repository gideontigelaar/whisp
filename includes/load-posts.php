<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/pdo-connect.php";

$loadedPosts = isset($_GET['loaded_posts']) ? json_decode($_GET['loaded_posts'], true) : [];
$loadedPostsStr = implode(',', $loadedPosts);
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 15;
$url = $_SERVER['REQUEST_URI'];

if (strpos($url, '/post') !== false) {
    $post_id = explode('/', $url)[2];

    $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header('Location: /home');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM posts WHERE reply_to_post_id = :post_id " . (empty($loadedPostsStr) ? "" : "AND post_id NOT IN ($loadedPostsStr)") . " ORDER BY created_at DESC LIMIT $limit");
    $stmt->execute(['post_id' => $post_id]);
} else if (strpos($url, '/profile') !== false) {
    $user_id = explode('/', $url)[2];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header('Location: /home');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM posts WHERE reply_to_post_id IS NULL AND user_id = :user_id " . (empty($loadedPostsStr) ? "" : "AND post_id NOT IN ($loadedPostsStr)") . " ORDER BY created_at DESC LIMIT $limit");
    $stmt->execute(['user_id' => $user_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE reply_to_post_id IS NULL " . (empty($loadedPostsStr) ? "" : "AND post_id NOT IN ($loadedPostsStr)") . " ORDER BY created_at DESC LIMIT $limit");
    $stmt->execute();
}
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $post['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $userName = $user['username'];
    $content = $post['content'];
    $createdAt = date('M j, Y', strtotime($post['created_at']));

    $likeIcon = $post['likes'] && in_array($_SESSION['user_id'], json_decode($post['likes'])) ? 'ph-fill ph-heart' : 'ph ph-heart';
    $likeCount = $post['likes'] ? count(json_decode($post['likes'])) : 0;
    $replyCount = $pdo->query("SELECT COUNT(*) FROM posts WHERE reply_to_post_id = $post[post_id]")->fetchColumn();

    include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/post.php";
}
?>