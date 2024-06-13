<div class="sticky-top overflow-y-auto pt-3">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Users to check out</h5>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id != :user_id ORDER BY RAND() LIMIT 4");
            $stmt->execute(['user_id' => $_SESSION['user_id']]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                $userId = $user['user_id'];
                $userName = $user['username'];
                $displayName = $user['display_name'];
                $profilePicture = $user['profile_picture'] && @getimagesize($user['profile_picture']) ? $user['profile_picture'] : '/assets/images/default-pfp.png';
                $bio = $user['bio'];
                $isVerified = $user['is_verified'];
                $createdAt = date('F Y', strtotime($user['created_at']));
            ?>
            <div class="py-2 d-flex gap-2 align-items-center" role="button" onclick="window.location.href = '/profile/<?= $userId ?>'">
                <img class="rounded-circle" src="<?= $profilePicture ?>" width="32" height="32" alt="PFP">
                <div class="d-flex gap-1 align-items-center text-truncate">
                    <span class="fs-5 text-truncate"><?= $displayName ?></span>
                    <?php if ($isVerified) { ?>
                        <i class="ph-fill ph-seal-check text-primary" style="font-size: 22px!important;"></i>
                    <?php } ?>
                    <span class="opacity-75">@<?= $userName ?></span>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>