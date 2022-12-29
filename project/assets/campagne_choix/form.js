// Il y a plusiers étapes à suivre pour créer un formulaire
// 1. Selectionne du parcours dans la liste déroulante (select) #campagne_choix_parcours
//    Lorsque l'on sélectionne un parcours, on doit afficher les blocs UEs associés à ce parcours
//    Pour cela, on va utiliser l'API de Symfony qui va nous permettre de récupérer les blocs UEs
// 2. Selectionne des blocs UEs dans la liste déroulante (select) #campagne_choix_bloc_option
//    Lorsque l'on sélectionne un bloc UE, on doit afficher les UEs associés à ce bloc UE
//    Pour cela, on va utiliser l'API de Symfony qui va nous permettre de récupérer les UEs

import Routing from "fos-router";

function selectBlocOptionOnChange() {
    const $selectUE = $(this).parent().parent().find('select[id$="_UE"]');
    $selectUE.empty();
    if (this.value === '') {
        return;
    }

    $selectUE.attr('disabled', true);
    $
        .get(Routing.generate('api_bloc_ue_ues', {blocUE: this.value}), function (data) {
            $selectUE.empty();
            data.forEach(function (ue) {
                $selectUE.append(`<option value="${ue.id}">${ue.label}</option>`);
            });

            $selectUE.attr('disabled', false);
        });
}

$(document).ready(function () {
    // On récupère le select #campagne_choix_parcours
    const $selectParcours = $('#campagne_choix_parcours');
    $selectParcours.on('change', function () {
        // if selected option has value
        if (this.value === '') {
            $('#campagne_choix_bloc_option').addClass('d-none');
            $('#bloc-option-list').empty();
            return;
        }

        // On grise le select #campagne_choix_bloc_option le temps de la requête AJAX
        $selectParcours.attr('disabled', true);

        // On récupère l'id du parcours sélectionné
        let idParcours = $(this).val();

        // On récupère l'url de l'API
        let url = Routing.generate('api_parours_bloc_ue', {parcours: idParcours});

        // On récupère les blocs UEs associés au parcours sélectionné
        $
            .get(url, function (data) {
                // set data on select
                $selectParcours.data('blocues', data);

                // on affiche les blocs UEs dans le select #campagne_choix_bloc_option
                $('#campagne_choix_bloc_option').removeClass('d-none');
            })
            .always(function () {
                // On dégrise le select #campagne_choix_bloc_option
                $selectParcours.attr('disabled', false);
            })
    })

    const $addBlocOptionBtn = $('#add-bloc-option');
    $addBlocOptionBtn.on('click', function () {
        const $container = $('#bloc-option-list');
        let newWidget = $container.data('prototype')
        const prototypeName = $container.data('prototype-name');
        const index = $container.children().length;
        newWidget = newWidget.replace(new RegExp(prototypeName, 'g'), index);
        // create new element from html string
        const $newWidget = $(newWidget);
        // fill campagne_choix_blocOptions_0_blocUE with bloc UEs
        const $selectBlocOption = $newWidget.find(`#campagne_choix_blocOptions_${index}_blocUE`);
        $selectBlocOption.empty();
        const blocUes = $selectParcours.data('blocues');
        blocUes.forEach(function (blocUE) {
            $selectBlocOption.append(`<option value="${blocUE.id}">${blocUE.label}</option>`);
        });
        // add new element to container
        $container.append($newWidget);

        // add event listener on delete button
        $newWidget.find('[data-action="delete-bloc-option"]').on('click', function () {
            $newWidget.remove();
        });

        // add event listener on select bloc UE
        $selectBlocOption.on('change', selectBlocOptionOnChange).trigger('change');
    })

    // Pour le formulaire d'édition d'une campagne de choix, il faut obtenir tout les campagne_choix_blocOptions_0_blocUE et charger les UEs associés dans campagne_choix_blocOptions_0_UE
    const $selectBlocOption = $('#bloc-option-list select[id$="_blocUE"]');
    $selectBlocOption.on('change', selectBlocOptionOnChange);
    const $deleteBlocOptionBtn = $('#bloc-option-list [data-action="delete-bloc-option"]');
    $deleteBlocOptionBtn.on('click', function () {
        // find closest data-bloc-option
        const $blocOption = $(this).closest('[data-bloc-option]');
        $blocOption.remove();
    });

});