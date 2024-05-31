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