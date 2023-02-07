import {Modal} from "bootstrap";
import $ from "jquery";

// for every checkbox in the document that have data-check-all attribute, add a click event listener to check or uncheck all checkboxes in the container
$(document).on('click', '[data-check-all]', function () {
    const $container = $(this).closest('table').find('[data-check-all-container]');
    const $checkboxes = $container.find(':checkbox');
    $checkboxes.prop('checked', $(this).prop('checked'));
    $checkboxes.trigger('change');
})

// On any checkbox change, enable or disable the button
$(document).on('change', ':checkbox', function () {
    const $container = $(this).closest('table').find('[data-check-all-container]');
    const $checkboxes = $container.find(':checkbox');
    const $moveButton = $container.closest('div.tab-pane').find('[data-move-selected]');
    if ($checkboxes.filter(':checked').length > 0) {
        $moveButton.toggleClass('disabled', false)
    } else {
        $moveButton.toggleClass('disabled', true)
    }
})

$(document).on('change', 'tbody :checkbox', function() {
    // if checked, row turn green and if unchecked, row return to normal
    if ($(this).prop('checked')) {
        $(this).closest('tr').addClass('table-info');
    } else {
        $(this).closest('tr').removeClass('table-info');
    }
})

let lastChecked = null;

$(document).on('click', 'tbody :checkbox', function(e) {
    if (e.shiftKey) {
        const checkboxesElements = $('tbody :checkbox');
        const start = checkboxesElements.index(this);
        const end = checkboxesElements.index(lastChecked);
        const checkboxes = checkboxesElements.slice(Math.min(start, end), Math.max(start, end) + 1);
        checkboxes.prop('checked', lastChecked.checked);
        checkboxes.trigger('change');
    }
    lastChecked = this;
});

$(document).on('click', '[data-move-selected]', function (e) {
    e.preventDefault();

    const $container = $(this).closest('div.tab-pane').find('[data-check-all-container]');
    const $checkboxes = $container.find(':checkbox');
    const $selected = $checkboxes.filter(':checked');

    const url = $(this).data('url');
    const title = $(this).data('modal-title');

    let modalDiv = document.getElementById('modalTemp');

    if (modalDiv) {
        modalDiv.remove();
    }

    modalDiv = document.createElement('div');
    modalDiv.classList.add('modal');
    modalDiv.classList.add('fade');
    modalDiv.setAttribute('id', 'modalTemp');
    modalDiv.setAttribute('tabindex', '-1');
    modalDiv.setAttribute('role', 'dialog');
    modalDiv.setAttribute('aria-labelledby', 'modalLabel');
    modalDiv.setAttribute('aria-hidden', 'true');
    modalDiv.innerHTML = `
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    document.body.appendChild(modalDiv);

    const modal = new Modal(modalDiv, {
        keyboard: false,
        backdrop: 'static',
        focus: true,
    });

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            students: $selected.map(function () {
                return $(this).val();
            }).toArray()
        },
        success: function (data) {
            modalDiv.querySelector('.modal-body').innerHTML = data;
            modal.show();
            modalDiv.querySelector('form').addEventListener('submit', function (formEvent) {
                formEvent.preventDefault();
                $.ajax({
                    url: url,
                    method: $(this).attr('method'),
                    data: $(this).serialize() + '&' + $selected.map(function () {
                        return 'students[]=' + $(this).val();
                    }).toArray().join('&'),
                    success: function (data) {
                        const target = $(e.currentTarget).data('ajax-target');
                        if (target) {
                            $(target).html(data);
                        }
                        modal.hide();
                    },
                    error: function (data) {
                        modal._element.innerHTML = data.responseText;
                    },
                });
            });
        },
    });
})

$(document).on('show.bs.tab', function (event) {
    const $tab = $(event.target);
    const $container = $tab.closest('ul.nav-tabs');
    const containerId = $container.attr('id');
    document.cookie = `${containerId}=${$tab.data('bs-target')}`;
})
