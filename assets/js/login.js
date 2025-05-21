function toggleLoginForm() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    [loginForm, registerForm].forEach(form => {
        form.classList.toggle('d-none');
        form.classList.toggle('d-flex');
    });
}

function loginUser() {
    var username = document.getElementById('login-username').value;
    var password = document.getElementById('login-password').value;
    var totpCode = document.getElementById('login-totp-code').value;
    var totpField = document.getElementById('totp-field');

    if (!totpField.classList.contains('d-none') && !totpCode) {
        showError('2FA code is required.', true, 'login-button');
        return;
    }

    if (totpField.classList.contains('d-none')) {
        checkIfUserHasTwoFactor(username, password);
        return;
    }

    performLogin(username, password, totpCode);
}

function checkIfUserHasTwoFactor(username, password) {
    setButtonLoadingState(['login-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/check-two-factor.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['login-button'], false, false);

            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.has_two_factor) {
                    var totpField = document.getElementById('totp-field');
                    totpField.classList.remove('d-none');
                    document.getElementById('login-totp-code').focus();

                    document.getElementById('login-button').textContent = 'Complete Login';
                } else {
                    performLogin(username, password, '');
                }
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'login-button');
            }
        }
    }
    xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
}

function performLogin(username, password, totpCode) {
    setButtonLoadingState(['login-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/login-user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['login-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'login-button');

                resetLoginForm();
            }
        }
    }
    xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password) + '&totp_code=' + encodeURIComponent(totpCode));
}

function resetLoginForm() {
    var totpField = document.getElementById('totp-field');
    totpField.classList.add('d-none');
    document.getElementById('login-totp-code').value = '';
    document.getElementById('login-button').textContent = 'Log in';
}

function registerUser() {
    var username = document.getElementById('register-username').value;
    var email = document.getElementById('register-email').value;
    var password = document.getElementById('register-password').value;
    var passwordConfirm = document.getElementById('register-password-confirm').value;
    var inviteCode = document.getElementById('register-invite-code').value;
    setButtonLoadingState(['register-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/register-user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['register-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'register-button');
            }
        }
    }
    xhr.send('username=' + username + '&email=' + email + '&password=' + encodeURIComponent(password) + '&password_confirm=' + encodeURIComponent(passwordConfirm) + '&invite_code=' + inviteCode);
}