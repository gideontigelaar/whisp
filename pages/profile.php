<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/validate-session.php";

$url = $_SERVER['REQUEST_URI'];
if (strpos($url, '/profile') !== false) {
    $user_id = explode('/', $url)[2];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header('Location: /profile/' . $_SESSION['user_id']);
        exit();
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$userName = $user['username'];
$displayName = $user['display_name'];
$profilePicture = $user['profile_picture'] ? $user['profile_picture'] : '/assets/images/default-pfp.png';
$bio = $user['bio'];
$isVerified = $user['is_verified'];
$createdAt = date('F Y', strtotime($user['created_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Whisp | Profile</title>

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
            <div class="col-12 col-sm-auto ps-0">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/nav.php" ?>
            </div>
            <div class="col pt-3 pb-5">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-4 align-items-center">
                                <img class="rounded-circle" src="<?= $profilePicture ?>" width="80" alt="PFP">
                                <div class="d-flex flex-column">
                                    <div class="d-flex gap-1 align-items-center">
                                        <h4 class="mb-0"><?= $displayName ?></h4>
                                        <?php if ($isVerified) { ?>
                                            <i class="ph-fill ph-seal-check text-primary"></i>
                                        <?php } ?>
                                    </div>
                                    <span class="opacity-75">@<?= $userName ?></span>
                                </div>
                            </div>

                            <?php if ($user_id == $_SESSION['user_id']) { ?>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit</button>

                                <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editProfileModalLabel">Edit profile</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Edit profile form
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" onclick="editProfile(<?= $user_id ?>)">Save changes</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <p class="card-text mt-3"><?= $bio ?></p>
                            </div>
                            <div class="col-12 col-md-4 text-md-end align-content-md-end">
                                <p class="card-text mt-3 opacity-75">Joined <?= $createdAt ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="mt-4">All posts</h4>
                <div id="posts-container">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/load-posts.php" ?>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 d-none d-md-flex flex-column py-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/cards.php" ?>
            </div>
        </div>
    </div>
</body>
</html>