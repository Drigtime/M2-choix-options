document.querySelector('#add-ue').addEventListener('click', function () {
    let container = document.querySelector('#ue-list');
    let newWidget = container.dataset.prototype;
    newWidget = newWidget.replace(/__ue__/g, container.children.length);
    container.insertAdjacentHTML('beforeend', newWidget);
});

// for all button created or future created button with data-collection-ue-add
// add event listener on the parent element (container) and listen for click event on the button
document.querySelector('#ue-list').addEventListener('click', function (e) {
    if (e.target && e.target.dataset.action === 'delete-ue') {
        e.target.closest('[data-ue]').remove();
    }
});