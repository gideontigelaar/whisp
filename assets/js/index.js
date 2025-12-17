function setButtonLoadingState(buttons, disableButton, loadingAnimation) {
    buttons.forEach(buttonId => {
        var button = document.getElementById(buttonId);
        if (button) {
            if (disableButton) {
                button.setAttribute("disabled", "disabled");
            } else {
                button.removeAttribute("disabled");
            }

            var buttonLoading = '<span id="loading"><span>&bull;</span><span>&bull;</span><span>&bull;</span></span>';
            var buttonText = button.getAttribute("data-original-text");

            if (!buttonText) {
                buttonText = button.textContent || button.innerText;
                button.setAttribute("data-original-text", buttonText);
            }

            if (loadingAnimation) {
                button.innerHTML = buttonLoading;
            } else {
                button.innerHTML = buttonText;
            }
        }
    });
}

function disableButtonWhenEmpty(buttonId, inputIds) {
    var button = document.getElementById(buttonId);
    var inputs = inputIds.map(inputId => document.getElementById(inputId));

    if (!button || inputs.includes(null)) {
        return;
    }

    var updateButton = function() {
        if (!button.querySelector('#loading')) {
            button.disabled = inputs.some(input => input.value.trim() === '');
        }
    };

    inputs.forEach(input => input.addEventListener('input', updateButton));
    updateButton();
}

function showError(errorMessage, isAlert, buttonId) {
    const errorContainer = document.getElementById(buttonId + "-error");

    if (errorContainer) {
        const errorText = document.createTextNode(errorMessage);

        errorContainer.appendChild(errorText);
        errorContainer.removeAttribute("style");

        setButtonLoadingState([buttonId], true, false);

        var duration = 1000 + (errorMessage.length * 25);

        setTimeout(() => {
            if (isAlert) {
                errorContainer.style.cssText = "opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;";
            } else {
                errorContainer.style.cssText = "opacity: 0;";
            }

            setButtonLoadingState([buttonId], false, false);

            setTimeout(() => {
                errorContainer.innerHTML = "";
            }, 200);
        }, duration);
    }
}

var messagePollDelay = 10000;
var messagePollTimeout = null;
var messagePollRequest = null;
var messagePollListeners = [];

function pollMessages() {
    clearTimeout(messagePollTimeout);

    if (document.hidden || messagePollRequest) {
        return;
    }

    var xhr = new XMLHttpRequest();
    messagePollRequest = xhr;
    xhr.open('GET', '../../api/check-messages.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            messagePollRequest = null;

            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                messagePollListeners.forEach(listener => listener(response));
            }

            if (xhr.status !== 401) {
                messagePollTimeout = setTimeout(pollMessages, messagePollDelay);
            }
        }
    }
    xhr.send();
}

document.addEventListener('DOMContentLoaded', function() {
    if (messagePollListeners.length > 0) {
        messagePollTimeout = setTimeout(pollMessages, messagePollDelay);
    }
});

document.addEventListener('visibilitychange', function() {
    if (!document.hidden && messagePollListeners.length > 0) {
        pollMessages();
    }
});