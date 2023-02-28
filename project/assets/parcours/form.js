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
    $newBlocUE.find('select').trigger('change');
});

// for all button created or future created button with data-collection-ue-add
// add event listener on the parent element (container) and listen for click event on the button
$('#list-bloc-ue').on('click', '[data-action="delete-bloc-ue"]', function () {
    $(this).closest('[data-bloc-ue]').remove();
    const $container = $('#list-bloc-ue');

    if ($container.children().length === 0) {
        const $noBlocUEPrototype = $container.data('no-bloc-ue');
        $container.append($noBlocUEPrototype);
    }
});

$(document).on('change', '#list-bloc-ue select[id$="_blocUECategory"]', function () {
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
            $uesContainer.append(`<div class="form-check">
                                <input type="checkbox" id="parcours_blocUEs_${index}_ues_${ue.id}" name="parcours[blocUEs][${index}][ues][]" class="form-check-input" value="${ue.id}">
                                <label class="form-check-label" for="parcours_blocUEs_${index}_ues_${ue.id}">${ue.label}</label>
                            </div>`);
        });
    } else {
        $uesContainer.empty();
        $uesContainer.append(`<div class="alert alert-info" role="alert">
                                Aucune UE n'est disponible pour cette catégorie
                            </div>`);
    }
});