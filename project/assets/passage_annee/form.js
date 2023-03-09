import $ from "jquery";
import DataTable from 'datatables.net-bs5';
import languageFR from 'datatables.net-plugins/i18n/fr-FR.json';
$(document).ready(function () {
    const $tableSelects = $('table select');

    $tableSelects.on('change', function () {
        const $tr = $(this).closest('tr');
        $tr.removeClass('table-warning table-danger');
        if ($(this).val() === '1') {
            $tr.addClass('table-warning');
        } else if ($(this).val() === '2') {
            $tr.addClass('table-danger');
        }
    });
    //
    // $("#searchInput").on("keyup search", function () {
    //     const value = $(this).val().toLowerCase();
    //     $("#parcours-table tbody tr").filter(function () {
    //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    //     });
    // });

    $tableSelects.trigger('change');

    $('table').DataTable({
        language: languageFR,
    });
});