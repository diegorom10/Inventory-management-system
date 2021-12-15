$('#registro-herramienta').submit(function(e){
    e.preventDefault(); 

    $("#registro-herramienta :submit").prop('disabled', true);
    setTimeout(()=>{$("#registro-herramienta :submit").prop('disabled', false)}, 500);
    // $("#registro-herramienta :submit").prop('disabled', true);
    // setTimeout(()=>{$("#registro-herramienta :submit").prop('disabled', false)}, 200);
    
    var descripcion = $('#txtDescripcion').val();
    var codigo = $('#txtCodigo').val();
    var serie = $('#txtSerie').val();
    var tipo = $('#selCategoria').val();
    var _token = $("input[name=_token]").val();
    

    $.ajax({
        url: "/catalogo/registrar",
        type: "POST",
        data:{
            descripcion: descripcion,
            codigo: codigo,  
            numserie: serie,
            tipo: tipo,
            _token:_token
        },
        success:function(response){
          if(response){
            $('#registro-herramienta')[0].reset(); //si se realiza el post correctamente,borrame la caja de registro
            toastr.success('El registro se ingreso correctamente.', 'Nuevo Registro', {timeOut: 3000});
            $('#tabla-catalogo').DataTable().ajax.reload(); //cuando ingrese datos, que se actualice la tabla
          }
        }

      });
  });