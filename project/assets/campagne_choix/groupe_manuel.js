import $ from "jquery";


$(document).ready(function() {
    console.log('js ready')
    $(".btn-selection").click(function() {
    console.log("clic btn")
    $('#etudiant_container tbody').empty();
    var url = $(this).data("url");
    var parcours = $(this).data("parcours");
    let route_delete = "{{ path('app_campagne_choix_delete_etudiant_groupe', { campagne_id: 'campagne_id_', groupe_id: 'groupe_id_', 'etudiant_id': 'etudiant_id_' }) }}";

    console.log(url)
              $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                success: function(data) {
					console.log(data)
                    var tableEtudiant = $('#etudiant_container tbody');
                    var tableGroupe = $('#groupe_container tbody');
                    var campagne_id = $('#campagne_id').val();
                    tableEtudiant.empty();
                    tableGroupe.empty();
                    $.each(data[1], function(index, etudiant) {
                      tableEtudiant.append(
                        '<tr>'+
                        '<td><input class="form-check-input selection_etudiant" type="checkbox" id="'+etudiant.id+'" value="'+etudiant.id+'" name="selection_etudiant[]"></td>'+
                        '<td>'+etudiant.nom+'</td>'+
                        '<td>'+etudiant.prenom+'</td>'+
                        '<td>'+parcours+'</td>'+
                        '<td>'+etudiant.ordre+'</td>'+
                        '</tr>');
                        $('#choixGroupeBtn').attr('data-bs-target',"#choixGroupeModal"+etudiant.ue);
                        
                    });
                    $.each(data[0], function(index, etudiant) {
                      tableGroupe.append(
                        '<tr>'+
                        '<td><a href="/admin/campagne_choix/delete_etudiant_groupe/'+campagne_id+'/'+etudiant.groupe_id+'/'+etudiant.id+'"><i class="fa-solid fa-xmark"></i></a></td>'+
                        '<td>'+etudiant.nom+'</td>'+
                        '<td>'+etudiant.prenom+'</td>'+
                        '</tr>');
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

  $(document).on('change', 'input[type="checkbox"][name="selection_groupe[]"]', function() {
    if ($('input[type="checkbox"][name="selection_groupe[]"]:checked').length > 0) {
      console.log('at last one')
      $('#delGroupeBtn').prop('disabled', false);
    } else {
      console.log('none')
      $('#delGroupeBtn').prop('disabled', true);
    }
  });









