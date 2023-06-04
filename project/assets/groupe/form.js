import $ from "jquery";
import Routing from 'fos-router';
import {Modal} from 'bootstrap';
import "datatables.net-bs5";
import languageFR from 'datatables.net-plugins/i18n/fr-FR.json';

$(document).ready(function () {
    const $ueSelect = $('#groupe_ue');
    const $groupeEtudiants = $('#groupe_etudiants');
    const $currentEffectif = $('#currentEffectif');
    const $etudiantsTableTbody = $('#etudiants');
    const addEtudiantModal = new Modal(document.getElementById('addEtudiantModal'));
    const $addEtudiantModalFormResult = $('#addEtudiantModalFormResult');

    function updateEffectifAndRefreshTable() {
        const selectedOptions = $groupeEtudiants.find('option:selected');
        const currentEffectif = selectedOptions.length;
        const maxEffectif = $ueSelect.find('option:selected').attr('data-max-effectif');
        $currentEffectif.text(`${currentEffectif}/${maxEffectif}`);
        refreshEtudiantsTable(selectedOptions);
    }

    function refreshEtudiantsTable(selectedOptions) {
        const etudiants = selectedOptions.map(function () {
            return {
                id: $(this).val(),
                nom: $(this).text(),
            };
        }).get();

        const rowsHtml = etudiants.map(function (etudiant) {
            return `
            <tr>
                <td>${etudiant.nom}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-danger btn-sm removeEtudiant" data-id="${etudiant.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        }).join('');

        $etudiantsTableTbody.html(rowsHtml);
    }

    function removeEtudiantById(id) {
        $groupeEtudiants.find(`option[value="${id}"]`).prop('selected', false).end();
        updateEffectifAndRefreshTable();
    }

    function addEtudiantById(id) {
        $groupeEtudiants.find(`option[value="${id}"]`).prop('selected', true).end();
        updateEffectifAndRefreshTable();
    }

    $etudiantsTableTbody.on('click', '.removeEtudiant', function () {
        const id = $(this).attr('data-id');
        removeEtudiantById(id);
    });

    $('#addEtudiant').on('click', function () {
        addEtudiantModal.show();
    });

    $ueSelect.on('change', updateEffectifAndRefreshTable);

    $addEtudiantModalFormResult.on('click', '.addEtudiantModalFormResultTableAdd', function () {
        addEtudiantModal.hide();
        const etudiantId = $(this).data('id');
        addEtudiantById(etudiantId);
    });

    $('#addEtudiantModalFormSearch').on('click', function () {
        const $this = $(this);
        const parcours = $('#addEtudiantModalFormParcours').val();
        const nom = $('#addEtudiantModalFormNom').val();
        const prenom = $('#addEtudiantModalFormPrenom').val();

        $.ajax({
            url: Routing.generate('api_etudiant_search'),
            type: 'POST',
            dataType: 'json',
            data: {
                parcours: parcours,
                nom: nom,
                prenom: prenom,
            },
            beforeSend: function () {
                $this.attr('disabled', true);
                $this.data('original-text', $this.html());
                $this.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            },
            success: function (data) {
                const $table = $('<table></table>');
                $table.attr('id', 'addEtudiantModalFormResultTable');
                $table.append('<thead><tr><th>Nom</th><th>Prénom</th><th>Parcours</th><th></th></tr></thead>');
                $table.append('<tbody></tbody>');

                data.forEach(function (etudiant) {
                    const $tr = $('<tr></tr>');
                    $tr.append(`<td>${etudiant.label.split(' ')[0]}</td>`);
                    $tr.append(`<td>${etudiant.label.split(' ')[1]}</td>`);
                    $tr.append(`<td>${etudiant.parcours.label}</td>`);
                    $tr.append(`<td><button class="btn btn-primary addEtudiantModalFormResultTableAdd" data-id="${etudiant.id}">Ajouter</button></td>`);
                    $table.find('tbody').append($tr);
                });

                $addEtudiantModalFormResult.html($table);

                $table.css('width', '100%');
                $table.DataTable({
                    language: languageFR,
                    info: false,
                    searching: false,
                    columnDefs: [
                        {"orderable": false, "targets": 3},
                        {"width": "0%", "targets": 3},
                    ],
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // TODO
            },
            complete: function () {
                $this.attr('disabled', false);
                $this.html($this.data('original-text'));
                $this.removeData('original-text');
            },
        })
    });

    $ueSelect.trigger('change');
});
