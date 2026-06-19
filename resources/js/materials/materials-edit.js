import {checkMinLength, checkChanged, addCheckChange} from "../utilities.js";

checkMinLength('name', 'name-error', 3)
checkMinLength('description', 'description-error', 5)
addCheckChange(checkChanged);
