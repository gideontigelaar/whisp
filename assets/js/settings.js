function editProfile() {
    var displayName = document.getElementById('edit-display-name').value;
    var profilePicture = document.getElementById('edit-profile-picture').value;
    var bio = document.getElementById('edit-bio').value;
    setButtonLoadingState(['edit-profile-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/edit-profile.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['edit-profile-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-profile-button');
            }
        }
    }
    xhr.send('&display_name=' + displayName + '&profile_picture=' + profilePicture + '&bio=' + bio);
}

function editUsername() {
    var username = document.getElementById('edit-username').value;
    var password = document.getElementById('edit-username-password').value;
    setButtonLoadingState(['edit-username-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/edit-username.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['edit-username-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-username-button');
            }
        }
    }
    xhr.send('&username=' + username + '&password=' + password);
}

function editEmail() {
    var email = document.getElementById('edit-email').value;
    var password = document.getElementById('edit-email-password').value;
    setButtonLoadingState(['edit-email-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/edit-email.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['edit-email-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-email-button');
            }
        }
    }
    xhr.send('&email=' + email + '&password=' + password);
}

function showPasswords() {
    const editPassword = document.getElementById('edit-password');
    const editPasswordConfirm = document.getElementById('edit-password-confirm');
    const showPasswords = document.getElementById('show-passwords');

    if (showPasswords.checked) {
        editPassword.type = 'text';
        editPasswordConfirm.type = 'text';
    } else {
        editPassword.type = 'password';
        editPasswordConfirm.type = 'password';
    }
}

function editPassword() {
    var currentPassword = document.getElementById('current-password').value;
    var newPassword = document.getElementById('edit-password').value;
    var newPasswordConfirm = document.getElementById('edit-password-confirm').value;
    setButtonLoadingState(['edit-password-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/edit-password.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['edit-password-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-password-button');
            }
        }
    }
    xhr.send('&current_password=' + currentPassword + '&new_password=' + newPassword + '&new_password_confirm=' + newPasswordConfirm);
}

function deleteAccount() {
    var password = document.getElementById('delete-account-password').value;
    var confirmDeletion = document.getElementById('delete-account-confirm').value;
    setButtonLoadingState(['delete-account-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/delete-account.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                logoutUser();
            } else {
                setButtonLoadingState(['delete-account-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'delete-account-button');
            }
        }
    }
    xhr.send('&password=' + password + '&confirm_deletion=' + confirmDeletion);
}

function logoutUser() {
    localStorage.clear();
    setButtonLoadingState(['logout-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/logout-user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            location.reload();
        }
    }
    xhr.send();
}

function logoutAllSessions() {
    setButtonLoadingState(['logout-all-sessions-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/logout-all-sessions.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            location.reload();
        }
    }
    xhr.send();
}