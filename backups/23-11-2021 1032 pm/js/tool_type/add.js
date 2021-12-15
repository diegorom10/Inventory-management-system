$('#registro-tipo').submit(function(e){
    e.preventDefault(); 
    var tipo = $('#txtTipo').val();
    var _token = $("input[name=_token]").val();
    
    console.log(tipo);

    $.ajax({
        url: "/tipo/registrar",
        type: "POST",
        data:{
            tipo: tipo,
            _token:_token
        },
        success:function(response){
          if(response){
            $('#registro-tipo')[0].reset(); //si se realiza el post correctamente,borrame la caja de registro
            toastr.success('El registro se ingreso correctamente.', 'Nuevo Registro', {timeOut: 3000});
            $('#tabla-tipo').DataTable().ajax.reload(); //cuando ingrese datos, que se actualice la tabla
          }
        }

      });
  });