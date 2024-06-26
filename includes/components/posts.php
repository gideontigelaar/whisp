<div class="card mb-3 post" id="post-<?= $post['post_id'] ?>">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-8 d-flex gap-2 align-items-center" role="button" onclick="window.location.href = '/profile/<?= $post['user_id'] ?>'">
                <img class="rounded-circle" src="<?= $profilePicture ?>" width="32" height="32" alt="PFP">
                <div class="d-flex gap-1 align-items-center text-truncate">
                    <span class="fs-5 text-truncate" style="max-width: 150px;"><?= $displayName ?></span>
                    <?php if ($isVerified) { ?>
                        <i class="ph-fill ph-seal-check text-primary" style="font-size: 22px!important;"></i>
                    <?php } ?>
                    <span class="opacity-75">@<?= $userName ?></span>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex gap-1 justify-content-md-end align-items-center mt-1 mt-md-0">
                <span class="opacity-75"><?= $createdAt ?></span>
            </div>
        </div>
        <p class="mt-1" style="white-space: pre-wrap; max-width: 400px;"><?= $content ?></p>
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
                <i class="ph ph-share-fat text-primary" role="button" onclick="sharePost(<?= $post['post_id'] . ', \'' . $displayName . '\', \'' . $userName . '\'' ?>)"></i>
                <?php if ($_SESSION['user_id'] === $post['user_id']) { ?>
                    <i class="ph ph-trash text-danger" role="button" data-bs-toggle="modal" data-bs-target="#deletePostModal-<?= $post['post_id'] ?>"></i>

                    <div class="modal fade" id="deletePostModal-<?= $post['post_id'] ?>" tabindex="-1" aria-labelledby="deletePostModalLabel-<?= $post['post_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
                            <div class="modal-content">
                                <form method="post">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="deletePostModalLabel-<?= $post['post_id'] ?>">Delete post</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        Are you sure you want to delete this post? This action cannot be undone.
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger" id="delete-post-button" onclick="deletePost(<?= $post['post_id'] ?>)">Delete post</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>