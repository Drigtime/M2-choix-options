import TomSelect from "tom-select";
import 'tom-select/dist/css/tom-select.bootstrap5.min.css';

new TomSelect("#campagne_choix_parcours", {});

$(document).ready(function () {
    const addBlocUEButton = $("#add-bloc-ue");
    const blocUEContainer = $("#bloc-ue-container");
    const selectChoixParcours = $("#campagne_choix_parcours");

    const prototypeBlocUE = blocUEContainer.data("prototypeBlocUe");
    const prototypeBlocOption = blocUEContainer.data("prototypeBlocOption");
    const prototypeNoBlocUE = blocUEContainer.data("prototypeNoBlocUe");
    const prototypeParcoursNoUes = blocUEContainer.data("prototypeParcoursNoUes");

    let blocUEIndex = $('[data-bloc-ue]').length;
    let blocUEsByCategories = [];

    function updateBlocUEsByCategories(parcours) {
        blocUEsByCategories = parcours.map((parcour) => parcour.blocUEs).flat().reduce((acc, blocUE) => {
            const {categorie_id, label} = blocUE;
            const categorie = acc.find((c) => c.id === categorie_id);

            // find in which parcours the blocUE is
            const parcour = parcours.find((parcour) => parcour.blocUEs.find((bloc) => bloc.id === blocUE.id));

            if (categorie) {
                categorie.blocUEs.push({id: blocUE.id, parcours_label: parcour.label, parcours_id: parcour.id, ues: blocUE.ues});
            } else {
                acc.push({
                    id: categorie_id,
                    label,
                    blocUEs: [{id: blocUE.id, parcours_label: parcour.label, parcours_id: parcour.id, ues: blocUE.ues}]
                });
            }
            return acc;
        }, []);
    }

    function updateSelectBlocUECategoryOptions(selectBlocUECategory) {
        const selectedOption = selectBlocUECategory.data('blocUeCategory')
        selectBlocUECategory.empty();
        blocUEsByCategories.forEach((blocUECategory) => {
            const option = $("<option></option>");
            option.val(blocUECategory.id);
            option.text(blocUECategory.label);
            option.data('blocUEs', blocUECategory.blocUEs);
            selectBlocUECategory.append(option);
        });
        // check if the selectedOption is still available in selectBlocUECategory, if so restore the selected option
        const selectedOptionsAvailable = blocUEsByCategories.find((blocUECategory) => blocUECategory.id === selectedOption);
        if (selectedOptionsAvailable) {
            selectBlocUECategory.val(selectedOption);
        }
    }


    // Ajouter un bloc UE
    function addBlocUE() {
        const newBlocUE = $(prototypeBlocUE.replace(/__name__/g, blocUEIndex + 1));

        const deleteButton = newBlocUE.find('[data-action="delete-bloc-ue"]');
        deleteButton.on("click", removeBlocUE);

        // Ajouter les catégories de blocs UE
        const selectBlocUECategory = newBlocUE.find('[data-bloc-ue-category]');
        updateSelectBlocUECategoryOptions(selectBlocUECategory);
        selectBlocUECategory.on("change", onBlocUECategoryChange);

        blocUEContainer.append(newBlocUE);
        selectBlocUECategory.trigger("change");

        blocUEIndex++;
    }

    // Supprimer un bloc UE
    function removeBlocUE(event) {
        const deleteButton = $(event.target).closest("button");
        const blocUE = deleteButton.closest('[data-bloc-ue]');
        blocUE.remove();

        if (blocUEContainer.children().length === 0) {
            const noBlocUE = $("<div></div>");
            noBlocUE.html(prototypeNoBlocUE);
            blocUEContainer.append(noBlocUE);
        }
    }

    function onCampagneParcoursChange() {
        const $blocOptionContainer = $('#bloc-option-container');
        const $listBlocUE = $('#list-bloc-ue');

        const selectedOptions = $(this).val();

        const parcours = selectedOptions.map((value) => $(`#campagne_choix_parcours option[value="${value}"]`).data('blocs-ue'));
        updateBlocUEsByCategories(parcours);

        $('[data-bloc-ue-category]').each(function () {
            updateSelectBlocUECategoryOptions($(this));
        });

        if (blocUEsByCategories.length > 0) {
            $listBlocUE.empty();
            $blocOptionContainer.show();
        } else {
            $blocOptionContainer.hide();
            $listBlocUE.empty();
        }
    }

    function onBlocUECategoryChange() {
        const selectBlocUECategory = $(this);
        selectBlocUECategory.data('blocUeCategory', selectBlocUECategory.val());
        const blocUECategory = selectBlocUECategory.find('option:selected');
        const blocUEs = blocUECategory.data('blocUEs');

        const blocUEContainer = selectBlocUECategory.closest('[data-bloc-ue]');
        const blocOptionContainer = blocUEContainer.find('[data-bloc-options-container]');
        const blocOptionContainerDisplay = blocUEContainer.find('[data-bloc-options-container-display]');
        blocOptionContainer.empty();
        blocOptionContainerDisplay.empty();

        let blocOptionIndex = $("#bloc-ue-container").data('bloc-option-index');
        blocUEs.forEach((blocUE) => {
            const newBlocOption = $(prototypeBlocOption.replace(/__name__/g, blocOptionIndex));
            const blocOptionBlocUE = newBlocOption.find('[name$="[blocUE]"]');
            const blocOptionParcours = newBlocOption.find('[name$="[parcours]"]');
            blocOptionBlocUE.val(blocUE.id);
            blocOptionParcours.val(blocUE.parcours_id);

            const listUE = $('<ul></ul>');
            blocUE.ues.forEach((ue) => {
                listUE.append(`<li>${ue.label}</li>`);
            });
            blocOptionContainerDisplay.append(`<h5>${blocUE.parcours_label}</h5>`);
            if (blocUE.ues.length > 0) {
                blocOptionContainerDisplay.append(listUE);
                blocOptionContainer.append(newBlocOption);
                blocOptionIndex++;
            } else {
                blocOptionContainerDisplay.append(prototypeParcoursNoUes);
            }
        });
        $("#bloc-ue-container").data('bloc-option-index', blocOptionIndex);
    }


    // Gestionnaire d'événements pour le select des parcours
    selectChoixParcours.on('change', onCampagneParcoursChange);

    // Gestionnaire d'événements pour ajouter un bloc UE
    addBlocUEButton.on("click", addBlocUE);

    // Gestionnaire d'événements pour supprimer un bloc UE existant
    $('[data-action="delete-bloc-ue"]').each(function () {
        $(this).on("click", removeBlocUE);
    });

    // Gestionnaire d'événements pour le select des catégories de blocs UE
    $('[data-bloc-ue-category]').each(function () {
        $(this).on("change", onBlocUECategoryChange);
    });

    // Déclencher l'événement pour mettre à jour les blocs UE
    selectChoixParcours.trigger('change');

    // Déclencher l'événement pour mettre à jour les blocs options
    $('[data-bloc-ue-category]').each(function () {
        $(this).trigger('change');
    });
});
