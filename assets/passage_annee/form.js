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

    // const dt =  $('table').DataTable({
    //     language: languageFR,
    //     // hide next/previous buttons if there is only one page
    //     "initComplete": function(settings, json) {
    //         if (this.api().page.info().pages <= 1) {
    //             $('.pagination', this.api().table().container()).hide();
    //         }
    //     }
    // });
    //
    // // on form submit, if the table has multiple pages, their is only le displayed page that is submitted, so we need to add the hidden inputs for the other pages
    // $('form').on('submit', function () {
    //     const nbPages = dt.page.info().pages;
    //     if (nbPages > 1) {
    //         // get inputs from other pages and add them to the form in hidden state
    //         for (let i = 1; i < nbPages; i++) {
    //             const page = dt.page(i).nodes().to$();
    //             page.find('input').each(function () {
    //                 $(this).attr('type', 'hidden');
    //                 $(this).attr('name', $(this).attr('name') + '[]');
    //                 $(this).appendTo('form');
    //             });
    //         }
    //     }
    // });
});