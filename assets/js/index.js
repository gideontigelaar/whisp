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