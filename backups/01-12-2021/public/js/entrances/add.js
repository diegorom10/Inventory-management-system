$('#registro-entradas').submit(function(e){
    e.preventDefault(); 
    var entrada = $('#txtEntrada').val();
    var _token = $("input[name=_token]").val();
    
    console.log(entrada);

    $.ajax({
        url: "/entradas/registrar",
        type: "POST",
        data:{
            entrada: entrada,
            _token:_token
        },
        success:function(response){
          if(response){
            $('#registro-entradas')[0].reset(); //si se realiza el post correctamente,borrame la caja de registro
            toastr.success('El registro se ingreso correctamente.', 'Nuevo Registro', {timeOut: 3000});
            $('#tabla-entradas').DataTable().ajax.reload(); //cuando ingrese datos, que se actualice la tabla
          }
        }

      });
  });