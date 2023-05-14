import {Modal} from "bootstrap";
import $ from "jquery";

const modal = new Modal(document.querySelector('#modal'));

$('a[data-has-active-campagne]').on('click', function (e) {
    e.preventDefault();
    const $this = $(this);
    const hasActiveCampagneChoix = $this.data('has-active-campagne');
    // if data is true, then do the default action (go to the link)
    if (!hasActiveCampagneChoix) {
        window.location.href = $this.attr('href');
    } else {
        // set modal title and body
        const $modal = $(modal._element);
        $modal.find('.modal-title').text('Attention');
        $modal.find('.modal-body').html(`
            <p>Ce parcours à des campagnes de choix actives. Êtes-vous sûr de vouloir le modifier ?</p>
            <p>L'ajout ou la suppression de bloc UEs ou d'UEs aura un impact sur les choix des étudiants.</p> 
        `);
        $modal.find('.modal-footer .btn-primary').on('click', function () {
            window.location.href = $this.attr('href');
        });

        modal.show();

    }
});