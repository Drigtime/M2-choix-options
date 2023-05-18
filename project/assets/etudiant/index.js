import $ from "jquery";
import {Modal} from 'bootstrap';

$(document).ready(function () {
    const $deleteModal = $('#deleteModal');

    $('a[data-delete-token]').on('click', function (e) {
        e.preventDefault();
        const $this = $(this);
        const url = $this.data('href');
        const token = $this.data('delete-token');
        const $cancelButton = $deleteModal.find('#modalCancelDeleteButton');
        const $deleteButton = $deleteModal.find('#modalConfirmDeleteButton');

        const modal = new Modal($deleteModal);
        modal.show();

        $deleteButton.off('click');
        $deleteButton.on('click', function () {
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    '_token': token
                },
                beforeSend: function () {
                    const spinner = $('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
                    $deleteButton.prepend(spinner);
                    $cancelButton.addClass('disabled');
                    $deleteButton.addClass('disabled');
                },
                complete: function (data) {
                    window.location = data.responseJSON.redirect;
                }
            });
        });
    });
});