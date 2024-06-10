<div class="modal fade" id="editSettingsModal" tabindex="-1" aria-labelledby="editSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editSettingsModalLabel">Settings</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <input type="radio" class="btn-check theme-btn" name="theme-options" id="dark" autocomplete="off">
                    <label class="btn bg-black text-white theme-btn-label border border-2" for="dark">Dark Mode</label>

                    <input type="radio" class="btn-check theme-btn" name="theme-options" id="light" autocomplete="off">
                    <label class="btn bg-white text-black theme-btn-label border border-2" for="light">Light Mode</label>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span>Username</span>
                            <span class="opacity-75 small">@<?= $currentUserName ?></span>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editUsernameModal">Edit</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span>Email</span>
                            <span class="opacity-75 small"><?= $currentUserEmail ?></span>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editEmailModal">Edit</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span>Password</span>
                            <span class="opacity-75 small">••••••••••••••••</span>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editPasswordModal">Edit</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUsernameModal" tabindex="-1" aria-labelledby="editUsernameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editUsernameModalLabel">Edit username</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- <div class="alert alert-danger" id="error-container" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div> -->

                <div class="mb-3">
                    <label class="form-label" for="edit-username">Username</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input class="form-control" type="text" id="edit-username" autocomplete="username" value="<?= $currentUserName ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="current-password">Current password</label>
                    <input class="form-control" type="password" id="current-password" autocomplete="current-password">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Save</button>
                <!-- <button class="btn btn-primary" type="button" id="edit-settings-button" onclick="editSettings(<?= $user_id ?>)">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editEmailModalLabel">Edit email</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- <div class="alert alert-danger" id="error-container" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div> -->

                <div class="mb-3">
                    <label class="form-label" for="edit-email">Email</label>
                    <input class="form-control" type="email" id="edit-email" autocomplete="email" value="<?= $currentUserEmail ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="current-password">Current password</label>
                    <input class="form-control" type="password" id="current-password" autocomplete="current-password">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Save</button>
                <!-- <button class="btn btn-primary" type="button" id="edit-settings-button" onclick="editSettings(<?= $user_id ?>)">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editPasswordModalLabel">Edit password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- <div class="alert alert-danger" id="error-container" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div> -->

                <div class="mb-3">
                    <label class="form-label" for="current-password">Current password</label>
                    <input class="form-control" type="password" id="current-password" autocomplete="current-password">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="edit-password">New password</label>
                    <input class="form-control" type="password" id="edit-password" autocomplete="new-password">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="edit-password-confirm">Confirm new password</label>
                    <input class="form-control" type="password" id="edit-password-confirm" autocomplete="new-password">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="show-passwords">
                        <label class="form-check-label" for="show-passwords">Show passwords</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Save</button>
                <!-- <button class="btn btn-primary" type="button" id="edit-settings-button" onclick="editSettings(<?= $user_id ?>)">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<script>
    const dark = document.getElementById('dark');
    const light = document.getElementById('light');
    const currentTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-bs-theme', currentTheme);

    if (currentTheme === 'dark') {
        dark.checked = true;
    } else {
        light.checked = true;
    }

    dark.addEventListener('change', () => {
        document.querySelectorAll('*').forEach(function(element) {
            element.style.transition = 'none';
        });

        document.body.setAttribute('data-bs-theme', 'dark');
        localStorage.setItem('theme', 'dark');

        setTimeout(() => {
            document.querySelectorAll('*').forEach(function(element) {
                element.style.transition = '';
            });
        }, 100);
    });

    light.addEventListener('change', () => {
        document.querySelectorAll('*').forEach(function(element) {
            element.style.transition = 'none';
        });

        document.body.setAttribute('data-bs-theme', 'light');
        localStorage.setItem('theme', 'light');

        setTimeout(() => {
            document.querySelectorAll('*').forEach(function(element) {
                element.style.transition = '';
            });
        }, 100);
    });
</script>