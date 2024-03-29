import {Toast} from "bootstrap";
import $ from "jquery";

const toastSuccess = new Toast(document.querySelector('#toastSuccess'));
const toastError = new Toast(document.querySelector('#toastError'));

function show(toast, option) {
    const {title, message, time} = option;
    const $toast = $(toast._element);
    $toast.find('.toast-header .toast-title').text(title);
    $toast.find('.toast-body').text(message);

    toast._config.delay = time ?? 5000;

    toast.show();
}

export function showSuccess(option) {
    show(toastSuccess, option);
}

export function showError(option) {
    show(toastError, option);
}
