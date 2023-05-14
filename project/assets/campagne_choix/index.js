import {Modal} from "bootstrap";
import $ from "jquery";

const modal = new Modal(document.querySelector('#modal'));

$('a[data-can-be-edited]').on('click', function (e) {
    e.preventDefault();
    const $this = $(this);
    const canBeEdited = $this.data('can-be-edited');
    const cantBeEditedReason = $this.data('cant-be-edited-reason'); // json string
    // if data is true, then do the default action (go to the link)
    if (canBeEdited) {
        window.location.href = $this.attr('href');
    } else {
        // set modal title and body
        const $modal = $(modal._element);
        $modal.find('.modal-title').text('Attention');
        if (cantBeEditedReason) {
            $modal.find('.modal-body').text('Modifier cette campagne peux être dangereux, car :');
            const $ul = $('<ul></ul>');
            for (const reason of cantBeEditedReason) {
                $ul.append(`<li>${reason}</li>`);
            }
            $modal.find('.modal-body').append($ul);
        } else {
            $modal.find('.modal-body').text('Modifier cette campagne peux être dangereux. Des actions peuvent être en cours.');
        }
        $modal.find('.modal-footer .btn-primary').on('click', function () {
            window.location.href = $this.attr('href');
        });

        modal.show();

    }
});