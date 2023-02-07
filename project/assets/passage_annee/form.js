import $ from "jquery";

$('#parcours-table select').on('change', function () {
    const $tr = $(this).closest('tr');
    $tr.removeClass('table-warning table-danger');
    if ($(this).val() === '1') {
        $tr.addClass('table-warning');
    } else if ($(this).val() === '2') {
        $tr.addClass('table-danger');
    }
});

$("#searchInput").on("keyup search", function () {
    const value = $(this).val().toLowerCase();
    $("#parcours-table tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});
