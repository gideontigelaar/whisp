document.addEventListener("DOMContentLoaded", function() {
    var chatContainer = document.getElementById('chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight - chatContainer.clientHeight;
    }
});

function sendMessage(user_id) {
    var content = document.getElementById('message-content').value;
    setButtonLoadingState(['message-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/send-message.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['message-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'message-button');
            }
        }
    }
    xhr.send('&recipient_id=' + user_id + '&content=' + content);
}

function sendNewMessage() {
    var recipient_username = document.getElementById('recipient-username').value;
    var content = document.getElementById('new-message-content').value;
    setButtonLoadingState(['new-message-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/send-new-message.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['new-message-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'new-message-button');
            }
        }
    }
    xhr.send('&recipient_username=' + recipient_username + '&content=' + content);
}