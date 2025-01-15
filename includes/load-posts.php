<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";

$loadedPosts = isset($_GET['loaded_posts']) ? json_decode($_GET['loaded_posts'], true) : [];
$loadedPostsStr = implode(',', $loadedPosts);
$limit = 10;
$url = isset($_GET['current_url']) ? $_GET['current_url'] : $_SERVER['REQUEST_URI'];

if (strpos($url, '/post') !== false) {
    $post_id = explode('/', $url)[2];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE reply_to_post_id = :post_id " . (empty($loadedPostsStr) ? "" : "AND post_id NOT IN ($loadedPostsStr)") . " ORDER BY created_at DESC LIMIT $limit");
    $stmt->execute(['post_id' => $post_id]);
} else if (strpos($url, '/profile') !== false) {
    $user_id = explode('/', $url)[2];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE reply_to_post_id IS NULL AND user_id = :user_id " . (empty($loadedPostsStr) ? "" : "AND post_id NOT IN ($loadedPostsStr)") . " ORDER BY created_at DESC LIMIT $limit");
    $stmt->execute(['user_id' => $user_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE reply_to_post_id IS NULL " . (empty($loadedPostsStr) ? "" : "AND post_id NOT IN ($loadedPostsStr)") . " ORDER BY created_at DESC LIMIT $limit");
    $stmt->execute();
}
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $post['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $userName = $user['username'];
    $displayName = $user['display_name'];
    $profilePicture = $user['profile_picture'] && @getimagesize($user['profile_picture']) ? $user['profile_picture'] : '/assets/images/default-pfp.png';
    $isVerified = $user['is_verified'];
    $content = $post['content'];
    $createdAt = date('M j, Y', strtotime($post['created_at']));
    $likeIcon = $post['likes'] && in_array($_SESSION['user_id'], json_decode($post['likes'])) ? 'ph-fill ph-heart' : 'ph ph-heart';
    $likeCount = $post['likes'] ? count(json_decode($post['likes'])) : 0;
    $replyCount = $pdo->query("SELECT COUNT(*) FROM posts WHERE reply_to_post_id = $post[post_id]")->fetchColumn();

    include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/posts.php";
}
?>