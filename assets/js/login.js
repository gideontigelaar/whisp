function toggleLoginForm() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    [loginForm, registerForm].forEach(form => {
        form.classList.toggle('d-none');
        form.classList.toggle('d-flex');
    });
}

function toggleTwoFactorField(show) {
    var totpField = document.getElementById('totp-field');
    var totpCodeInput = document.getElementById('login-totp-code');
    var loginButton = document.getElementById('login-button');
    var buttonText = show ? 'Complete Login' : 'Log in';

    totpField.classList.toggle('show', show);
    totpField.inert = !show;
    totpCodeInput.value = '';
    loginButton.textContent = buttonText;
    loginButton.setAttribute('data-original-text', buttonText);
    updateLoginButton();

    if (show) {
        totpCodeInput.focus();
    }
}

function updateLoginButton() {
    var loginButton = document.getElementById('login-button');
    var totpFieldShown = document.getElementById('totp-field').classList.contains('show');

    if (!loginButton.querySelector('#loading')) {
        loginButton.disabled = totpFieldShown && document.getElementById('login-totp-code').value.trim() === '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    ['login-username', 'login-password'].forEach(inputId => {
        document.getElementById(inputId).addEventListener('input', function() {
            if (document.getElementById('totp-field').classList.contains('show')) {
                toggleTwoFactorField(false);
            }
        });
    });

    document.getElementById('login-totp-code').addEventListener('input', updateLoginButton);

    document.getElementById('registerSuccessModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('login-password').focus();
    });
});

function loginUser() {
    var username = document.getElementById('login-username').value;
    var password = document.getElementById('login-password').value;
    var totpCode = document.getElementById('login-totp-code').value;

    if (!document.getElementById('totp-field').classList.contains('show')) {
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
                    toggleTwoFactorField(true);
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

                if (document.getElementById('totp-field').classList.contains('show')) {
                    var totpCodeInput = document.getElementById('login-totp-code');
                    totpCodeInput.focus();
                    totpCodeInput.select();
                }
            }
        }
    }
    xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password) + '&totp_code=' + encodeURIComponent(totpCode));
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
            setButtonLoadingState(['register-button'], false, false);

            if (xhr.status === 200) {
                document.getElementById('register-form').reset();
                document.getElementById('login-username').value = username.toLowerCase();
                toggleLoginForm();

                bootstrap.Modal.getOrCreateInstance(document.getElementById('registerSuccessModal')).show();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'register-button');
            }
        }
    }
    xhr.send('username=' + encodeURIComponent(username) + '&email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password) + '&password_confirm=' + encodeURIComponent(passwordConfirm) + '&invite_code=' + encodeURIComponent(inviteCode));
}