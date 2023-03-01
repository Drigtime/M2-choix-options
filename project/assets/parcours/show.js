// there is a form that is loaded through ajax and placed in a modal.
// the form has a select field with a list of bloc_ue, the id is bloc_option_blocUE
// I want to listen to the change event on this select field


$(document).on('change', '#bloc_ue_blocUECategory', function () {
    // get selected option
    const selectedBlocUECategory = $(this).find('option:selected');
    // get the value of the selected option
    const ues = selectedBlocUECategory.data('ues');
    const $blocUeUes = $('#bloc_ue_ues');
    $blocUeUes.empty();
    if (ues.length > 0) {
        ues.forEach(function (ue) {
            $blocUeUes.append(`<div class="form-check">
                        <input type="checkbox" id="bloc_ue_ues_${ue.id}" name="bloc_ue[ues][]" class="form-check-input" value="${ue.id}">
                        <label class="form-check-label" for="bloc_ue_ues_${ue.id}">${ue.label}</label>
                    </div>`);
        });
    } else {
        $blocUeUes.append(`<div class="alert alert-warning" role="alert">
                    Aucune UE n'est disponible pour ce bloc.
                </div>`);
    }
});

// on #modalTemp is shown, the form is loaded, so I need to trigger the change event on the select field
$(document).on('show.bs.modal', '#modalTemp', function () {
    // if modal as data 'custom' and it(s equal to "editBlocOption" then do nothing
    const custom = $(this).data('custom');
    if (custom && custom === 'editBlocUECategory') {
        return;
    }
    $('#bloc_ue_blocUECategory').trigger('change');
});