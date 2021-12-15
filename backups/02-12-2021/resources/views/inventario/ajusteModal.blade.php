
   <div class="modal fade" id="ajusteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Realizar un ajuste</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"> 
             <div class="d-flex flex-column align-items-center">
            <form id="ajuste-form" style="width: 80%">  
              <div class="form-group">
								<label>Selecciona una herramienta:</label>
									<select name="herramienta" id="herramienta_select3" class="form-control input-ajuste">
										<option></option>
									</select>
                  <small style="color: rgb(122, 122, 122)">*No puedes ajustar artículos de tipo único.</small>
							</div>
              <div class="form-group">
								<label>¿Por qué realizas este ajuste?</label>
									<textarea name="motivo" id="input_motivo" class="form-control input-ajuste" placeholder="Ej. descuadre en el inventario" row="1" style="height: 40px;" required>
                  </textarea>
                  <small style="color: rgb(122, 122, 122)">*Obligatorio.</small>
							</div>
              <div class="form-group d-flex flex-column align-items-center">
									<label>Cantidad actual:</label>
                  <div class="col-md-3">
									  <input type="number" min="0" max="100" name="cantidad" id="cantidad_actual2" class="form-control input-ajuste" disabled>
                  </div>
              </div>
              <div class="form-group d-flex flex-column align-items-center">
									<label>Cantidad a ajustar:</label>
                  <div class="col-md-3">
									  <input type="number" min="1" max="100" name="cantidad" id="cantidad_ajustar" class="form-control input-ajuste">
                  </div>
              </div>
              <div class="form-group d-flex flex-column align-items-center">
									<label>Cantidad nueva:</label>
                  <div class="col-md-3">
									  <input type="number" min="1" max="100" name="cantidad" id="cantidad_nueva2" class="form-control input-ajuste">
                  </div>
              </div>
              <div class="form-group d-flex flex-column align-items-center">
                <input type="hidden" name="hidden_id3" id="hidden_id3" value="" />
                <button type="submit"name="Ajustar" id="ajustar-button" class="btn btn-success" value="Add">
                     Realizar ajuste
                  </button>
              </div>
            </form>
            </div> <!--.d-flex flex-column....-->
          
<!--Modal resumen-->
<div class="modal fade" id="confirmAjuste" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Resumen de ajuste</h5>
					</div>
          <div class="modal-body">
            <div id="resumen-ajuste" class="d-flex flex-column"></div>
          </div>
					<div class="modal-footer">
						<button type="button"  id="close-confirmar-ajuste" name="close-confirmar-ajuste" class="btn btn-secondary">Regresar</button>
						<button type="button" id="btn-confirm-ajuste" name="btn-confirm-ajuste" class="btn btn-primary">Confirmar</button>
					</div>
					</div>
				</div>
    </div> <!-- modal resumen -->


          
           </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
</div>

    <script>

        $('#herramienta_select3').editableSelect();
        $('#herramienta_select3').prop("placeholder", "Descripción o código");
        $('#input_motivo').addClass('disabled');
        $('#ajustar-button').addClass('disabled');
      


        function listarHerramientas3(){
			  $.ajax({
            url: 'inventario/fetchTools',
            type: 'GET',
            success: function(data) {
                let herramientas = JSON.parse(data);
                let options = '';
                //let codigo = ''; // <- toma el valor de codigo o numserie dependiendo de la herramienta
                herramientas.forEach(herramienta => {
                  //codigo = herramienta.codigo == null ? herramienta.numserie : herramienta.codigo;
                  if(herramienta.codigo == null){
                    options += `
                            <li class="es-visible disabled">${herramienta.descripcion} | #${herramienta.numserie} (unico)</li>`
                    ;
                  }else if(herramienta.numserie == null){
                    options += `
                            <li class="es-visible">${herramienta.descripcion} | #${herramienta.codigo}</li>`
                    ;
                  }else{
                    toastr.warning('Hubo un error al cargar una herramienta', 'Error', {timeOut: 3000});
                    return false;
                  }

                  });
                $('.es-list').html(options);
              },
              error: function(data) {
                toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
                return false;
              }
        	});
	      }


        function setQty2(herramienta_codigo){

          $.getJSON("inventario/getTool/"+herramienta_codigo, function(data) {
                  const herramienta = data;
                  const cantidad_actual = herramienta.qtyf;
                  $('#cantidad_actual2').val(cantidad_actual);
                  $('#hidden_id3').val(herramienta.id);
            });

        }

    //hasta aqui todo bien

      $('#ajuste-form').on('submit', function(e){
	    	e.preventDefault();

        var herramienta_id = '';
        var cantidad_ajuste = 0; 
        var motivo = ''

        $('#ajustar-button').prop('disabled', true);
        setTimeout(()=>{$('#ajustar-button').prop('disabled', false)}, 200);


        if($('#cantidad_actual2').val() == '' || $('#cantidad_ajustar').val() == '' || $('#input_motivo').val() == '' || $('#cantidad_nueva2').val() == '')
        {
          toastr.warning('Completa el campo faltante', 'Campo faltante', {timeOut: 1500});
          return false;
        }else{
          herramienta_id = $('#hidden_id3').val();
          cantidad_ajustar = $('#cantidad_ajustar').val();
          motivo = $('#input_motivo').val();

          if(parseInt(cantidad_ajustar) > 0 && herramienta_id !== ''){

          $.getJSON("catalogo/fetchTool/"+herramienta_id, function(data) {

                //si la herramienta es unica entonces no me dejes agregar

                  const herramienta = data;
                  
                  var codigo = herramienta.codigo !== null ? herramienta.codigo : herramienta.numserie;

                  if(data.numserie !== null){
                    toastr.warning('No puedes eliminar cantidades de un artículo de tipo único. Dalo de baja', 'Herramienta de tipo única', {timeOut: 2000});
                    return false;
                  }

                  let resumen = `
                              <div class="form-group d-flex flex-column align-items-center">
                                <label>Herramienta:</label>
                                <input type="text" class="form-control" style="text-align:center; pointer-events:none;" value="${herramienta.descripcion} | #${codigo}" readonly>
                              </div>

                              

                              <div class="form-group d-flex flex-column align-items-center">
                                <label>Ajuste realizado por:</label>
                                <input type="text" id="usuario_confirmado2" class="form-control" style="text-align:center; pointer-events:none;" value="{{ Auth()->user()->name }}" readonly>
                              </div>

                              <div class="form-group d-flex flex-column align-items-center">
                                <label>Motivo:</label>
                                <input type="text" id="motivo_confirmado" class="form-control" style="text-align:center; pointer-events:none;" value="${motivo}" readonly>
                              </div>

                              <div class="d-flex flex-row">
                                <div class="form-group d-flex flex-column align-items-center mr-2" style="width:70%;">
                                  <label>Ajuste realizado el:</label>
                                  <input type="text" class="form-control" style="text-align:center; pointer-events:none;" value="{{date("d-m-Y",time())}}" readonly>
                                </div>

                                <div class="form-group d-flex flex-column align-items-center" style="width:30%;">
                                  <label>Cantidad:</label>
                                  <input type="text" id="cantidad_confirmada2" class="form-control" style="text-align:center; pointer-events:none;" value="${cantidad_ajustar}" readonly>
                                </div>
                              </div>

                              <div class="form-group">
                                  <input type="hidden" id="hidden_id_confirmado2" class="form-control" value="${herramienta.id}" readonly>
                              </div>`;

                $("#resumen-ajuste").html(resumen);
          
                $('#btn-confirm-ajuste').text('Confirmar');
                let backdropHeight = window.screen.height;
                $('#backdrop').css('height', backdropHeight);
                $('#backdrop').fadeIn(100);
                $("#confirmAjuste").show(); 
                $("#confirmAjuste").css('opacity', '1');
            });

          }
        }

	    });

        $(document).on('click', '#herramienta_select3', function(e){
            e.preventDefault();
            $(this).val('');
            listarHerramientas3();
          });

          $('#herramienta_select3').focusout(function(e){
            e.preventDefault();
            var opciones = [];
            var herramienta_input = $('#herramienta_select3').val();
            $('#cantidad_actual2').val($('#cantidad_actual2').prop('defaultValue'));
            $('#cantidad_ajustar').val($('#cantidad_ajustar').prop('defaultValue'));
            $('#cantidad_nueva2').val($('#cantidad_nueva2').prop('defaultValue'));
            $('#ajustar-button').addClass('disabled');

              //solo realizar esto si se escribió algo en el input
            if(herramienta_input !== ''){
              var herramienta_codigo = '';
              $('#input_motivo').val('');

              $('.es-list li').each(function(){
                opciones.push($(this)[0].innerHTML)
              });

              //solamente sigue si el usuario seleccionó alguna de las opciones del editableSelect
              if(opciones.length > 0 && opciones.includes($("#herramienta_select3").val())){
                
                  $('#input_motivo').removeClass('disabled');
              }else{
                alert("Selecciona una herramienta de la lista por favor");
                $('#herramienta_select3').val($('#herramienta_select3').prop("defaultValue"));
                $('#cantidad_nueva2').val($('#cantidad_nueva2').prop('defaultValue'));
                $('#input_motivo').addClass('disabled');
                return false;
              }

            }else{
              //si no se selecciona nada, quita lo ultimo seleccionado
              $('#input_motivo').addClass('disabled');
              $('#cantidad_actual2').val($('#cantidad_actual2').prop('defaultValue'));
              $('#cantidad_ajustar').val($('#cantidad_ajustar').prop('defaultValue'));
            }
              
	        });

          $('#input_motivo').focusout(function(e){
            e.preventDefault();
            var opciones = [];
            var herramienta_input = $('#herramienta_select3').val();
            var motivo = $('#input_motivo').val();
            

              //solo realizar esto si se escribió algo en el input
            if(motivo !== ''){
              $('#cantidad_ajustar').val(0);
              var herramienta_codigo = '';
              $('.es-list li').each(function(){
                opciones.push($(this)[0].innerHTML)
              });
               //solamente sigue si el usuario seleccionó alguna de las opciones del editableSelect
                  herramienta_codigo = herramienta_input.split(' | ')[1].substring(1); //forzado a hacer esto gracias al editableSelect (convierte selecto a texto / no val = id)
                  $('#cantidad_ajustar').val(0);
                  setQty2(herramienta_codigo);              
            }else{
              //si no se selecciona nada, quita lo ultimo seleccionado
              $('#cantidad_actual2').val($('#cantidad_actual2').prop('defaultValue'));
              $('#cantidad_ajustar').val($('#cantidad_ajustar').prop('defaultValue'));
            }
              
	        });




          $('#cantidad_ajustar').change(function(e){
            const cantidad_ajustar = $(this).val();
            const cantidad_actual = $('#cantidad_actual2').val(); //esto no funciona si en input cantidad actual tiene prop disabled <- solucion: deshabilitarlo por 
            var cantidad_total = 0;

            if(cantidad_ajustar !== null || cantidad_ajustar !== ''){
              cantidad_total = parseInt(cantidad_actual) - parseInt(cantidad_ajustar);
              $('#cantidad_nueva2').val(cantidad_total);

              if(parseInt(cantidad_ajustar) > 0 && cantidad_nueva !== ''){
              $('#ajustar-button').removeClass('disabled');
              }else{
                $('#cantidad_nueva2').val($('#cantidad_nueva2').prop('defaultValue'));
                $('#ajustar-button').addClass('disabled');
              }
            }
              
          }); 


          $('#cantidad_ajustar').focus(function(e){
            
              if($('#herramienta_select3').val() == '' || $('#input_motivo').val() == ''){
                if($('#herramienta_select3').val() == ''){
                  $('#herramienta_select3').effect("shake");
                   toastr.warning('Primero selecciona una herramienta por favor', 'No puedes seleccionar la cantidad', {timeOut: 1500});
                }else if($('#input_motivo').val() == ''){
                  $('#input_motivo').effect("shake");
                 toastr.warning('Primero debes indicar un motivo de ajuste.', 'No puedes seleccionar la cantidad', {timeOut: 1500});
                }
               
                $(this).blur(); 
                return false;
              }
          }); 


          $('#buttonAjuste').click(function(){
            $('.input-ajuste').val('');
        });

        
        $('#btn-confirm-ajuste').click(function(){
            $(this).text('Confirmando...');	
           

            const herramienta_id = $('#hidden_id_confirmado2').val();
            const cantidad_ajustar = $('#cantidad_confirmada2').val();
            const usuario = $('#usuario_confirmado2').val();
            const motivo = $('#motivo_confirmado').val();

           if(herramienta_id !== '' && cantidad_ajustar !== '' && parseInt(cantidad_ajustar) > 0 && usuario !== '' && motivo !== ''){
            $.ajax({
              url:"inventario/ajustarArticulos",
              method:"POST",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
            
              data:{herramienta: herramienta_id, cantidad: cantidad_ajustar, usuario:usuario, motivo: motivo},
              error: function(error) {
                toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
                return false;
              },
              success:function(data)
              {
               
                setTimeout(function(){
                  toastr.success('Se ha realizado el ajuste', 'Exito', {timeOut: 800});
                  
                    $('#confirmAjuste').hide();
                    $('#backdrop').fadeOut(100);
                    $('#btn-confirm-ajuste').text('Confirmar');
                    $('#resumen-ajuste').html('');
                    $('#ajuste-form')[0].reset();
                    $('#tabla-inventario').DataTable().ajax.reload(null, false);

                },1000);
                  
              },
            });
           }
        
        
        });

        $('#close-confirmar-ajuste').click(function(){
          $('#confirmAjuste').hide();
	        $('#backdrop').fadeOut(100);
        });



      
 
    </script> -->