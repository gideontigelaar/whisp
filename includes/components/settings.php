<div class="modal fade" id="editSettingsModal" tabindex="-1" aria-labelledby="editSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editSettingsModalLabel">Settings</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3 d-flex gap-2">
                    <div>
                        <input type="radio" class="btn-check theme-btn" name="theme-options" id="dark" autocomplete="off">
                        <label class="btn bg-black text-white theme-btn-label border border-2" for="dark">Dark Mode</label>
                    </div>

                    <div>
                        <input type="radio" class="btn-check theme-btn" name="theme-options" id="light" autocomplete="off">
                        <label class="btn bg-white text-black theme-btn-label border border-2" for="light">Light Mode</label>
                    </div>
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

            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete account</button>
                    <button class="btn btn-secondary" type="button" id="logout-button" onclick="logoutUser()">Log out</button>
                </div>

                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUsernameModal" tabindex="-1" aria-labelledby="editUsernameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUsernameModalLabel">Edit username</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="edit-username-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-username">Username</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">@</span>
                            <input class="form-control" type="text" id="edit-username" autocomplete="username" value="<?= $currentUserName ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-username-password">Current password</label>
                        <input class="form-control" type="password" id="edit-username-password" autocomplete="current-password">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="edit-username-button" onclick="editUsername()">Update username</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editEmailModalLabel">Edit email</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="edit-email-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-email">Email</label>
                        <input class="form-control" type="email" id="edit-email" autocomplete="email" value="<?= $currentUserEmail ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-email-password">Current password</label>
                        <input class="form-control" type="password" id="edit-email-password" autocomplete="current-password">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="edit-email-button" onclick="editEmail()">Update email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPasswordModalLabel">Edit password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="edit-password-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

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
                            <input class="form-check-input" type="checkbox" id="show-passwords" onclick="showPasswords()">
                            <label class="form-check-label" for="show-passwords">Show passwords</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="edit-password-button" onclick="editPassword()">Update password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteAccountModalLabel">Delete account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="delete-account-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                    <div class="mb-3">
                        <label class="form-label" for="delete-account-password">Password</label>
                        <input class="form-control" type="password" id="delete-account-password" autocomplete="current-password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="delete-account-confirm">Confirm account deletion</label>
                        <input class="form-control" type="text" id="delete-account-confirm" autocomplete="off" placeholder="Type 'DELETE' to confirm">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-danger" type="submit" id="delete-account-button" onclick="deleteAccount()">Delete account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/settings.js"></script>

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