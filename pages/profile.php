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
$profilePicture = $user['profile_picture'] && @getimagesize($user['profile_picture']) ? $user['profile_picture'] : '/assets/images/default-pfp.png';
$bio = $user['bio'];
$isVerified = $user['is_verified'];
$createdAt = date('F Y', strtotime($user['created_at']));
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
    <script src="/assets/js/settings.js"></script>
</head>
<body class="bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-auto ps-0 pe-0 pe-md-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/nav.php" ?>
            </div>
            <div class="col mb-5 p-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-4 align-items-center">
                                <img class="rounded-circle" src="<?= $profilePicture ?>" width="80" height="80" alt="PFP">
                                <div class="d-flex flex-column">
                                    <div class="d-flex gap-1 align-items-center">
                                        <h4 class="mb-0 text-break"><?= $displayName ?></h4>
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
                                    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
                                        <div class="modal-content">
                                            <form method="post">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="editProfileModalLabel">Edit profile</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="alert alert-danger" id="edit-profile-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="edit-display-name">Display name</label>
                                                        <input class="form-control" type="text" id="edit-display-name" value="<?= $displayName ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="edit-profile-picture">Profile picture</label>
                                                        <input class="form-control" type="text" id="edit-profile-picture" value="<?= $profilePicture == '/assets/images/default-pfp.png' ? '' : $profilePicture ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="edit-bio">Bio</label>
                                                        <textarea class="form-control" id="edit-bio" rows="3" maxlength="150"><?= $bio ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                                    <button class="btn btn-primary" type="submit" id="edit-profile-button" onclick="editProfile(<?= $user_id ?>)">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <p class="card-text mt-3" style="white-space: pre-wrap; max-width: 400px;"><?= $bio ?></p>
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
            <div class="col-md-4 col-lg-3 d-none d-md-flex flex-column p-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/cards.php" ?>
            </div>
        </div>
    </div>
</body>
</html>