import $ from "jquery";


$(document).ready(function() {
    console.log('js ready')
    $(".btn-selection").click(function() {
    console.log("clic btn")
    $('#etudiant_container tbody').empty();
    var url = $(this).data("url");
    var parcours = $(this).data("parcours");
    console.log(url)
              $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                success: function(data) {
					console.log(data)
                    var tableBody = $('#etudiant_container tbody');
                    tableBody.empty();
                    $.each(data, function(index, etudiant) {
                        tableBody.append(
                        '<tr>'+
                        '<td><input class="form-check-input selection_etudiant" type="checkbox" id="'+etudiant.id+'" value="'+etudiant.id+'" name="selection_etudiant[]"></td>'+
                        '<td>'+etudiant.nom+'</td>'+
                        '<td>'+etudiant.prenom+'</td>'+
                        '<td>'+parcours+'</td>'+
                        '<td>'+etudiant.ordre+'</td>'+
                        '</tr>');
                        $('#choixGroupeBtn').attr('data-bs-target',"#choixGroupeModal"+etudiant.ue);
                        
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("error request");
                }
            });
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









