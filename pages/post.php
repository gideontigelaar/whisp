<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/validate-session.php";

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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A place for developers to learn and share.">

    <title>Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/fill/style.css">
    <link rel="stylesheet" href="/assets/css/styles.css">

    <script src="/assets/js/index.js"></script>
    <script src="/assets/js/posts.js"></script>
</head>
<body class="bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-auto ps-0 pe-0 pe-md-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/nav.php" ?>
            </div>
            <div class="col mb-5 p-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-flex" role="button" onclick="window.history.back()">
                        <i class="ph ph-arrow-left"></i>
                    </div>
                    <span class="fs-5">Post</span>
                </div>

                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/original-post.php" ?>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <textarea class="form-control" id="post-content" rows="1" maxlength="250" placeholder="Post your reply" style="resize: none;"></textarea>
                            <button class="btn btn-primary" type="submit" id="post-button" onclick="createPost(<?= $post_id ?>)">Post</button>
                        </div>
                    </div>
                </div>

                <h4 class="mt-4">Replies</h4>
                <div id="posts-container">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/load-posts.php" ?>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 d-none d-md-flex flex-column p-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/cards.php" ?>
            </div>
        </div>
    </div>
</body>
</html>