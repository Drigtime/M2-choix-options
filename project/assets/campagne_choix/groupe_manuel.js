import $ from "jquery";


$(document).ready(function() {
    console.log('js ready')
    $(".btn-selection").click(function() {
    console.log("clic btn")
    var url = $(this).data("url");
    var parcours = $(this).data("parcours");
    console.log(url)
    console.log(parcours)
              $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: parcours,
                success: function(data) {
					console.log(data)
                    var tableBody = $('#etudiant_container tbody');
                    var i=0;
                    tableBody.empty();
                    $.each(data, function(index, etudiant) {
                        tableBody.append(
                        '<tr>'+
                        '<td><input class="form-check-input selection_etudiant" type="checkbox" id="'+parcours+i+'" value="'+i+'" name="selection_etudiant[]"></td>'+
                        '<td>'+etudiant+'</td>'+
                        '<td>'+parcours+'</td>'+
                        '</tr>');
                        i++;
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("error request");
                }
            });
    });

  

    $(".btn_ue").click(function() {
        var id = $(this).data("ueid");
        console.log(id)
        $("#choixUE").val(id);


    });


    $('#SIO0').on('change', function() {
        if ($(this).is(':checked')) {
          console.log('Checkbox is checked.');
          // Do something when checkbox is checked.
        } else {
          console.log('Checkbox is unchecked.');
          // Do something when checkbox is unchecked.
        }
      });


  });






