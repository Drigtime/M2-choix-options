import $ from "jquery";
import {Modal} from "bootstrap";

$(function () {
    const formModal = new Modal('#form-modal')

    $('.bloc-ue-selection-tab').on('shown.bs.tab', function () {
        const $tabContainer = $($(this).data("bs-target"));
        const btnSelection = $('.btn-selection.active', $tabContainer);
        btnSelection.trigger('show.bs.tab');
    });

    $(".btn-selection").on('show.bs.tab', function (e) {
        const $tabContainer = $($(this).data("bs-target"));
        const $etudiantContainer = $('#etudiant_container', $tabContainer);
        const $groupeContainer = $('#groupe_container', $tabContainer);
        $('#etudiant_container tbody').empty();
        const url = $(this).data("url");
        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            beforeSend: function () {
                $etudiantContainer.toggleClass('d-none', true);
                $groupeContainer.toggleClass('d-none', true);

                const loadingSpinner = '<div class="loading-spinner p-4 w-100 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                $etudiantContainer.after(loadingSpinner);
                $groupeContainer.after(loadingSpinner);
            },
            success: function (data) {
                const tableEtudiant = $('tbody', $etudiantContainer);
                const tableGroupe = $('tbody', $groupeContainer);
                const campagne_id = $('#campagne_id').val();
                tableEtudiant.empty();
                tableGroupe.empty();
                let ue = ''
                if (data[1].length === 0) {
                    tableEtudiant.append(
                        '<tr class="table-info">' +
                        '<td colspan="5" class="text-center">Aucun étudiant disponible</td>' +
                        '</tr>'
                    );
                } else {
                    $.each(data[1], function (index, etudiant) {
                        tableEtudiant.append(
                            '<tr>' +
                            '<td><input class="form-check-input selectionEtudiant" type="checkbox" id="' + etudiant.id + '" value="' + etudiant.id + '" name="selectionEtudiant[]"></td>' +
                            '<td>' + etudiant.nom + '</td>' +
                            '<td>' + etudiant.prenom + '</td>' +
                            '<td>' + etudiant.parcours + '</td>' +
                            '<td>' + etudiant.ordre + '</td>' +
                            '</tr>'
                        );
                        ue = etudiant.ue;
                    });
                }
                if (data[0].length === 0) {
                    tableGroupe.append(
                        '<tr class="table-info">' +
                        '<td colspan="5" class="text-center">Aucun groupe disponible</td>' +
                        '</tr>'
                    );
                } else {
                    $.each(data[0], function (index, etudiant) {
                        tableGroupe.append(
                            '<tr>' +
                            '<td><a href="#" data-href="/admin/campagne_choix/delete_etudiant_groupe/' + campagne_id + '/' + etudiant.groupe_id + '/' + etudiant.id + '" class="delete-etudiant btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i></a></td>' +
                            '<td>' + etudiant.nom + '</td>' +
                            '<td>' + etudiant.prenom + '</td>' +
                            '<td>' + etudiant.groupe_label + '</td>' +
                            '<td>' + etudiant.parcours + '</td>' +
                            '</tr>'
                        );
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("error request");
            },
            complete: function () {
                // remove spinner
                $('.loading-spinner').remove();
                $etudiantContainer.toggleClass('d-none', false);
                $groupeContainer.toggleClass('d-none', false);
            }
        });
    });

    $('#form-modal').on('show.bs.modal', function (event) {
        // get data from button trigger
        const button = $(event.relatedTarget);
        const table = button.parent().find('table');
        const selected = $('.selectionEtudiant:checked', table);
        const modal = $(this);
        const url = button.data('url');

        $.ajax({
            url: url,
            type: 'GET',
            contentType: 'application/json',
            beforeSend: function () {
                modal.find('.modal-body').html('<div class="loading-spinner p-4 w-100 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            success: function (data) {
                const groupes = data.groupes;

                const modalBody = modal.find('.modal-body');
                modalBody.empty();

                if (groupes.length === 0) {
                    modalBody.append('<div class="alert alert-info" role="alert">Aucun groupe disponible</div>');
                } else {
                    const form = $('<form></form>');

                    $.each(groupes, function (index, groupe) {
                        const formCheck = $('<div class="form-check"></div>');
                        const input = $('<input class="form-check-input" type="radio" id="flexRadioDefault' + index + '" name="choixGroupes" value="' + groupe.id + '">');
                        const label = $('<label class="form-check-label" for="flexRadioDefault' + index + '">' + groupe.label + ' (' + groupe.currentEffectif + '/' + groupe.maxEffectif + ')</label>');

                        formCheck.append(input);
                        formCheck.append(label);

                        form.append(formCheck);
                    });

                    modalBody.append(form);

                    $('form', modalBody).on('submit', function (event) {
                        event.preventDefault();

                        // if form is not valid stop here
                        if ($(this).serialize() === '') {
                            return false;
                        }

                        let data = $(this).serialize();

                        $.each(selected, function (index, value) {
                            data += '&selectionEtudiant[]=' + value.value;
                        });

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: data,
                            beforeSend: function () {
                                modalBody.html('<div class="loading-spinner p-4 w-100 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                            },
                            success: function (data) {
                                modalBody.html('<div class="alert alert-success" role="alert">Etudiants ajoutés avec succès</div>');
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                console.log("error request");
                            },
                            complete: function () {
                                formModal.hide();
                                modalBody.empty();
                                $('.bloc-ue-selection-tab.active').trigger('shown.bs.tab');
                            },
                        });
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("error request");
            },
            complete: function () {
                // remove spinner
                $('.loading-spinner').remove();
            },
        });
    });

    $('#form-modal .modal-footer button[type="submit"]').on('click', function () {
        $('#form-modal form').submit();
    });

    $(".first_section").click(function () {
        $('#etudiant_container tbody').empty();
    });

    // trigger show.bs.tab on active tab
    $('.bloc-ue-selection-tab.active').trigger('shown.bs.tab');

    $(document).on('click', '.delete-etudiant', function (event) {
        event.preventDefault();

        const url = $(this).data('href');
        const button = $(this);

        $.ajax({
            url: url,
            type: 'DELETE',
            contentType: 'application/json',
            beforeSend: function () {
                button.html('<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>');
            },
            success: function (data) {
                $('.bloc-ue-selection-tab.active').trigger('shown.bs.tab');
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("error request");
            },
            complete: function () {
                button.html('<i class="fa-solid fa-xmark"></i>');
            }
        });
    });
});

$(document).on('change', 'input[type="checkbox"][name="selectionEtudiant[]"]', function () {
    if ($('input[type="checkbox"][name="selectionEtudiant[]"]:checked').length > 0) {
        $('.choixGroupeBtn').prop('disabled', false);
    } else {
        $('.choixGroupeBtn').prop('disabled', true);
    }

});

$(document).on('change', 'input[type="checkbox"][name="selection_groupe[]"]', function () {
    if ($('input[type="checkbox"][name="selection_groupe[]"]:checked').length > 0) {
        $('#delGroupeBtn').prop('disabled', false);
    } else {
        $('#delGroupeBtn').prop('disabled', true);
    }
});









