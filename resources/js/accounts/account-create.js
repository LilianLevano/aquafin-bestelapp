import {
    checkEmail,
    setValidity,
    checkPhoneNumber,
    checkName,
    checkPassword,
    checkPasswordMatch,
    togglePassword
} from "../utils.js";

checkEmail('email', setValidity);
checkPhoneNumber('phone_number', setValidity)
checkName('first_name', setValidity)
checkName('last_name', setValidity)
checkPassword('password', setValidity)
checkPasswordMatch('password_confirmation', 'password', setValidity)
window.togglePw = function(inputId, btn) {
    togglePassword(inputId, btn)
}
