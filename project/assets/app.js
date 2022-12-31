/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

import $ from 'jquery';
import 'bootstrap';

import {Modal} from 'bootstrap';

// set global jQuery variable
global.$ = global.jQuery = $;

$(document).ready(function () {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // load form from ajax request and display it in a modal window
    // the modal window is created dynamically
    // the form is loaded from the url in the data-url attribute of the button
    // the form is submitted to the url in the data-url attribute of the form
    // the form is submitted with ajax
    // apply the event on the document to be able to catch dynamically created buttons
    $(document).on('click', '[data-action="load-form"]', function (e) {
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
                    <div class="modal-body
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
});