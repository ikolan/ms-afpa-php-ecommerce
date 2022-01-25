const passwordInput = document.querySelector("#user_registration_password_first");
const passwordConfirmationInput = document.querySelector("#user_registration_password_second");
const registrationButton = document.querySelector("#user_registration_submit");

function checkPassword() {
    return this.assets.validatePassword(passwordInput.value) && passwordInput.value === passwordConfirmationInput.value;
}

function validate() {
    valid = checkPassword();

    if (valid) {
        registrationButton.removeAttribute("disabled");
    } else {
        registrationButton.setAttribute("disabled", true);
    }
}

registrationButton.setAttribute("disabled", true);