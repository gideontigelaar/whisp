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

function showError(errorMessage, isAlert) {
    const errorContainer = document.getElementById("error-container");

    if (errorContainer) {
        const errorText = document.createTextNode(errorMessage);
        const buttonIds = ["login-button", "register-button", "post-button", "delete-button", "edit-profile-button"];

        errorContainer.appendChild(errorText);
        errorContainer.removeAttribute("style");

        setButtonLoadingState(buttonIds, true, false);

        var duration = 1000 + (errorMessage.length * 25);

        setTimeout(() => {
            if (isAlert) {
                errorContainer.style.cssText = "opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;";
            } else {
                errorContainer.style.cssText = "opacity: 0;";
            }

            setButtonLoadingState(buttonIds, false, false);

            setTimeout(() => {
                errorContainer.innerHTML = "";
            }, 200);
        }, duration);
    }
}