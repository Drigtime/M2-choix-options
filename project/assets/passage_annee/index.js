import {Modal} from "bootstrap";
import $ from "jquery";
import 'datatables.net-bs5';
import languageFR from 'datatables.net-plugins/i18n/fr-FR.json';
import {showError, showSuccess} from '../toast';

function initializeDataTable() {
    const $dataTables = $('.dt');
    $dataTables.DataTable({
        "columnDefs": [
            {"orderable": false, "targets": 0},
            {"searchable": false, "targets": 0},
            {"width": "0%", "targets": 0},
            {"width": "0%", "targets": 1},
            {"orderable": false, "targets": 4},
            {"searchable": false, "targets": 4},
            {"width": "0%", "targets": 4},
        ],
        "order": [[2, "asc"]],
        "language": languageFR
    });
    $dataTables.css('width', '100%');
}

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

$(document).on('change', 'tbody :checkbox', function () {
    // if checked, row turn green and if unchecked, row return to normal
    if ($(this).prop('checked')) {
        $(this).closest('tr').addClass('table-info');
    } else {
        $(this).closest('tr').removeClass('table-info');
    }
})

let lastChecked = null;

$(document).on('click', 'tbody :checkbox', function (e) {
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

$(document).on('click', '[data-move]', function (e) {
    e.preventDefault();
    const url = $(this).data('url');
    const title = $(this).data('modal-title');
    const custom = $(this).data('custom');
    // Create a div with that will be used as a modal window
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

    if (custom) {
        modalDiv.setAttribute('data-custom', custom);
    }

    const modal = new Modal(modalDiv, {
        keyboard: false,
        backdrop: 'static',
        focus: true,
    });

    $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {
            modalDiv.querySelector('.modal-body').innerHTML = data;
            modal.show();
            modalDiv.querySelector('form').addEventListener('submit', function (formEvent) {
                formEvent.preventDefault();
                const cancelButton = formEvent.target.querySelector('[data-bs-dismiss="modal"]');
                const submitButton = formEvent.submitter;
                // save content of the button
                const submitButtonContent = submitButton.innerHTML;
                // disable the button
                cancelButton.disabled = true;
                submitButton.disabled = true;
                // disable all inputs that are not hidden
                formEvent.target.querySelectorAll('input:not([type="hidden"])').forEach(function (input) {
                    input.disabled = true;
                });

                // add a spinner to the button
                submitButton.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Déplacement...</span>
                    `;

                $.ajax({
                    url: url,
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (data) {
                        // if element that open the modal has a data-ajax-target attribute with a value that is a valid css selector then
                        // the data is inserted in the element that match the selector
                        const target = $(e.currentTarget).data('ajax-target');
                        if (target) {
                            $(target).html(data);
                            initializeDataTable();
                        }
                        modal.hide();
                    },
                    error: function (data) {
                        modal._element.innerHTML = data.responseText;
                    },
                    complete: function (data) {
                        cancelButton.disabled = false;
                        submitButton.disabled = false;
                        submitButton.innerHTML = submitButtonContent;

                        formEvent.target.querySelectorAll('input:not([type="hidden"])').forEach(function (input) {
                            input.disabled = false;
                        });

                        if (data.status === 200) {
                            showSuccess({
                                title: 'Déplacement',
                                message: 'Déplacement effectué avec succès',
                            })
                        } else {
                            showError({
                                title: 'Déplacement',
                                message: 'Une erreur est survenue lors du déplacement',
                            })
                        }
                    }
                });
            });
        },
    });
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
                const cancelButton = formEvent.target.querySelector('[data-bs-dismiss="modal"]');
                const submitButton = formEvent.submitter;
                // save content of the button
                const submitButtonContent = submitButton.innerHTML;
                // disable the button
                cancelButton.disabled = true;
                submitButton.disabled = true;

                formEvent.target.querySelectorAll('input:not([type="hidden"])').forEach(function (input) {
                    input.disabled = true;
                });

                // add a spinner to the button
                submitButton.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Déplacement...</span>
                    `;

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
                            initializeDataTable();
                        }
                        modal.hide();
                    },
                    error: function (data) {
                        modal._element.innerHTML = data.responseText;
                    },
                    complete: function (data) {
                        cancelButton.disabled = false;
                        submitButton.disabled = false;
                        submitButton.innerHTML = submitButtonContent;

                        formEvent.target.querySelectorAll('input:not([type="hidden"])').forEach(function (input) {
                            input.disabled = false;
                        });

                        if (data.status === 200) {
                            showSuccess({
                                title: 'Déplacement',
                                message: 'Déplacement effectué avec succès',
                            })
                        } else {
                            showError({
                                title: 'Déplacement',
                                message: 'Une erreur est survenue lors du déplacement',
                            })
                        }
                    }
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

$(document).ready(function () {
    initializeDataTable();
});