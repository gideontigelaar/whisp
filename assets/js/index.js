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

function showError(errorMessage) {
    const errorContainer = document.getElementById("error-container");
    const errorText = document.createTextNode(errorMessage);

    errorContainer.innerHTML = "";
    errorContainer.appendChild(errorText);
    errorContainer.removeAttribute("style");

    setButtonLoadingState(["login-button", "register-button", "post-button"], true, false);

    var duration = 1000 + (errorMessage.length * 25);

    setTimeout(() => {
        errorContainer.style.cssText = "opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden";
        setButtonLoadingState(["login-button", "register-button", "post-button"], false, false);

        setTimeout(() => {
            errorContainer.innerHTML = "";
        }, 200);
    }, duration);
}