import TomSelect from "tom-select";
import 'tom-select/dist/css/tom-select.bootstrap5.min.css';

new TomSelect("#campagne_choix_parcours", {});

$('#campagne_choix_parcours').on('change', function () {
    const $blocOptionContainer = $('#bloc-option-container');
    const $listBlocUE = $('#list-bloc-ue');

    // get the selected options
    const selectedOptions = $(this).val();
    // store the value of the selected options in a data attribute to later know what was the previous value
    const previousValue = $(this).data('previousValue');
    // get options with value "previousValue"
    const previousOptions = $(this).find(`option[value="${previousValue}"]`);

    // store $('#bloc-option-container') as a variable on the elements previousOptions to later load it back when the user select the previous option
    previousOptions.each(function () {
        $(this).data('listBlocUE', $listBlocUE.clone(true));
    });

    $(this).data('previousValue', selectedOptions);

    const parcours = selectedOptions.map((value) => $(`#campagne_choix_parcours option[value="${value}"]`).data('blocs-ue'));
    const blocUEsByCategorie = parcours.map((parcour) => parcour.blocUEs).flat().reduce((acc, blocUE) => {
        const {categorie_id, label} = blocUE;
        const categorie = acc.find((c) => c.id === categorie_id);
        if (categorie) {
            categorie.blocUEs.push(blocUE);
        } else {
            acc.push({id: categorie_id, label, blocUEs: [blocUE]});
        }
        return acc;
    }, []);

    // check if selectedOptions and previousValue are the same to avoid reloading the blocUEs
    if ((previousValue === undefined && selectedOptions.length > 0) || (previousValue !== undefined && selectedOptions.length === previousValue.length && selectedOptions.every((value, index) => value === previousValue[index]))) {
        $blocOptionContainer.show();
        return;
    }

    if (blocUEsByCategorie.length > 0) {
        $listBlocUE.empty();
        $blocOptionContainer.show();
    } else {
        $blocOptionContainer.hide();
        $listBlocUE.empty();
    }
}).change();

$('#add-bloc-ue').on('click', function () {
    let $container = $('#list-bloc-ue');
    let newWidget = $container.data('prototype');

    const $noBlocUE = $('#no-bloc-ue');
    if ($noBlocUE.length > 0) {
        $noBlocUE.remove();
    }

    newWidget = newWidget.replaceAll(/__name__/g, $container.children().length + 1);
    $container.append(newWidget);
    // trigger change event on the select inside the new widget
    const $newBlocUE = $container.children().last();

    const $selectBlocUE = $newBlocUE.find('select');
    // change option based on the selected parcours
    const selectedParcours = $('#campagne_choix_parcours').val();
    const parcours = selectedParcours.map((value) => $(`#campagne_choix_parcours option[value="${value}"]`).data('blocs-ue'));
    const blocUEsByCategorie = parcours.map((parcour) => parcour.blocUEs).flat().reduce((acc, blocUE) => {
        const {categorie_id, label} = blocUE;
        const categorie = acc.find((c) => c.id === categorie_id);

        // find in which parcours the blocUE is
        const parcour = parcours.find((parcour) => parcour.blocUEs.find((bloc) => bloc.id === blocUE.id));

        if (categorie) {
            categorie.blocUEs.push({parcour_label: parcour.label, ues: blocUE.ues});
        } else {
            acc.push({id: categorie_id, label, blocUEs: [{parcour_label: parcour.label, ues: blocUE.ues}]});
        }
        return acc;
    }, []);

    $selectBlocUE.empty();
    blocUEsByCategorie.forEach(blocUECategorie => {
        const option = $(`<option value="${blocUECategorie.id}">${blocUECategorie.label}</option>`);
        option.data('BlocUEs', blocUECategorie.blocUEs);
        $selectBlocUE.append(option);
    });

    $selectBlocUE.trigger('change');
});

$(document).on('click', '#list-bloc-ue [data-action="delete-bloc-ue"]', function () {
    $(this).closest('[data-bloc-ue]').remove();
    const $container = $('#list-bloc-ue');

    if ($container.children().length === 0) {
        const $noBlocUEPrototype = $container.data('no-bloc-ue');
        $container.append($noBlocUEPrototype);
    }
});

$(document).on('change', '#list-bloc-ue select[id$="_blocUECategory"]', function () {
    const uesContainerId = $(this).data('ues-container');
    const $uesContainer = $("#" + uesContainerId);

    // list of ue are in the attribute data-ues on the option selected
    const selectedBlocUECategory = $(this).find('option:selected');

    // store the value of the selected option in a data attribute to later know what was the previous value
    const previousValue = $(this).data('previousValue');
    // get option with value "previousValue"
    const previousBlocUECategory = $(this).find(`option[value="${previousValue}"]`);

    // store $('#bloc-option-container') as a variable on the element previousBlocUECategory to later load it back when the user select the previous option
    previousBlocUECategory.data('listUEs', $uesContainer.clone());

    $(this).data('previousValue', selectedBlocUECategory.val());

    const blocUEs = selectedBlocUECategory.data('BlocUEs');

    $uesContainer.empty();
    for (const blocUE of blocUEs) {
        const ues = blocUE.ues;
        const $newBlocUE = $('<div></div>');
        $newBlocUE.append(`<h5>${blocUE.parcour_label}</h5>`);
        const $listUE = $('<ul></ul>');
        for (const ue of ues) {
            $listUE.append(`<li>${ue.label}</li>`);
        }
        $newBlocUE.append($listUE);
        $uesContainer.append($newBlocUE);
    }
});

// trigger change event on the select inside the new widget
// $('#list-bloc-ue select[id$="_blocUECategory"]').trigger('change');
