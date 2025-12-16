<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A place for developers to learn and share.">

    <title>Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css" integrity="sha384-6p9AefaqUhEVheRlj1mpAkbngHXy9mbYMrIdcIt4Jlc9lOLIablJq3bBsLOjGwZ7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/fill/style.css" integrity="sha384-pPVoXE8ft+zxKtxIDDI7SfTK6y95NHm4qa+hKEg/hs8VkjW5IP+9/dGOPCbDpUPl" crossorigin="anonymous">
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
            <div class="col mb-5 mb-sm-0 p-3 main-column">
                <div class="card">
                    <div class="card-body">
                        <form method="post">
                            <textarea class="form-control mb-3" id="post-content" rows="3" maxlength="250" placeholder="What is happening?!" style="resize: none;"></textarea>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-2">
                                    <i class="ph ph-image-square text-primary" role="button"></i>
                                    <i class="ph ph-smiley text-primary" role="button"></i>
                                </div>

                                <div class="text-danger" id="post-button-error" style="opacity: 0;"></div>

                                <button class="btn btn-primary" type="submit" id="post-button" onclick="createPost()">Post</button>
                            </div>
                        </form>
                    </div>
                </div>

                <h4 class="mt-4">Recent posts</h4>
                <div id="posts-container">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/load-posts.php" ?>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 col-xxl-2 d-none d-md-flex flex-column p-3 pt-0">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/cards.php" ?>
            </div>
        </div>
    </div>
</body>
</html>