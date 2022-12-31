// there is a form that is loaded through ajax and placed in a modal.
// the form has a select field with a list of bloc_ue, the id is bloc_option_blocUE
// I want to listen to the change event on this select field
import Routing from "fos-router";

$(document).on('change', '#bloc_option_blocUE', function () {
    // get selected option
    const selectedBlocUE = $(this).find('option:selected');
    // get the value of the selected option
    const selectedBlocUEId = selectedBlocUE.val();

    const url = Routing.generate('api_bloc_ue_ues', {blocUE: selectedBlocUEId});
    $
        .get(url, function (data) {
            const $selectUE = $('#bloc_option_UE');
            $selectUE.empty();
            data.forEach(function (ue) {
                $selectUE.append(`<div class="form-check">
                    <input type="checkbox" id="bloc_option_UE_${ue.id}" name="bloc_option[UE][]" class="form-check-input" value="${ue.id}">
                    <label class="form-check-label" for="bloc_option_UE_${ue.id}">${ue.label}</label>
                </div>`);
            });
        });
});

// on #modalTemp is shown, the form is loaded, so I need to trigger the change event on the select field
$(document).on('show.bs.modal', '#modalTemp', function () {
    // if modal as data 'custom' and it(s equal to "editBlocOption" then do nothing
    const custom = $(this).data('custom');
    if (custom && custom === 'editBlocOption') {
        return;
    }
    $('#bloc_option_blocUE').trigger('change');
});