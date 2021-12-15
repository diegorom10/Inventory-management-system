var entrada_id;
$(document).on('click','.delete', function(){
    entrada_id = $(this).attr('id');
    $('#btnEliminar').text('Eliminar'); 
    $('#confirmModal').modal('show');
}); 

//el boton con id=btnEliminar está en el modal
$('#btnEliminar').click(function(){
$.ajax({
  url: "entradas/eliminar/"+entrada_id,
  beforeSend: function(){
    $('#btnEliminar').text('Eliminando....');
  },
  success: function(data){
    setTimeout(function(){
      $('#confirmModal').modal('hide');
      toastr.warning('El registro fue eliminado correctamente.', 'Eliminar registro', {timeOut:3000});
      $('#tabla-entradas').DataTable().ajax.reload();
    },2000);
    //$('#btnEliminar').text('Eliminar'); 
  }
});
});