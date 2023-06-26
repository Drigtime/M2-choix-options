import Sortable from 'sortablejs';
import {showError, showSuccess} from '../toast'
import $ from "jquery";

$(document).ready(function () {
    const blocsOptions = $('.choices');
    blocsOptions.each(function () {
        const blocOptionsId = $(this).data('id');
        new Sortable(this, {
            group: blocOptionsId,
            animation: 150,
            onEnd: function () {
                const $choixes = $('[id="' + blocOptionsId + '"] > .choix > span');
                $choixes.each(function (i, _) {
                    $(this).text("Choix n°" + (i + 1));
                });
            },
            store: {
                set: function (sortable) {
                    const choix = {"blocOptionsId": blocOptionsId, "ordre": sortable.toArray()};
                    const $campagne = $('#campagneId');
                    $.ajax({
                        url: "/etudiant/options/" + $campagne.data('campagneId') + "/edit",
                        type: "POST",
                        data: JSON.stringify(choix),
                        contentType: "application/json",
                        dataType: "json",
                        success: function (data) {
                            showSuccess({
                                title: 'Choix d\'options',
                                message: data.message,
                                time: 1500
                            });
                        },
                        error: function (data) {
                            showError({
                                title: 'Choix d\'options',
                                message: data.message,
                                time: 1500
                            });
                        }
                    });
                }
            },
        });
    });
});
