document.querySelector('#add-bloc-ue').addEventListener('click', function () {
    let container = document.querySelector('#bloc-ue-list');
    let newWidget = container.dataset.prototype;
    newWidget = newWidget.replace(/__blocUE__/g, container.children.length);
    container.insertAdjacentHTML('afterbegin', newWidget);
});

// for all button created or future created button with data-collection-ue-add
// add event listener on the parent element (container) and listen for click event on the button
document.querySelector('#bloc-ue-list').addEventListener('click', function (e) {
    if (e.target && e.target.dataset.action === 'delete-bloc-ue') {
        e.target.closest('[data-bloc-ue]').remove();
    }
});