const newPasswordInput = document.querySelector("#newPassword");
const newPasswordConfirmationInput = document.querySelector("#newPasswordConfirm");
const updateButton = document.querySelector("#submit");

function checkPassword() {
    let regex = REGEX_PASSWORD;
    return regex.test(newPasswordInput.value) && newPasswordInput.value === newPasswordConfirmationInput.value;
}

function validate() {
    valid = checkPassword();

    if (valid) {
        updateButton.removeAttribute("disabled");
    } else {
        updateButton.setAttribute("disabled", true);
    }
}

updateButton.setAttribute("disabled", true);