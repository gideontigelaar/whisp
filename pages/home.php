<?php require_once "../queries/validate-session.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Whisp | Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/fill/style.css">
    <link rel="stylesheet" href="/assets/css/styles.css">

    <script src="/assets/js/index.js"></script>
    <script src="/assets/js/posts.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-auto ps-0">
                <?php include "../includes/components/nav.php" ?>
            </div>
            <div class="col">
                <div class="alert alert-danger" id="error-container" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>
                <div class="card text-bg-dark">
                    <div class="card-body">
                        <form>
                            <textarea class="form-control mb-3" id="post-content" rows="3" maxlength="250" placeholder="What is happening?!" style="resize: none;"></textarea>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-2">
                                    <i class="ph ph-image-square text-primary" role="button"></i>
                                    <i class="ph ph-smiley text-primary" role="button"></i>
                                </div>
                                <button class="btn btn-primary" type="submit" id="post-button" onclick="createPost()">Post</button>
                            </div>
                        </form>
                    </div>
                </div>

                <h3 class="mt-4">Recent posts</h3>
                <?php include "../includes/components/load-posts.php" ?>
            </div>
            <div class="col-md-4 col-lg-3 d-none d-md-flex flex-column">
                <div class="card text-bg-dark">
                    <div class="card-body">
                        <h5 class="card-title">Who to follow</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content. Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>

                <div class="card text-bg-dark">
                    <div class="card-body">
                        <h5 class="card-title">Who to follow</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content. Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>