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

$(document).on('click', '[data-action="add-ue"]', function () {
    const target = $(this).data('target');
    const $container = $(target);
    let newWidget = $container.data('prototype');

    newWidget = newWidget.replaceAll(/__ues__/g, $container.children().length + 1);
    $container.prepend(newWidget);
    // trigger change event on the select inside the new widget
    // const $newUE = $container.children().first();
    // $newUE.find('select').trigger('change');
});

$(document).on('click', '[data-action="delete-ue"]', function () {
    $(this).parent().remove();
});

function updateAvailableUE() {
    const $blocUECategories = $('#list-bloc-ue select[id$="_blocUECategory"]');
    $blocUECategories.each(function (index, blocUECategory) {
        const $blocUECategory = $(blocUECategory);
        const ueContainers = $blocUECategory.data('ues-container');

        for (const ueContainer of ueContainers) {
            const $ueContainer = $('#' + ueContainer);
            // get the data-prototype from the container and remove option from the first select to only display UE of the selected category
            let newWidget = $ueContainer.data('original-prototype');

            const $newWidget = $(newWidget);
            const $selectUE = $newWidget.find('select');

            const selectedBlocUECategory = $blocUECategory.find('option:selected');
            const ues = selectedBlocUECategory.data('ues'); // ues is an array of object with id and label. ex: [{id: 1, label: 'UE 1'}, {id: 2, label: 'UE 2'}]

            $selectUE.find('option').each(function () {
                // remove all option which value is not in the ues array id property
                const ueId = $(this).val();
                if (!ues.some(ue => ue.id === parseInt(ueId)) && ueId !== '') {
                    $(this).remove();
                }
            });

            // replace the prototype with the new one
            $ueContainer.data('prototype', $newWidget.prop('outerHTML'));

            // update the select with the new options (only the UE of the selected category)
            const $selectUEContainer = $ueContainer.find('select');
            // update select options while keeping the selected value
            $selectUEContainer.each(function (index, selectUEContainer) {
                const $selectUEContainer = $(selectUEContainer);
                const selectedUE = $selectUEContainer.find('option:selected');
                $selectUEContainer.html($selectUE.html());
                $selectUEContainer.val(selectedUE.val());
            });
        }
    });
}


$(document).on('change', '#list-bloc-ue select[id$="_blocUECategory"]', function () {
    const ueContainer = $(this).data('ues-container');
    const $ueContainer = $('#' + ueContainer);

    updateAvailableUE();

    // remove all UE of the bloc UE
    $ueContainer.children().remove();
});

updateAvailableUE();