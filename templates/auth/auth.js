const passwordInput = document.querySelector("#user_registration_password_first");
const passwordConfirmationInput = document.querySelector("#user_registration_password_second");
const registrationButton = document.querySelector("#user_registration_submit");

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