
var herramienta_id;
$(document).on('click','.delete', function(){
    herramienta_id = $(this).attr('id');
    $('#btnEliminar').text('Eliminar'); 
    $('#confirmModal').modal('show');
}); 

//el boton con id=btnEliminar está en el modal
$('#btnEliminar').click(function(){
  $(this).prop('disabled', true);
	setTimeout(()=>{$(this).prop('disabled', false)}, 200);

  if($("#motivoTxt2").val() == '' || $("#pass-eliminar").val() == ''){
    
    if($("#motivoTxt2").val() == ''){
      $('#motivoTxt2').effect("shake");
      toastr.warning('Indica un motivo por favor', 'Motivo faltante');
    }

    if($("#pass-eliminar").val() == ''){
      $('#pass-eliminar').effect("shake");
      toastr.warning('Proporciona tu contraseña por favor', 'Contraseña faltante');
    }

    return false;
  }


var motivo = $("#motivoTxt2").val();
var password = $("#pass-eliminar").val();

if(motivo != '' && herramienta_id != '' && password != ''){
  $.ajax({
    url: "/catalogo/eliminar",
    type: "POST",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{
        id: herramienta_id, motivo: motivo, password: password
    },
    beforeSend: function(){
      $('#btnEliminar').text('Eliminando....');
    },
    success:function(response){
      
      if(response.success == false){
        if(response.error == 'pendientes'){
          let message = `Tienes ${response.cantidad} articulos de este tipo en prestamos pendientes
          <br><a href="#">IR A INVENTARIO</a>`; 
          toastr.warning(message, 'Prestamos sin regresar', {timeOut:3000});
          $('#btnEliminar').text('Eliminar');
          return false;
        }

        if(response.error == 'contraseña'){
          $('#pass-eliminar').effect("shake");
          toastr.warning('La contraseña no coincide, vuelve a intentarlo', 'Contraseña', {timeOut:3000});
          $('#btnEliminar').text('Eliminar');
          return false;
        }
       
      }else{
        setTimeout(function(){
          $('#confirmModal').modal('hide');
          $("#motivoTxt2").val($('#motivoTxt2').prop("defaultValue"));
          $("#pass-eliminar").val($('#pass-eliminar').prop("defaultValue"));
          toastr.warning('El registro fue eliminado correctamente.', 'Eliminar registro', {timeOut:3000});
          $('#tabla-catalogo').DataTable().ajax.reload(null,false);
        },500);
      }
    
    }
  });
}

});