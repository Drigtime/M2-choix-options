$('#campagne_choix_parcours').on('change', function () {
    const $blocOptionContainer = $('#bloc-option-container');
    const $listBlocUE = $('#list-bloc-ue');

    // get the selected option
    const selectedOption = $(this).find('option:selected');
    // store the value of the selected option in a data attribute to later know what was the previous value
    const previousValue = $(this).data('previousValue');
    // get option with value "previousValue"
    const previousOption = $(this).find(`option[value="${previousValue}"]`);

    // store $('#bloc-option-container') as a variable on the element previousOption to later load it back when the user select the previous option
    previousOption.data('listBlocUE', $listBlocUE.clone(true));

    $(this).data('previousValue', selectedOption.val());

    const blocUEs = selectedOption.data('blocs-ue');

    if (selectedOption.data('listBlocUE') && selectedOption.data('listBlocUE').find('.card').length > 0) {
        $listBlocUE.replaceWith(selectedOption.data('listBlocUE'));
        $blocOptionContainer.show();
    } else if (blocUEs.length > 0) {
        $listBlocUE.empty();
        $listBlocUE.html(`<div id="no-bloc-ue" class="col-12">
                <div class="alert alert-primary m-0 p-2">
                    Aucun bloc option n'a encore été ajouté.
                </div>
            </div>`);
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
    $container.prepend(newWidget);
    // trigger change event on the select inside the new widget
    const $newBlocUE = $container.children().first();

    const $selectBlocUE = $newBlocUE.find('select');
    // change option based on the selected parcours
    const selectedParcours = $('#campagne_choix_parcours').find('option:selected');
    const blocUEs = selectedParcours.data('blocs-ue');
    $selectBlocUE.empty();
    blocUEs.forEach(blocUE => {
        const option = $(`<option value="${blocUE.id}">${blocUE.label}</option>`);
        option.data('ues', blocUE.ues);
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

$(document).on('change', '#list-bloc-ue select[id$="_blocUE"]', function () {
    const $uesContainerId = $(this).data('ues-container');
    const $uesContainer = $("#" + $uesContainerId);

    // list of ue are in the attribute data-ues on the option selected
    const selectedBlocUECategory = $(this).find('option:selected');

    // store the value of the selected option in a data attribute to later know what was the previous value
    const previousValue = $(this).data('previousValue');
    // get option with value "previousValue"
    const previousBlocUECategory = $(this).find(`option[value="${previousValue}"]`);

    // store $('#bloc-option-container') as a variable on the element previousBlocUECategory to later load it back when the user select the previous option
    previousBlocUECategory.data('listUEs', $uesContainer.clone());

    $(this).data('previousValue', selectedBlocUECategory.val());

    const ues = selectedBlocUECategory.data('ues');

    const index = $(this).closest('[data-bloc-ue]').data('index');

    if (selectedBlocUECategory.data('listUEs') && selectedBlocUECategory.data('listUEs').children().length > 0) {
        $uesContainer.replaceWith(selectedBlocUECategory.data('listUEs'));
    } else if (ues.length > 0) {
        $uesContainer.empty();
        ues.forEach(function (ue) {
            $uesContainer.append(`<div class="text-muted">${ue.label}</div>`);
        });
    } else {
        $uesContainer.empty();
        $uesContainer.append(`<div class="alert alert-primary" role="alert">
                                Aucune UE n'est disponible pour cette catégorie
                            </div>`);
    }
});

// trigger change event on the select inside the new widget
$('#list-bloc-ue select[id$="_blocUE"]').trigger('change');
