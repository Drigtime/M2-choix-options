import $ from "jquery";

$("ul.nav-tabs a").click(function (e) {
    e.preventDefault();  
      $(this).tab('show');
      });

$(document).on('show.bs.tab', function (event) {
    const $tab = $(event.target);
    const $container = $tab.closest('ul.nav-tabs');
    const containerId = $container.attr('id');
    document.cookie = `${containerId}=${$tab.data('bs-target')}`;
})
