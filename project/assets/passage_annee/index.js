
// for every checkbox in the document that have data-check-all attribute, add a click event listener to check or uncheck all checkboxes in the container
$(document).on('click', '[data-check-all]', function () {
    const $container = $(this).closest('table').find('[data-check-all-container]');
    const $checkboxes = $container.find('input[type="checkbox"]');
    $checkboxes.prop('checked', $(this).prop('checked'));
})

// On any checkbox change, enable or disable the button
$(document).on('change', 'input[type="checkbox"]', function () {
    const $container = $(this).closest('table').find('[data-check-all-container]');
    const $checkboxes = $container.find('input[type="checkbox"]');
    const $moveButton = $container.closest('div.tab-pane').find('[data-move-selected]');
    if ($checkboxes.filter(':checked').length > 0) {
        $moveButton.toggleClass('disabled', false)
    } else {
        $moveButton.toggleClass('disabled', true)
    }
})

// same with data-move-selected
$(document).on('click', '[data-move-selected]', function () {
const $container = $(this).closest('div.tab-pane').find('[data-check-all-container]');
    const $checkboxes = $container.find('input[type="checkbox"]');
    const $selected = $checkboxes.filter(':checked');
    const $destination = $(this).data('move-selected');
    $selected.each(function () {
        const $tr = $(this).closest('tr');
        $tr.appendTo($destination);
    })
})