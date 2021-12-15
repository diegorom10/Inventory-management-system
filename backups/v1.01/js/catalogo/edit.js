function editarHerramienta(id){
  $('#div-codigo2').hide();
  $('#div-serie2').hide();
  
    $.get('catalogo/editar/' + id, function(herramienta){
      //asignar los datos asignados a la ventana modal
      $('#txtId2').val(herramienta[0].id);
      $('#txtDescripcion2').val(herramienta[0].descripcion);
      $('#txtCodigo2').val(herramienta[0].codigo);
      $('#txtSerie2').val(herramienta[0].numserie);
      $('#selCategoria2').val(herramienta[0].tipo);
      $("input[name=_token]").val();

      if(($('#txtCodigo2').val()) == ""){
          $('#div-serie2').show();
      }

      if(($('#txtSerie2').val()) == ""){
        $('#div-codigo2').show();
    }
      
       $('#herramienta_edit_modal').modal('toggle');
    
    });
  }