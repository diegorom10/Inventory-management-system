function editarHerramienta(id){
  $('#div-codigo2').hide();
  $('#div-serie2').hide();
  
    $.get('catalogo/editar/' + id, function(herramienta){
      //asignar los datos asignados a la ventana modal
      $('#txtId2').val(herramienta[0].id);
      $('#txtDescripcion2').val(herramienta[0].descripcion);
      $('#selCategoria2').val(herramienta[0].tipo);
      $("input[name=_token]").val();
      
       $('#herramienta_edit_modal').modal('toggle');
    
    });
  }