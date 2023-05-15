import TomSelect from "tom-select";
import 'tom-select/dist/css/tom-select.bootstrap5.min.css';
import $ from "jquery";

$(document).ready(function () {
    const $ueSelect = $('#groupe_ue');
    const $currentEffectif = $('#currentEffectif');

    function updateEffectif(currentEffectif, maxEffectif) {
        $currentEffectif.text(`${currentEffectif}/${maxEffectif}`);
    }

    const etudiantSelect = new TomSelect("#groupe_etudiants", {
        maxItems: $ueSelect.find('option:selected').data('maxEffectif'),
        onChange: function () {
            updateEffectif(this.items.length, $ueSelect.find('option:selected').data('maxEffectif'));
        }
    });

    $ueSelect.on('change', function () {
        const maxEffectif = $(this).find('option:selected').data('maxEffectif');
        etudiantSelect.settings.maxItems = maxEffectif;
        etudiantSelect.refreshItems();
        while (etudiantSelect.items.length > maxEffectif) {
            etudiantSelect.removeItem(etudiantSelect.items[etudiantSelect.items.length - 1]);
        }

        updateEffectif(etudiantSelect.items.length, maxEffectif);
    });

    $ueSelect.trigger('change');
});