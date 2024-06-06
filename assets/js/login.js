function toggleLoginForm() {
    var loginForm = document.getElementById('login-form');
    var registerForm = document.getElementById('register-form');

    loginForm.classList.toggle('d-none');
    registerForm.classList.toggle('d-none');
}

function loginUser() {
    var username = document.getElementById('login-username').value;
    var password = document.getElementById('login-password').value;
    setButtonLoadingState(['login-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/login-user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['login-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error);
            }
        }
    }
    xhr.send('username=' + username + '&password=' + password);
}

function registerUser() {
    var username = document.getElementById('register-username').value;
    var email = document.getElementById('register-email').value;
    var password = document.getElementById('register-password').value;
    var passwordConfirm = document.getElementById('register-password-confirm').value;
    var inviteCode = document.getElementById('register-invite-code').value;
    setButtonLoadingState(['register-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/register-user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['register-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error);
            }
        }
    }
    xhr.send('username=' + username + '&email=' + email + '&password=' + password + '&password_confirm=' + passwordConfirm + '&invite_code=' + inviteCode);
}