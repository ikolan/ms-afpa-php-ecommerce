const REGEX_PASSWORD = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/;
const REGEX_USER_NAME = /^[A-Za-z\-\ \']{1,}$/;

export function validatePassword(password) {
    return REGEX_PASSWORD.test(password)
}

export function validateUserName(userName) {
    return REGEX_USER_NAME.test(userName);
}