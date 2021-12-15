$('#herramienta_edit_form').submit(function(e){
    e.preventDefault();
    var id2 = $('#txtId2').val();
    var descripcion2 = $('#txtDescripcion2').val();
    var tipo2 = $("#selCategoria2").val();
    var _token2 = $("input[name=_token]").val();

    $.ajax({
      url: "catalogo/actualizar",
      type: "POST",
      data:{
        id: id2,
        descripcion: descripcion2,
        tipo: tipo2,
        _token:_token2
      },
      success:function(response){
        if(response){
          $('#herramienta_edit_modal').modal('hide');
          toastr.info('La herramienta fue actualizada correctamente.', 'Actualizar registro', {timeOut:3000});
          $('#tabla-catalogo').DataTable().ajax.reload('', false);

        }
      }
    })
    

  });