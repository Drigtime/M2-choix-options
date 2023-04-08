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
        var nbGroupes = $(this).data("nbgroupes");
        console.log(id)
        $("#choixUE").val(id);
        $("#nbGroupes").val(nbGroupes);
        $('#etudiant_container tbody').empty();


    });

    $(".first_section").click(function() {
      $('#etudiant_container tbody').empty();
  });

  
  });


  $(document).on('change', 'input[type="checkbox"][name="selection_etudiant[]"]', function() {
    if ($('input[type="checkbox"][name="selection_etudiant[]"]:checked').length > 0) {
      console.log('at last one')
      $('#choixGroupeBtn').prop('disabled', false);
    } else {
      console.log('none')
      $('#choixGroupeBtn').prop('disabled', true);
    }

  });

  $('#choixGroupeModal').on('shown.bs.modal', function () {
    console.log('open modal');
    var nbGroupes = parseInt($('#nbGroupes').val());

    $('.choixGroupeModal_body').empty();
    for(var i = 1;i<=nbGroupes;i++){
      console.log(i)
      $('.choixGroupeModal_body').append(
        $('<button/>', {
          text: 'Groupe'+i,
          class: 'btn btn-primary mt-2'
        }),
        '<br>'
      );
    }
    

  });







