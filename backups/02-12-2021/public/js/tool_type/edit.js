var tipoherramienta_id;
var tipo;
$(document).on('click','.edit', function(){
    tipoherramienta_id = $(this).attr('id');
    tipo = $(this).attr('tipo');
    $('#btnEditar').text('Editar'); 
    $('#editartipo').text(tipo); 
    $('#confirmModaleditar').modal('show');
    console.log(tipoherramienta_id);
}); 


$('#tipoherramienta_edit_form').submit(function(e){
    e.preventDefault();
    var nuevotipo = $('#editartipo').val();
    console.log(nuevotipo);
    $.ajax({
      url: "tipo/editartipo/"+tipoherramienta_id,
      beforeSend: function(){
        $('#btnEditar').text('Guardando');
      },
      type: "GET",
      data:{
        id: tipoherramienta_id,
        updatetipo : nuevotipo
      },
      success: function(data){
        setTimeout(function(){
          $('#confirmModaleditar').modal('hide');
          toastr.success('El registro fue editado correctamente.', 'Editar registro', {timeOut:3000});
          $('#tabla-catalogo').DataTable().ajax.reload();
        },2000);
      }
    });
    $('#tabla-tipo').DataTable().ajax.reload();
    });


