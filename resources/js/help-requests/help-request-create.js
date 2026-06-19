import {checkMaxLength, checkMinLength, checkEmailFormat, validateOnBlur} from '../utilities.js';

validateOnBlur('email', 'check-input-email');
validateOnBlur('first_name', 'check-input-first-name');
validateOnBlur('last_name', 'check-input-last-name');
validateOnBlur('description', 'check-input-description');
validateOnBlur('title', 'check-input-title');

checkMaxLength('title', 'max-length-title-input', 50);
checkMaxLength('description', 'max-length-description-input', 400);

checkMinLength('first_name', 'min-length-first-name-input', 2);
checkMinLength('last_name', 'min-length-last-name-input', 2);

checkEmailFormat('email', 'check-format-email');
