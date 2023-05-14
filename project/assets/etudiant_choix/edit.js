import Sortable from 'sortablejs';

// https://github.com/SortableJS/Sortable

window.addEventListener('DOMContentLoaded', (event) => {
    var blocsOptions = document.querySelectorAll('.choices');
    blocsOptions.forEach(function(blocOptions) {
        var blocOptionsId = blocOptions.dataset.id
        new Sortable(blocOptions, {
            group: blocOptionsId,
            animation: 150,
            onEnd: function (/**Event*/evt) {
                var choixes = document.querySelectorAll('[id="'+blocOptionsId+'"] > .choix > span');
                choixes.forEach(function(choix, i) {
                    choix.innerHTML = "Choix n°" + (i+1);
                });
            },
            store: {
                /**
                 * Save the order of elements. Called onEnd (when the item is dropped).
                 * @param {Sortable}  sortable
                 */
                set: function (sortable) {
                    var choix = {"blocOptionsId": blocOptionsId, "ordre": sortable.toArray()};
                    var campagneId = document.getElementById('campagneId');
                    $.ajax({
                        url: "/etudiant/options/"+ campagneId.dataset.campagneId +"/edit",
                        type: "POST",
                        data: JSON.stringify(choix),
                        contentType: "application/json",
                        dataType: "json",
                        success: function (data) {
                            console.log('success');
                        },
                        error: function (data) {
                            console.log('error');
                        }
                    });
                }
            },
      });
    });
});
