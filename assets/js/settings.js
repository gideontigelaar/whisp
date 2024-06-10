function editProfile(user_id) {
    var displayName = document.getElementById('edit-display-name').value;
    var profilePicture = document.getElementById('edit-profile-picture').value;
    var bio = document.getElementById('edit-bio').value;
    setButtonLoadingState(['edit-profile-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/edit-profile.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['edit-profile-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-profile-button');
            }
        }
    }
    xhr.send('user_id=' + user_id + '&display_name=' + displayName + '&profile_picture=' + profilePicture + '&bio=' + bio);
}

function editUsername(user_id) {
    var username = document.getElementById('edit-username').value;
    var password = document.getElementById('edit-username-password').value;
    setButtonLoadingState(['edit-username-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/edit-username.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['edit-username-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-username-button');
            }
        }
    }
    xhr.send('user_id=' + user_id + '&username=' + username + '&password=' + password);
}

function editEmail(user_id) {
    var email = document.getElementById('edit-email').value;
    var password = document.getElementById('edit-email-password').value;
    setButtonLoadingState(['edit-email-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/edit-email.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['edit-email-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-email-button');
            }
        }
    }
    xhr.send('user_id=' + user_id + '&email=' + email + '&password=' + password);
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

function editPassword(user_id) {
    var currentPassword = document.getElementById('current-password').value;
    var newPassword = document.getElementById('edit-password').value;
    var newPasswordConfirm = document.getElementById('edit-password-confirm').value;
    setButtonLoadingState(['edit-password-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/edit-password.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['edit-password-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'edit-password-button');
            }
        }
    }
    xhr.send('user_id=' + user_id + '&current_password=' + currentPassword + '&new_password=' + newPassword + '&new_password_confirm=' + newPasswordConfirm);
}

function deleteAccount(user_id) {
    var password = document.getElementById('delete-account-password').value;
    var confirmDeletion = document.getElementById('delete-account-confirm').value;
    setButtonLoadingState(['delete-account-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/delete-account.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['delete-account-button'], false, false);
            if (xhr.status === 200) {
                logoutUser();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'delete-account-button');
            }
        }
    }
    xhr.send('user_id=' + user_id + '&password=' + password + '&confirm_deletion=' + confirmDeletion);
}

function logoutUser() {
    localStorage.clear();
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf('=');
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT';
    }
    location.reload();
}