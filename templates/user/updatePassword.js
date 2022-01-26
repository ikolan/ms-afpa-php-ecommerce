const newPasswordInput = document.querySelector("#update_user_password_password_first");
const newPasswordConfirmationInput = document.querySelector("#update_user_password_password_second");
const updateButton = document.querySelector("#update_user_password_submit");

function checkPassword() {
    return this.assets.validatePassword(newPasswordInput.value) && newPasswordInput.value === newPasswordConfirmationInput.value;
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