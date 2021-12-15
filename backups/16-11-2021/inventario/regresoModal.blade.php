<div class="container">
 <div class="modal fade" id="regresoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">Regresar un prestamo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body mx-4" style="height: 30rem;">
			<div class="row">
				<div class="d-flex flex-column col-md-12">
					<p>Estos son los prestamos <strong>pendientes:</strong></p>
					<div class="table-wrapper-scroll-y my-custom-scrollbar  p-3">
						<div class="table-responsive">
							<table class="table table-bordered" id="tabla-prestamos">
								<colgroup>
								<col span="1" style="width: 10%;">
								<col span="1" style="width: 60%;">
								<col span="1" style="width: 30%;">
								</colgroup>
								<thead>
										<th>ID</th>
										<th>Fecha</th>
                                        <th>Solicitante</th>
								</thead>
								<tbody>
                                   
								</tbody>
							</table>
						</div><!--.table-responsive-->
					</div><!--.table-wrapper-->
				</div><!--.d-flex-->
			</div><!--.row-->
		</div><!--.modal-body-->
		<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
		</div><!--.modal-content-->
	  </div><!--.menu-dialog-->
</div><!--modal-fade-->


			<!--modal seleccionar regresados-->
			<div class="modal"  id="listaRegresos" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Entregar un prestamo</h5>
      </div>
      <div class="modal-body mx-4" style="height: 30rem;">
	  <p><strong>Selecciona las herramientas que se entregaron</strong></p>
			<div class="row">
				<div class="col-md-5">
					<form method="post" id="formEntrega">   
                        <!-- @csrf -->
						<div class="form-group">
						<label>Fecha:</label>
							<input type="text" class="form-control" id="fecha" name="fecha" value="<?=date("d-m-Y",time());?>" disabled >
						</div>
						<div class="form-group">
							<label>Solicitante:</label>
							<input type="text" name="solicitante" id="solicitanteRegreso" class="form-control" disabled/>
						</div>
						<div class="form-group">
							<label>Comentario:</label>
							<textarea name="comentario" id="comentarioOriginal" class="form-control" rows="2" disabled></textarea>
							<small style="color: rgb(122, 122, 122)">*Este es el comentario que se realizó al momento del prestamo</small>
						</div>	
					</form>
					<br />
				</div> <!--.col-md-5-->

				<div class="d-flex flex-column col-md-7">
					<div class="table-wrapper-scroll-y my-custom-scrollbar  p-3">
						<div class="table-responsive">
							<table class="table table-bordered" id="tabla-entregados">
								<colgroup>
								<col span="1" style="width: 10%;">
								<col span="1" style="width: 80%;">
								<col span="1" style="width: 5%;">
								</colgroup>
								<thead>
									<tr>
										<th>Codigo</th>
										<th>Herramienta</th>
										<th id="th-qty">#</th>
										<th>
											<input type="checkbox" value="" name="checkbox_maestro">											
										</th>
									</tr>
								</thead>
								<tbody id="body_seleccionadas">

								</tbody>
							</table>
						</div>
					</div>
				</div>
      </div>
	</div><!--modal-body-->
      <div class="modal-footer">
	  	<button type="button" class="btn btn-secondary" id="closeEntregar">Regresar a prestamos</button>
        <button type="button" class="btn btn-success" id="btnEntregar">Entregar</button>
      </div>
    </div>
  </div>
</div>



<!--modal-resumen-->
<div class="modal" id="confirmEntrega" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="">Resumen de la entrega</h5>
					</div>
					<div class="modal-body">
						<p>¿Confirmas que las siguientes herramientas y sus cantidades fueron entregadas?</p>
						<div class="table-wrapper-scroll-y my-custom-scrollbar-resumen">
							<ul id="entrega-list">
							</ul>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="btnCerrarResumen">Cerrar</button>
						<button type="button" class="btn btn-success" id="btnConfirmarEntrega">Confirmar</button>
					</div>
					</div>
				</div>
			</div><!--modal resumen-->


<!--modal-faltantes-->
<div class="modal" id="modalFaltantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id=""><strong>Herramientas faltantes</strong></h5>
					</div>
					<div class="modal-body">
						<p>Las siguientes herramientas no fueron entregadas</p>
						<div class="table-wrapper-scroll-y my-custom-scrollbar-resumen">
							<ul id="faltantes-list">
							</ul>
						</div>
						<p>Estas se almacenarán junto con el motivo que nos proporcione</p>
						<div class="form-group">
							<textarea type="text" id="motivoTxt" class="form-control" placeholder="Escribe aqui el motivo ej. robo, extravío, etc." rows="1"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" id="btnConfirmarFaltantes">Confirmar</button>
					</div>
					</div>
				</div>
			</div><!--modal faltantes-->


		

          
</div>

<script>
	$(document).ready(function(){
		var entregados = [];
		var faltantes = [];
		var id_kardex = '';
		
		

		function obtenerPrestamos(){
			$.ajax({
			url:"inventario/getPrestamos",
			method:"GET",
			error: function(data) {
				toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
				return false;
			},
				success:function(data)
			{
				if(data == "No hay ningun prestamo pendiente"){
					let tbody = `<tr>
									<td colspan=3 style="text-align: center;">${data}</td>
								</tr>`;;
					
					$("#tabla-prestamos tbody").html(tbody);
				}else{
					let prestamos = JSON.parse(data);
					listarPrestamos(prestamos);
				}
				
			},
			});
		}

		function listarPrestamos(prestamos){
			var tbody = '';
			prestamos.forEach(prestamo => {
				tbody += `<tr class="prestamos-tr">
								<td>${prestamo["id"]}</td>
								<td>${prestamo["fecha"]}</td>
								<td>${prestamo["solicitante"]}</td>
							</tr>`;
			});

			$("#tabla-prestamos tbody").html(tbody);


			$('.prestamos-tr').hover(function(){
				var id_mov = $(this).find("td").eq(0)[0].innerHTML;
				var tr_mov = $(this).closest("tr");
				previewHerramientas(id_mov, tr_mov);
			});


			$('.prestamos-tr').click( function() {
					var id_mov = $(this).find("td").eq(0)[0].innerHTML;
					//var solicitante = $(this).find("td").eq(2)[0].innerHTML;
					cargarEntrega(id_mov);
					$('input:checkbox').prop('checked', false);


				var backdropHeight = window.screen.height;
				$('#backdrop').css('height', backdropHeight);
				$('#backdrop').fadeIn(100);

				$("#listaRegresos").show(); //no funciona con efectos
				$("#listaRegresos").css('opacity', '1');
 
				$("#regresoModal").hide(); // no pueden existir dos modales al mismo nivel, en el segundo no se puede dar focus al input, z-index no lo soluciona

			});

		}//listarPrestamos

		function previewHerramientas(id, tr_mov){
			
			$.ajax({
				url:"inventario/getPrestamoDetalle/"+id,
				method:"GET",
				error: function(data) {
					toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
					return false;
				},
					success:function(data)
				{
					let entregas = JSON.parse(data);
					let title_body = '';

					entregas.forEach(entrega => {
						title_body += `${entrega["descripcion"]} (${entrega["qty"]}) | `;
					});

					title_body = title_body.substr(0, title_body.length -2);
					tr_mov.prop("title", title_body);
				},
			});
		}

		function cargarEntrega(id){
			//cargar las herramientas que serán entregadas
			$.ajax({
			url:"inventario/getPrestamoDetalle/"+id,
			method:"GET",
			error: function(data) {
				toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
				return false;
			},
				success:function(data)
			{
				let entregas = JSON.parse(data);
				id_kardex = id; //succes - si existe este movimiento
				listarEntregadas(entregas);

			},
			});

		}

		function listarEntregadas(entregas){
			//listar herramientas que serán entregadas
			var tbody = '';
			let comentarioP = entregas[0]["comentario"];
			let solicitante = entregas[0]["solicitante"]
			$("#solicitanteRegreso").val(solicitante);
			$("#comentarioOriginal").val(comentarioP);
			entregas.forEach(entrega => {
				tbody += `<tr>
								<td>${entrega["id_herramienta"]}</a></td>
								<td>${entrega["descripcion"]}</td>
								<td>${entrega["qty"]}</td>
								<td><input type="checkbox" value="" name="checkboxes"></td>
							</tr>`;
			});

			$("#tabla-entregados tbody").html(tbody);

			$('input[name=checkboxes]').click(function(){
			 $('input[name=checkbox_maestro]').prop("checked", false);
			});	
		}

		//manda las herramientas entregadas al backend
		function confirmarEntrega(){
			var lista_faltantes = "";
			var solicitante = $('#solicitanteRegreso').val();
	
			if(entregados.length > 0){
				$('#btnConfirmarEntrega').text('Confirmando....');	

				$.ajax({
					url:"inventario/regresarPrestamo",
					method:"POST",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					//enviar el codigo o serie de la herramienta
					data:{entregadas_lista: entregados , solicitante: solicitante, id: id_kardex},
					error: function(error) {
						toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
						return false;
					},
					success:function(data)
					{
						if(faltantes.length > 0){
							faltantes.forEach(faltante => {
								lista_faltantes += `<li><strong>${faltante['descripcion']}</strong> - #${faltante['codigo']} (${faltante['cantidad']})</li>`
							});

							$('#faltantes-list').html(lista_faltantes);

							setTimeout(function(){
								toastr.warning("Algunas herramientas no fueron entregadas", 'Advertencia', {timeOut: 3000});
								var backdropHeight = window.screen.height;
								$('#backdrop').css('height', backdropHeight);
								$('#backdrop').fadeIn(100);
								$("#motivoTxt").val($('#motivoTxt').prop("defaultValue"));
								$("#btnConfirmarFaltantes").text('Confirmar');
								$('#btnConfirmarFaltantes').prop('disabled', false);
								$("#modalFaltantes").show();
								$("#modalFaltantes").css('opacity', '1');
							}, 2000);	
						}else{
							toastr.success(data, 'Exito', {timeOut: 2000});	
							cerrarConfirmado();	
						}

					},
				});
			}else{
				toastr.error('Hay un error en la lista de herramientas', 'Error en la lista', {timeOut: 3000});
				return false;
			}
		}

		function cerrarConfirmado(){
			setTimeout(function(){
				entregados = [];
				faltantes = [];
				$('#backdrop').fadeOut(100);
				$('#confirmEntrega').hide();
				$('#modalFaltantes').hide();
				$('#regresoModal').modal('toggle');
				$('#listaRegresos').hide();
				$('#tabla-entregados tbody').html('');
				$('#formEntrega')[0].reset();
				$('#btnConfirmarEntrega').prop('disabled', false);
				location.reload();
			},2500);
		}


		//click a checkbox maestro
		$('input[name=checkbox_maestro]').click(function(){
			 var state = $(this).is(":checked") ? true : false;
			 $('input[name=checkboxes]').prop('checked', state);
		});
	

		//boton regresar a prestamos
		$('#closeEntregar').click( function() {
			$('#listaRegresos').hide();
			//aclarar fondo
			$('#backdrop').fadeOut(100);
			$("#regresoModal").show();
		});

	
		//boton en index que abre el modal con la lista de prestamos
		$("#buttonRegreso").click(function(){
			obtenerPrestamos();
		});
	
		//boton confirmar en modal de resumen de entrega
		$("#btnConfirmarEntrega").click(function(){
			$('#btnConfirmEntrega').text('Confirmar');
			$('#btnConfirmarEntrega').prop('disabled', true);
			confirmarEntrega();
		});

		$('#btnEntregar').click(function(){
			var checked_array = [];
			var unchecked_array = [];
			var codigos_array = [];
			var lista = "";
			var lista_faltantes = "";
	

			$('input[name=checkboxes]').each(function(){
				if($(this).is(":checked")){
					checked_array.push($(this));
				}else{
					unchecked_array.push($(this));
				};
			});	
		
				checked_array.forEach(checked => {				
					codigo = checked.closest('tr').find('td').eq(0)[0].innerHTML;
					descripcion = checked.closest('tr').find('td').eq(1)[0].innerHTML;
					cantidad = checked.closest('tr').find('td').eq(2)[0].innerHTML;

					entregados.push({
							codigo: codigo,
							descripcion: descripcion,
							cantidad: cantidad
					})
					
				});

				if(entregados.length>0){

					if(unchecked_array.length > 0){
						unchecked_array.forEach(unchecked => {				
						codigo = unchecked.closest('tr').find('td').eq(0)[0].innerHTML;
						descripcion = unchecked.closest('tr').find('td').eq(1)[0].innerHTML;
						cantidad = unchecked.closest('tr').find('td').eq(2)[0].innerHTML;

						faltantes.push({
								codigo: codigo,
								descripcion: descripcion,
								cantidad: cantidad
						})

						});

					}


					entregados.forEach(entregado => {
					lista += `<li><strong>${entregado['descripcion']}</strong> - #${entregado['codigo']} (${entregado['cantidad']})</li>`
					});

					$('#entrega-list').html(lista);

					var backdropHeight = window.screen.height;
					$('#backdrop').css('height', backdropHeight);
					$('#backdrop').fadeIn(100);
					$('#btnConfirmarEntrega').text('Confirmar');
					$("#confirmEntrega").show();
					$("#confirmEntrega").css('opacity', '1');
				}else{
					toastr.warning('Confirma que regresaron al menos una herramienta', 'No seleccionaste herramientas', {timeOut: 3000});
				}

			
		})

		$("#btnCerrarResumen").click(function(){
			entregados = [];
			faltantes = [];
			$('#confirmEntrega').hide();
			$('#backdrop').fadeOut(100);
		});

		//boton confirmar en modal de faltantes
		$("#btnConfirmarFaltantes").click(function(){
			let motivo = $("#motivoTxt").val();
			$(this).text('Confirmando...');
			$(this).prop('disabled', true);
			
			

			$.ajax({
					url:"inventario/insertFaltantes",
					method:"POST",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					//enviar el codigo o serie de la herramienta
					data:{faltantes: faltantes , motivo: motivo, id: id_kardex},
					error: function(error) {
						toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
						return false;
					},
					success:function(data)
					{
						toastr.success("Se realizó el regreso", 'Exito', {timeOut: 2000});
						cerrarConfirmado();	
					}

				});			

		});

	});

</script>