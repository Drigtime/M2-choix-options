$('#campagne_choix_parcours').on('change', function () {
    // get the selected option
    const selectedOption = $(this).find('option:selected');
    const blocUEs = selectedOption.data('blocs-ue');

    if (blocUEs.length > 0) {
        // show the blocs
        $('#bloc-option-container').show();
        // empty the blocs select
        const listBlocUE = $('#list-bloc-ue');
        listBlocUE.empty();

        listBlocUE.html(`<div id="no-bloc-ue" class="col-12">
                <div class="alert alert-info m-0 p-2">
                    Aucun bloc option n'a encore été ajouté.
                </div>
            </div>`);
    } else {
        // hide the blocs
        $('#bloc-option-container').hide();
    }
});

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

$('#list-bloc-ue').on('click', '[data-action="delete-bloc-ue"]', function () {
    $(this).closest('[data-bloc-ue]').remove();
    const $container = $('#list-bloc-ue');

    if ($container.children().length === 0) {
        const $noBlocUEPrototype = $container.data('no-bloc-ue');
        $container.append($noBlocUEPrototype);
    }
});

$(document).on('change', 'select[id$="_blocUE"]', function () {
    // list of ue are in the attribute data-ues on the option selected
    const selectedBlocUECategory = $(this).find('option:selected');
    const ues = selectedBlocUECategory.data('ues');
    const $uesContainerId = $(this).data('ues-container');
    const $uesContainer = $("#" + $uesContainerId);

    const index = $(this).closest('[data-bloc-ue]').data('index');

    $uesContainer.empty();

    if (ues.length > 0) {
        ues.forEach(function (ue) {
            $uesContainer.append(`<div class="form-check">
                                <input type="checkbox" id="campagne_choix_blocOptions_${index}_UEs_${ue.id}" name="campagne_choix[blocOptions][${index}][UEs][]" class="form-check-input" value="${ue.id}">
                                <label class="form-check-label" for="campagne_choix_blocOptions_${index}_UEs_${ue.id}">${ue.label}</label>
                            </div>`);
        });
    } else {
        $uesContainer.append(`<div class="alert alert-info" role="alert">
                                Aucune UE n'est disponible pour cette catégorie
                            </div>`);
    }
});