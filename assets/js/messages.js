messagePollDelay = 3000;
messagePollListeners.push(function(response) {
    if (response.latest_message_id !== getLatestConversationMessageId()) {
        loadNewMessages(false, loadConversations);
    }
});

document.addEventListener("DOMContentLoaded", function() {
    scrollToLatestMessage(false);
    disableButtonWhenEmpty('message-button', ['message-content']);
    disableButtonWhenEmpty('new-message-button', ['recipient-username', 'new-message-content']);
});

function getConversationUserId() {
    var chatContainer = document.getElementById('chat-container');
    return chatContainer ? chatContainer.getAttribute('data-user-id') : null;
}

function getLatestConversationMessageId() {
    var conversations = document.querySelectorAll('#conversations-container .conversation');
    var latestMessageId = 0;

    conversations.forEach(conversation => {
        latestMessageId = Math.max(latestMessageId, parseInt(conversation.getAttribute('data-message-id')));
    });

    return latestMessageId;
}

function getLastMessageId() {
    var messages = document.querySelectorAll('#messages-container .message');
    return messages.length > 0 ? messages[messages.length - 1].id.split('-')[1] : 0;
}

function isScrolledToBottom() {
    var chatContainer = document.getElementById('chat-container');
    return chatContainer.scrollHeight - chatContainer.scrollTop - chatContainer.clientHeight < 100;
}

function scrollToLatestMessage(smooth) {
    var chatContainer = document.getElementById('chat-container');
    if (chatContainer) {
        chatContainer.scrollTo({ top: chatContainer.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
    }
}

function loadNewMessages(forceScroll, callback) {
    var userId = getConversationUserId();
    if (!userId) {
        if (callback) callback();
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../../includes/load-messages.php?user_id=' + encodeURIComponent(userId) + '&after_id=' + getLastMessageId(), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200 && xhr.responseText.trim() !== '') {
                var shouldScroll = forceScroll || isScrolledToBottom();
                var messagesContainer = document.getElementById('messages-container');
                var template = document.createElement('template');
                template.innerHTML = xhr.responseText;

                Array.from(template.content.children).forEach(message => {
                    if (!document.getElementById(message.id)) {
                        messagesContainer.appendChild(message);
                    }
                });

                if (shouldScroll) {
                    scrollToLatestMessage(true);
                }
            }

            if (callback) callback();
        }
    }
    xhr.send();
}

function loadConversations() {
    var userId = getConversationUserId();

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../../includes/load-conversations.php' + (userId ? '?user_id=' + encodeURIComponent(userId) : ''), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('conversations-container').innerHTML = xhr.responseText;
        }
    }
    xhr.send();
}

function sendMessage(user_id) {
    var messageContent = document.getElementById('message-content');
    setButtonLoadingState(['message-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/send-message.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['message-button'], false, false);

            if (xhr.status === 200) {
                messageContent.value = '';
                messageContent.dispatchEvent(new Event('input'));
                messageContent.focus();
                loadNewMessages(true, loadConversations);
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'message-button');
            }
        }
    }
    xhr.send('recipient_id=' + encodeURIComponent(user_id) + '&content=' + encodeURIComponent(messageContent.value));
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
            var response = JSON.parse(xhr.responseText);

            if (xhr.status === 200) {
                window.location.href = '/messages/' + response.recipient_id;
            } else {
                setButtonLoadingState(['new-message-button'], false, false);
                showError(response.error, true, 'new-message-button');
            }
        }
    }
    xhr.send('recipient_username=' + encodeURIComponent(recipient_username) + '&content=' + encodeURIComponent(content));
}