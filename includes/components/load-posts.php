<?php
session_start();
require_once "../queries/pdo-connect.php";

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $post['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $userName = $user['username'];
    $content = $post['content'];
    $createdAt = date('M j, Y', strtotime($post['created_at']));
    $likeCount = $post['likes'] ? count(json_decode($post['likes'])) : 0;
    $replyCount = $pdo->query("SELECT COUNT(*) FROM posts WHERE reply_to_post_id = {$post['post_id']}")->fetchColumn();
    ?>
    <div class="card text-bg-dark mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="d-flex gap-1 align-items-center" role="button">
                    <i class="ph ph-user-circle text-primary"></i>
                    <span class="fs-5"><?= $userName ?></span>
                </div>
                <div class="d-flex gap-1 align-items-center">
                    <span class="opacity-75"><?= $createdAt ?></span>
                </div>
            </div>
            <p class="mt-1"><?= $content ?></p>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex gap-2">
                    <div class="d-flex gap-1 align-items-center" role="button">
                        <i class="ph ph-heart text-primary"></i>
                        <span><?= $likeCount ?></span>
                    </div>
                    <div class="d-flex gap-1 align-items-center" role="button">
                        <i class="ph ph-chat-circle text-primary"></i>
                        <span><?= $replyCount ?></span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <i class="ph ph-share-fat text-primary" role="button"></i>
                    <?php if ($_SESSION['user_id'] === $post['user_id']) { ?>
                        <i class="ph ph-trash text-danger" role="button"></i>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>