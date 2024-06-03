<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/pdo-connect.php";

$url = $_SERVER['REQUEST_URI'];
if (strpos($url, '/post') !== false) {
    $post_id = explode('/', $url)[2];

    //check if post exists
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // if post does not exist, redirect to home, else, display post, and replies
    if (!$post) {
        header('Location: /home');
    } else {
        $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $post['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $userName = $user['username'];
        $content = $post['content'];
        $createdAt = date('M j, Y', strtotime($post['created_at']));
        $likeCount = $post['likes'] ? count(json_decode($post['likes'])) : 0;
        $replyCount = $pdo->query("SELECT COUNT(*) FROM posts WHERE reply_to_post_id = {$post['post_id']}")->fetchColumn();

        $likeIcon = $post['likes'] && in_array($_SESSION['user_id'], json_decode($post['likes'])) ? 'ph-fill ph-heart' : 'ph ph-heart';
        ?>
        <div class="card mb-3 post">
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
                        <div class="d-flex gap-1 align-items-center" role="button" onclick="likePost(<?= $post['post_id'] ?>)">
                            <i class="<?= $likeIcon ?> text-primary" id="like-icon-<?= $post['post_id'] ?>"></i>
                            <span id="like-count-<?= $post['post_id'] ?>"><?= $likeCount ?></span>
                        </div>
                        <div class="d-flex gap-1 align-items-center" role="button">
                            <i class="ph ph-chat-circle text-primary"></i>
                            <span><?= $replyCount ?></span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <i class="ph ph-share-fat text-primary" role="button"></i>
                        <?php if ($_SESSION['user_id'] === $post['user_id']) { ?>
                            <i class="ph ph-trash text-danger" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>

                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete post?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this post? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger" onclick="deletePost(<?= $post['post_id'] ?>)">Delete post</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 15;

    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    $stmt->execute();
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

        $likeIcon = $post['likes'] && in_array($_SESSION['user_id'], json_decode($post['likes'])) ? 'ph-fill ph-heart' : 'ph ph-heart';
        ?>
        <div class="card mb-3 post">
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
                        <div class="d-flex gap-1 align-items-center" role="button" onclick="likePost(<?= $post['post_id'] ?>)">
                            <i class="<?= $likeIcon ?> text-primary" id="like-icon-<?= $post['post_id'] ?>"></i>
                            <span id="like-count-<?= $post['post_id'] ?>"><?= $likeCount ?></span>
                        </div>
                        <div class="d-flex gap-1 align-items-center" role="button" onclick="window.location.href = '/post/<?= $post['post_id'] ?>'">
                            <i class="ph ph-chat-circle text-primary"></i>
                            <span><?= $replyCount ?></span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <i class="ph ph-share-fat text-primary" role="button" onclick="sharePost(<?= $post['post_id'] ?>)"></i>
                        <?php if ($_SESSION['user_id'] === $post['user_id']) { ?>
                            <i class="ph ph-trash text-danger" role="button" data-bs-toggle="modal" data-bs-target="#deletePostModal"></i>

                            <div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="deletePostModalLabel">Delete post?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this post? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger" onclick="deletePost(<?= $post['post_id'] ?>)">Delete post</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>