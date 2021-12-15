
<!-- <div class="modal fade" id="ajusteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Realizar un ajuste</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"> -->
            <!-- <div class="d-flex flex-column align-items-center">
            <form id="add-form" style="width: 80%">  
              <div class="form-group">
								<label>Selecciona una herramienta:</label>
									<select name="herramienta" id="herramienta_select2" class="form-control input-entrada">
										<option></option>
									</select>
                  <small style="color: rgb(122, 122, 122)">*No puedes agregar artículos de tipo único.</small>
							</div>
              <div class="form-group d-flex flex-column align-items-center">
									<label>Cantidad actual:</label>
                  <div class="col-md-3">
									  <input type="number" min="0" max="100" name="cantidad" id="cantidad_actual" class="form-control input-entrada" disabled>
                  </div>
              </div>
              <div class="form-group d-flex flex-column align-items-center">
									<label>Cantidad a agregar:</label>
                  <div class="col-md-3">
									  <input type="number" min="1" max="100" name="cantidad" id="cantidad_agregar" class="form-control input-entrada">
                  </div>
              </div>
              <div class="form-group d-flex flex-column align-items-center">
									<label>Cantidad nueva:</label>
                  <div class="col-md-3">
									  <input type="number" min="1" max="100" name="cantidad" id="cantidad_nueva" class="form-control input-entrada">
                  </div>
              </div>
              <div class="form-group d-flex flex-column align-items-center">
                <input type="hidden" name="hidden_id2" id="hidden_id2" value="" />
                <button type="submit"name="Add" id="add-button" class="btn btn-success" value="Add">
                     Agregar
                  </button>
              </div>
            </form>
            </div> <!--.d-flex flex-column....-->
          
<!--Modal resumen
<div class="modal fade" id="confirmEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Resumen de artículos nuevos</h5>
					</div>
					<div class="modal-body">
						<div class="table-wrapper-scroll-y my-custom-scrollbar-resumen">
							<ul id="resumen-list-entrada">
							</ul>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button"  id="close-confirmar-entrega" name="close-confirmar-entrada" class="btn btn-secondary">Cerrar</button>
						<button type="button" id="btn-confirm-entrega" name="btn-confirm-entrada" class="btn btn-primary">Confirmar</button>
					</div>
					</div>
				</div>
    </div>modal resumen -->


          
          <!-- </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Agregar material</button>
          </div>
        </div>
      </div>
    </div>
</div>

    <script>

        $('#herramienta_select2').editableSelect();
        $('#herramienta_select2').prop("placeholder", "Descripción o código");
        $('#add-button').addClass('disabled');
      


        function listarHerramientas2(){
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


        function setQty(herramienta_codigo){

          $.getJSON("inventario/getTool/"+herramienta_codigo, function(data) {
                  const herramienta = data;
                  const cantidad_actual = herramienta.qtyf;
                  $('#cantidad_actual').val(cantidad_actual);
                  $('#hidden_id2').val(herramienta.id);
            });

        }


      $('#add-form').on('submit', function(e){
	    	e.preventDefault();

        var herramienta_id = '';
        var cantidad_agregar = 0; 

        $('#add-button').prop('disabled', true);
        setTimeout(()=>{$('#add-button').prop('disabled', false)}, 200);


        if($('#cantidad_actual').val() == '' || $('#cantidad_agregar').val() == '' || $('#cantidad_nueva').val() == '')
        {
          toastr.warning('Completa el campo faltante', 'Campo faltante', {timeOut: 1500});
          return false;

        }else{
          herramienta_id = $('#hidden_id2').val();
          cantidad_agregar = $('#cantidad_agregar').val();
          if(parseInt(cantidad_agregar) > 0 && herramienta_id !== ''){
          let resumen = '';

          $.getJSON("catalogo/fetchTool/"+herramienta_id, function(data) {
                  const herramienta = data;
                 
                  resumen += `<p><strong>Herramienta:</strong> ${herramienta.descripcion}</p>
                              <p><strong>Cantidad agregada:</strong>${cantidad_agregar}</p>`;

                $("#resumen-list-entrada").html(resumen);
            });


            var backdropHeight = window.screen.height;
            $('#backdrop').css('height', backdropHeight);
            $('#backdrop').fadeIn(100);

            $("#confirmEntrada").show(); 
            $("#confirmEntrada").css('opacity', '1');

          
            // resumen += `
            //     <li><strong>${herramienta["descripcion"]}</strong> - #${herramienta["codigo"]} (${herramienta["cantidad"]})</li>
            //     `;
         
            // $("#resumen-list").html(resumen);

            // $('#btnConfirmPrestamo').text('Confirmar');
            // var backdropHeight = window.screen.height;
            // $('#backdrop').css('height', backdropHeight);
            // $('#backdrop').fadeIn(100);

            // $("#confirmPrestamo").show(); //no funciona con efectos
            // $("#confirmPrestamo").css('opacity', '1');
          

          
		 


            // $.ajax({
            //   url:"inventario/addArticulos",
            //   method:"POST",
            //   headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //   },
            //   //enviar el codigo o serie de la herramienta
            //   data:{herramienta: herramienta_id, cantidad: cantidad_agregar},
            //   error: function(error) {
            //     toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
            //     return false;
            //   },
            //   success:function(data)
            //   {
            //     toastr.success(data, 'Exito', {timeOut: 2000});
            //     setTimeout(function(){
            //       selected = [];
            //       $('#backdrop').fadeOut(100);
            //       $('#confirmPrestamo').hide();
            //       $('#tabla-seleccionados tbody').html('');
            //       $('#sample_form')[0].reset();
            //       $("#prestamoModal").modal('hide');
            //       $('#btnConfirmPrestamo').prop('disabled', false);
            //       $('#tabla-inventario').DataTable().ajax.reload(null, false);
            //     },2500);
                  
            //   },
            // });
            console.log("todo listo papito");

          }
        }

	    });



        $(document).on('click', '#herramienta_select2', function(e){
            e.preventDefault();
            $(this).val('');
            listarHerramientas2();
          });

          $('#herramienta_select2').focusout(function(e){
            e.preventDefault();
            var opciones = [];
            var herramienta_input = $('#herramienta_select2').val();
            $('#cantidad_nueva').val($('#cantidad_nueva').prop('defaultValue'));
            $('#add-button').addClass('disabled');

              //solo realizar esto si se escribió algo en el input
            if(herramienta_input !== ''){
              var herramienta_codigo = '';
              $('.es-list li').each(function(){
                opciones.push($(this)[0].innerHTML)
              });

              //solamente sigue si el usuario seleccionó alguna de las opciones del editableSelect
              if(opciones.length > 0 && opciones.includes($("#herramienta_select2").val())){
                  herramienta_codigo = herramienta_input.split(' | ')[1].substring(1); //forzado a hacer esto gracias al editableSelect (convierte selecto a texto / no val = id)
                  $('#cantidad_agregar').val(0);
                  setQty(herramienta_codigo);
              }else{
                alert("Selecciona una herramienta de la lista por favor");
                $('#herramienta_select2').val($('#herramienta_select2').prop("defaultValue"));
                $('#cantidad_nueva').val($('#cantidad_nueva').prop('defaultValue'));
                return false;
              }

            }else{
              //si no se selecciona nada, quita lo ultimo seleccionado
              $('#cantidad_actual').val($('#cantidad_actual').prop('defaultValue'));
              $('#cantidad_agregar').val($('#cantidad_agregar').prop('defaultValue'));
            }
              
	        });


          $('#cantidad_agregar').change(function(e){
            const cantidad_agregar = $(this).val();
            const cantidad_actual = $('#cantidad_actual').val(); //esto no funciona si en input cantidad actual tiene prop disabled <- solucion: deshabilitarlo por 
            var cantidad_total = 0;

            if(cantidad_agregar !== ''){
              cantidad_total = parseInt(cantidad_actual) + parseInt(cantidad_agregar);
              $('#cantidad_nueva').val(cantidad_total);

              if(parseInt(cantidad_agregar) > 0 && cantidad_nueva !== ''){
              $('#add-button').removeClass('disabled');
              }else{
                $('#cantidad_nueva').val($('#cantidad_nueva').prop('defaultValue'));
                $('#add-button').addClass('disabled');
              }
            }
              
          }); 


          $('#cantidad_agregar').focus(function(e){
        
              if($('#herramienta_select2').val() == ''){
                $('#herramienta_select2').effect("shake");
                toastr.warning('Primero selecciona una herramienta por favor', 'No puedes seleccionar la cantidad', {timeOut: 1500});
                $(this).blur(); 
                return false;
              }
              
          }); 


          $('#buttonEntrada').click(function(){
            $('.input-entrada').val('');
        });


      
 
    </script> -->