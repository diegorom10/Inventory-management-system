 <div class="container">
 <div class="modal fade" id="prestamoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Hacer un prestamo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body mx-4" style="height: 35rem;">
          <p><strong>Selecciona las herramientas que serán prestadas</strong></p>
			<div class="row">
				<div class="col-md-5">
					<form method="post" id="sample_form">   
                        <!-- @csrf -->
						<div class="form-group">
						<label>Fecha del préstamo (Hoy):</label>
							<input type="text" class="form-control" id="fecha" name="fecha" value="<?=date("d-m-Y",time());?>" disabled >
						</div>
						<div class="form-group">
							<label>Solicitante:</label>
							<input type="text" id="solicitanteTxt" class="form-control" placeholder="A quien será prestada la herramienta">
						</div>
						<div class="form-group">
							<label>Comentario:</label>
							<textarea name="comentario" id="comentarioTxt" class="form-control" rows="2" placeholder="Préstamo ordinario (predeterminado)" title="Ej. las pinzas ya estaban rotas al momento del préstamo"></textarea>
							<small style="color: rgb(122, 122, 122)">*Opcional</small>
						</div>

						<div class="elementos-opcionales">
							<div class="form-group">
								<label>Herramientas Disponibles:</label>
								<div class="selDiv">
									<select name="herramienta" id="herramienta_select" class="form-control" >
										<option></option> <!--editable select debe al menos una opcion al momento de hacer focus-->
									</select>
								</div>
							</div>
							
							<div class="d-flex align-items-end">
								<div class="form-group col-md-4" style="padding-left: 0px">
									<label>Cantidad:</label>
									<input type="number" min="1" max="100" name="cantidad" id="cantidad_input" class="form-control">
								</div>
								<div class="form-group">
									<input type="hidden" name="action" id="action" value="add" />
									<input type="hidden" name="hidden_id" id="hidden_id" value="" />
									<button type="submit" title="Agregar a la lista" name="Save" id="save" class="btn btn-success" value="Save">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<circle cx="12" cy="12" r="9"></circle>
										<line x1="9" y1="12" x2="15" y2="12"></line>
										<line x1="12" y1="9" x2="12" y2="15"></line>
										</svg>
									</button>
								</div>
							</div>
						</div>	<!-- .elementos-opcionales-->
					</form>
					<br />
				</div> <!--.col-md-5-->

				<div class="d-flex flex-column col-md-7">
					<div class="table-wrapper-scroll-y my-custom-scrollbar  p-3">
						<div class="table-responsive">
							<table class="table table-bordered" id="tabla-seleccionados">
								<colgroup>
								<col span="1" style="width: 5%;">
								<col span="1" style="width: 83%;">
								<col span="1" style="width: 7%;">
								<col span="1" style="width: 5%;">
								</colgroup>
								<thead>
									<tr>
										<th>Codigo</th>
										<th>Herramienta</th>
										<th id="th-qty">#</th>
										<th><a class="btn btn-danger btn-xs" style="pointer-events: none;">
										<!-- width="10" height="10" viewBox="5 -4 14 24" -->
											<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="2 0 20 20" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H 0z" fill="none"/>
											<line x1="4" y1="7" x2="20" y2="7" />
											<line x1="10" y1="11" x2="10" y2="17" />
											<line x1="14" y1="11" x2="14" y2="17" />
											<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
											<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
											</svg>
											</a>
										</th>
									</tr>
								</thead>
								<tbody id="body_seleccionadas">

								</tbody>
							</table>
						</div>
					</div>
						<div class="elementos-opcionales">
							<button type="button" id="btnFlush" class="btn btn-primary ml-3">Vaciar</button>
							
						</div>
				</div>
			</div>
			<br />
			<br />
			<br />


			<!-- Modal eliminar -->
			<div class="modal fade" id="confirmHer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Vaciar lista</h5>
					</div>
					<div class="modal-body">
						¿Desea vaciar la lista de herramientas seleccionadas?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="closemodalF">Close</button>
						<button type="button" id="btnConfirmHer" name="btnConfirmHer" class="btn btn-primary">Eliminar</button>
					</div>
					</div>
				</div>
			</div><!--modal eliminar-->

	<!--Modal resumen-->
			<div class="modal fade" id="confirmPrestamo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Resumen de préstamo</h5>
					</div>
					<div class="modal-body">
						<div class="table-wrapper-scroll-y my-custom-scrollbar-resumen">
							<ul id="resumen-list">
							</ul>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="closemodalR">Cerrar</button>
						<button type="button" id="btnConfirmPrestamo" name="btnConfirmPrestamo" class="btn btn-primary">Confirmar</button>
					</div>
					</div>
				</div>
			</div><!--modal resumen-->


				<!--Modal advertencia ticket-->
				<div class="modal fade" id="ticket-warning" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-md" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h3>Error en la cantidad</h3>
					</div>
					<div class="modal-body">
					<p class="modal-title" id="exampleModalLabel">La siguientes herramientas sobrepasan la cantidad máxima de unidades, 
							disminuye la cantidad a prestar o elimina esos elementos de la lista.</p>
						<div class="table-wrapper-scroll-y my-custom-scrollbar-resumen">
							<ul id="exceso_ul">
							</ul>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="close-ticket-warning">Cerrar</button>
					</div>
					</div>
				</div>
			</div><!--modal advertencia ticket-->
 	
			
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" id="btnPrestamo" >Realizar prestamo</button>
          </div>
        </div>
      </div>
    </div>
</div>

<script>
$(document).ready(function(){
	var selected = []; //codigos,descripciones cantidades de las herramientas seleccionadas
	$('#herramienta_select').editableSelect();
	$('#herramienta_select').prop("placeholder", "Busca por descripcion o codigo/serie"); //select se convierte en input text al cargar editableSelect() 
	$(document).tooltip();

	// const href = window.location.search;
	// const urlParams = new URLSearchParams(href);
	// const ticket = urlParams.get('ticket')

	//si recibo un id de ticket, cargar modal automaticamente
	// if(ticket !== null){
	// 	$("#prestamoModal").modal('show');
	// 	$(".elementos-opcionales").css('display', 'none');

	// 	$('<th>Max</th>').insertBefore('#th-qty');
		
	// 	$.ajax({
	// 		url:"inventario/getTicket/"+ticket,
	// 		method:"GET",
	// 		error: function(data) {
	// 		toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
	// 		return false;
	// 		},
	// 		success:function(data)
	// 		{
	// 			mostrarHerramientas(data);
	// 		},
	// 	});

	//}



	function listarHerramientas(selected){
			$.ajax({
            url: 'inventario/fetchTools',
            type: 'GET',
            success: function(data) {
                let herramientas = JSON.parse(data);
				let options = '';
				let codigo = ''; // <- toma el valor de codigo o numserie dependiendo de la herramienta
				herramientas.forEach(herramienta => {
					codigo = herramienta.codigo == null ? herramienta.numserie : herramienta.codigo;

					options += `
                     <li class="es-visible">${herramienta.descripcion} | #${codigo} | ${herramienta.qtyf} disponibles</li>`
					;
					});
				$('.es-list').html(options);


					//deshabilitando los <li> de las herramientas que ya fueron seleccionadas:
				if(selected.length > 0){
					//$(".es-list li:contains(2125)").prop("style", "opacity: 0.6; pointer-events: none; display: none;"); <--imposible usar variable dentro de li:contains(), por eso el for
					lista = $(".es-list li");
					for (let i=0; i<lista.length; i++){
						for(let x=0; x<selected.length; x++){
							if(lista[i].innerHTML.includes(selected[x].codigo)){
								lista[i].classList.add("disabled");
							}
						}
					}
				}

			},
			error: function(data) {
				toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
				return false;
			}
        	});
	}

	function confirmarPrestamo(){
		var comentario = $('#comentarioTxt').val();
		var solicitante = $('#solicitanteTxt').val();
		


		if(selected.length > 0){
			$('#btnConfirmPrestamo').text('Confirmando....');	

			$.ajax({
				url:"inventario/hacerPrestamo",
				method:"POST",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				//enviar el codigo o serie de la herramienta
				data:{selected_list: selected, comentario: comentario, solicitante: solicitante},
				error: function(error) {
					toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
					return false;
				},
				success:function(data)
				{
					toastr.success(data, 'Exito', {timeOut: 2000});
					setTimeout(function(){
						selected = [];
						$('#backdrop').fadeOut(100);
						$('#confirmPrestamo').hide();
						$('#tabla-seleccionados tbody').html('');
						$('#sample_form')[0].reset();
						$("#prestamoModal").modal('hide');
						$('#btnConfirmPrestamo').prop('disabled', false);
						$('#tabla-inventario').DataTable().ajax.reload(null, false);
					},2500);
						
				},
			});
		}else{
			toastr.error('Hay un error en la lista de herramientas', 'Error en la lista', {timeOut: 3000});
			return false;
		}
	}

	//imprimir la cantidad disponible en el placeholder
	function placeholderMax(herramienta_codigo){
		$.ajax({
				url:"inventario/getTool/"+herramienta_codigo,
				method:"GET",
				//data:{codigo_serie: herramienta_codigo},
				error: function(data) {
					toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
					return false;
				},
				success:function(data)
				{
					const herramienta = data;
					const cantidad_maxima = "Max: " + herramienta.qtyf;
					$('#cantidad_input').prop("placeholder", cantidad_maxima);
				},
		});
	}

	//funcion añadir tr
	function añadirHerramienta(herramienta){
			var id_seleccionados = herramienta["id_seleccionados"];
			var codigo = herramienta["codigo"];
			var cantidad = herramienta["cantidad"];
			var descripcion = herramienta["descripcion"];
			var max = herramienta["max"];

			if(id_seleccionados != null && codigo != '' && cantidad != '' && descripcion != '' && max != ''){
				if(id_seleccionados.includes(codigo)){
				toastr.warning('Ya seleccionaste esta herramienta', 'Herramienta duplicada', {timeOut: 3000, positionClass: "toast-top-full-width"});
				return false;
				}else if(parseInt(cantidad) > parseInt(max)){
					toastr.error('Hay ' + max +' elementos de esta herramienta', 'Selecciona una cantidad menor', {timeOut: 3000});
					return false;
				}else{

					var row = `<tr>
								<td class="herramientaIDCell style-td">${codigo}</td>
								<td>${descripcion}</td>
								<td>${cantidad}</td>
								<td><a type="button" name="delete" class="btn btn-danger btn-xs delete">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"  width="20" height="20" viewBox="2 0 20 20"" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
										<line x1="4" y1="7" x2="20" y2="7" />
										<line x1="10" y1="11" x2="10" y2="17" />
										<line x1="14" y1="11" x2="14" y2="17" />
										<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
										<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
										</svg>
									</a>
								</td>
							</tr>`;

					$('#tabla-seleccionados').append(row);

					//$('#sample_form')[0].reset(); <-- no quiero resetar los input de fecha y comentario
					$('#herramienta_select').val($('#herramienta_select').prop("defaultValue"));
					$('#cantidad_input').val($('#cantidad_input').prop("defaultValue"));


					toastr.success('Agregaste una herramienta', 'Nueva herramienta', {timeOut: 3000});
					$('#cantidad_input').prop("placeholder", "");

					selected.push({
						codigo: codigo,
						descripcion: descripcion,
						cantidad: cantidad
					})

				}
			}else{
				toastr.error('No se pudo agregar la herramienta',  'Error', {timeOut: 3000});
				return false;
			}
			

	}

	//funcion agregar herramientas si viene desde un ticket
	// function mostrarHerramientas(herramientas){
		
	// 	herramientas.forEach(herramienta => {
	// 		var codigo = '';
	// 		var id = herramienta.herramienta;
	// 		var descripcion = herramienta.descripcion.charAt(0).toUpperCase() + herramienta.descripcion.slice(1);
	// 		var cantidad = herramienta.qty_peticion;

	// 		if(herramienta.codigo == null){
	// 		codigo = herramienta.numserie;
	// 		}else{
	// 		codigo = herramienta.codigo;
	// 		}

	// 		$.ajax({
	// 			url:"inventario/getTool/"+codigo,
	// 			method:"GET",
	// 			error: function(data) {
	// 				toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
	// 				return false;
	// 			},
	// 			success:function(data)
	// 			{
	// 				var row = '';
	// 				if(data !== null){
	// 					var herramientaDB = JSON.parse(data);
	// 					verifyQty(herramientaDB, codigo, id, descripcion, cantidad);

	// 				}

	// 			},
	// 		});
			
	// 	});
	// }// funcion mostrarHerramientas


	// function verifyQty(herramientaDB, codigo, id, descripcion, cantidad){
	// 	var row = '';     

	// 			if(herramientaDB.descripcion !== descripcion || herramientaDB.id !== id){
	// 				toastr.error('Alguna herramienta no existe', 'Error', {timeOut: 3000});
	// 				return false;	
	// 			}else if(parseInt(cantidad) <= parseInt(herramientaDB.qtyf)){

	// 				row += `<tr>
	// 								<td class="herramientaIDCell style-td">${codigo}</td>
	// 								<td>${descripcion}</td>
	// 								<td>${herramientaDB.qtyf}</td>
	// 								<td class="qty-ok qty"><a href="#" class="increment desc">-</a>${cantidad}<a href="#" class="increment asc">+</a></td>
	// 								<td><a type="button" name="delete" class="btn btn-danger btn-xs delete">
	// 									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"  width="20" height="20" viewBox="2 0 20 20"" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
	// 									<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
	// 									<line x1="4" y1="7" x2="20" y2="7" />
	// 									<line x1="10" y1="11" x2="10" y2="17" />
	// 									<line x1="14" y1="11" x2="14" y2="17" />
	// 									<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
	// 									<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
	// 									</svg>
	// 								</a>
	// 								</td>
	// 							</tr>`;

							
					
	// 			}else{
	// 				row += `<tr>
	// 								<td class="herramientaIDCell style-td">${codigo}</td>
	// 								<td>${descripcion}</td>
	// 								<td>${herramientaDB.qtyf}</td>
	// 								<td class="qty-error qty"><a href="#" class="increment desc">-</a>${cantidad}<a href="#" class="increment asc">+</a></td>
	// 								<td><a type="button" name="delete" class="btn btn-danger btn-xs delete">
	// 									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"  width="20" height="20" viewBox="2 0 20 20"" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
	// 									<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
	// 									<line x1="4" y1="7" x2="20" y2="7" />
	// 									<line x1="10" y1="11" x2="10" y2="17" />
	// 									<line x1="14" y1="11" x2="14" y2="17" />
	// 									<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
	// 									<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
	// 									</svg>
	// 								</a>
	// 								</td>
	// 							</tr>`;
	// 			}	

				

	// 			selected.push({
	// 				codigo: codigo,
	// 				descripcion: descripcion,
	// 				cantidad: cantidad
	// 			});

	// 			$('#tabla-seleccionados').append(row);

	// }


	// $("tbody").on('DOMSubtreeModified', function() {
	// 				//va estrictamente aqui, no en document.ready porque al momento de que el archivo está listo, los elementos <a class=".incrementador"> aun no estan cargados
	// 		$('.asc').on('click', function(e){
	// 			//++ a la canitdad solicitada
	// 			e.stopPropagation(e);
	// 			e.stopImmediatePropagation(e);

	// 			var tr_qty = $(this).closest('tr');
	// 			if(tr_qty != "" && typeof tr_qty == "object"){
	// 				var max = (tr_qty.find("td").eq(2)[0].innerHTML);
	// 				var qty = (tr_qty.find("td").eq(3)[0].innerHTML);
	// 				var qty_requested = qty.split('<a href="#" class="increment asc">')[0].substring(40);
	// 				var qty_new = (parseInt(qty_requested))+1;
	// 				var td_new = (tr_qty.find("td").eq(3)[0]);

	// 				if(parseInt(qty_new) > parseInt(max)){
	// 					td_new.classList.remove("qty-ok");
	// 					td_new.classList.add("qty-error");
	// 				}
					
	// 				var td_new_txt =`<a href="#" class="increment desc">-</a>${qty_new}<a href="#" class="increment asc">+</a>`
	// 				td_new.innerHTML = (td_new_txt);

	// 			}			
	// 		})
				
			

	// 		$('.desc').on('click', function(e){
	// 			//-- a la cantidad solicitada
	// 			e.stopPropagation();
	// 			e.stopImmediatePropagation();

	// 			var tr_qty = $(this).closest('tr');
	// 			if(tr_qty != "" && typeof tr_qty == "object"){
	// 				var max = (tr_qty.find("td").eq(2)[0].innerHTML);
	// 				var qty = (tr_qty.find("td").eq(3)[0].innerHTML);
	// 				var qty_requested = qty.split('<a href="#" class="increment asc">')[0].substring(40);
	// 				if(parseInt(qty_requested) > 1){
	// 					var qty_new = (parseInt(qty_requested))-1;
	// 					var td_new = (tr_qty.find("td").eq(3)[0]);

	// 					if((parseInt(qty_new) <= parseInt(max)) && td_new.classList.contains("qty-error")){
	// 						td_new.classList.remove("qty-error");
	// 						td_new.classList.add("qty-ok");
	// 					}

	// 					var td_new_txt =`<a href="#" class="increment desc">-</a>${qty_new}<a href="#" class="increment asc">+</a>`
	// 					td_new.innerHTML = (td_new_txt);
	// 					console.log(qty_new);
	// 				}else{
	// 					toastr.error('No puedes elegir menos de 1 unidad', 'Error', {timeOut: 3000});
	// 				}
				
	// 			}			
	// 		})
	//  });





	//listar herramientas disponibles cuando se da click en el select
	$(document).on('click', '#herramienta_select', function(e){
		e.preventDefault();
		$(this)[0].value = '';
		$('#cantidad_input').val($('#cantidad_input').prop("defaultValue"));
		listarHerramientas(selected);
	});
	
	//cambiar placeholder de campo cantidad dinamicamente
	$('#herramienta_select').focusout(function(e){
		e.preventDefault();
		var herramienta_input = $('#herramienta_select').val();
		if(herramienta_input !== ''){
			var herramienta_codigo = herramienta_input.split(' | ')[1].substring(1);
			placeholderMax(herramienta_codigo);
		}else{
			//si no se selecciona nada, quita el ultimo placeholder
			$('#cantidad_input').prop("placeholder", "");
		}
		
	})

	//submit - agregar herramienta a la lista
	$('#sample_form').on('submit', function(e){
			/*guardar ids de herramientas selccionadas para despues comprobar antes de añadir
			un nuevo row a la tabla con una herramienta repetida (esta es una comprobacion que ya no es necesaria gracias a que una vez que
			se agrega la herramienta, se deshabilita su opcion en el select)
			se deja en caso de que falle la hoja de estilos
		*/
		e.preventDefault();
		$('#save').prop('disabled', true);
		setTimeout(()=>{$('#save').prop('disabled', false)}, 200);


		var id_seleccionados = [];
		var opciones = [];
      

		$('#tabla-seleccionados .herramientaIDCell').each(function() {
    		id_seleccionados.push($(this).html());
	 	});


		if($('#herramienta_select').val() == '' )
		{
			$("#herramienta_select").effect("shake");
			toastr.warning('Selecciona una herramienta', 'Falta herramienta', {timeOut: 3000});
			return false;
		}else if($('#cantidad_input').val() == '')
		{
			$("#cantidad_input").effect("shake");
			toastr.warning('Indica la cantidad', 'Falta cantidad', {timeOut: 3000});
			return false;
		}else{

			//que el usuario no pueda modificar la herramienta seleccionada (gracias a editableSelect):
			$('.es-list li').each(function(){
				opciones.push($(this)[0].innerHTML)
			});

			//si ninguna el val de herramienta_select no coincide con las opciones
			if(opciones.length > 0 && opciones.includes($("#herramienta_select").val()) == false){
					alert("No modificar la opcion seleccionada por favor");
					return false;
			}


			var herramienta_input = $('#herramienta_select').val();
			var herramienta_descripcion =  herramienta_input.split(' | ')[0]; //importante los espacios
			var herramienta_codigo = herramienta_input.split(' | ')[1].substring(1);
			var cantidad = $('#cantidad_input').val();
            //var _token = $("input[name=_token]").val();
			var cantidad_maxima = "";
			var lista = [];



    		//Obtener la cantidad por herramienta
			if(herramienta_codigo != null){
				$.ajax({
				url:"inventario/getTool/"+herramienta_codigo,
				method:"GET",
				error: function(data) {
					toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
					return false;
				},
				success:function(data)
				{
					const herramienta = data;
					const cantidad_maxima = herramienta.qtyf;
					const herramientajson = {
						"id_seleccionados": id_seleccionados,
						"codigo": herramienta_codigo,
						"descripcion": herramienta_descripcion,
						"cantidad": cantidad,
						"max" : cantidad_maxima,
					}
					añadirHerramienta(herramientajson);
				},
				});
			}

		}

	});

//botn hacer el prestamo
	$('#btnPrestamo').click(function(){
		let resumen = '';
		
		if(selected.length > 0){

			if($('#solicitanteTxt').val() == ''){
			$("#solicitanteTxt").effect("shake");
			toastr.warning('Indica el solicitante', 'Falta el solicitante', {timeOut: 3000});
			return false;
			}

			selected.forEach(herramienta => {
			resumen += `
					<li><strong>${herramienta["descripcion"]}</strong> - #${herramienta["codigo"]} (${herramienta["cantidad"]})</li>
					`;
			});

			$("#resumen-list").html(resumen);

			$('#btnConfirmPrestamo').text('Confirmar');
			var backdropHeight = window.screen.height;
			$('#backdrop').css('height', backdropHeight);
			$('#backdrop').fadeIn(100);

			$("#confirmPrestamo").show(); //no funciona con efectos
			$("#confirmPrestamo").css('opacity', '1');
		

		}else{
			toastr.error('Agrega al menos una herramienta', 'La tabla está vacia', {timeOut: 3000});
			return false;
		}
		 
	});

	$('#closemodalR').click(function(){
		$("#btnPrestamo").css( "display", "inline-block" ); 
		$('#confirmPrestamo').hide();
		//aclarar fondo
		$('#backdrop').fadeOut(100);
	})

	$('#close-ticket-warning').click(function(){
		$("#ticket-warning").hide();
		$('#backdrop').fadeOut(100);
	});


	$('#btnConfirmPrestamo').click(function(){
		$(this).prop('disabled', true);
		confirmarPrestamo();
	});


//eliminar herramienta de la lista
	$(document).on('click', '.delete', function(){
		var delete_tr = $(this).closest('tr');
		if(delete_tr != "" && typeof delete_tr == "object"){
			var delete_td = (delete_tr.find("td").eq(0));
			var delete_id = delete_td[0].innerHTML;
			delete_tr.remove();

			//quitar el codigo de las herramientas seleccionadas (y deshabilitadas)
			let index = selected.map(function(e) { return e.codigo;}).indexOf(delete_id);
			if(index > -1){
				selected.splice(index,1);
			}
		}
	});

//vaciar lista
	$('#btnFlush').click(function(){
		$('#btnConfirmHer').text('Eliminar');

		//oscurecer fondo
		var backdropHeight = window.screen.height;
  		$('#backdrop').css('height', backdropHeight);
  		$('#backdrop').fadeIn(100);

		$('#confirmHer').show();
		$("#confirmHer").css('opacity', '1');
	});

	$('#closemodalF').click(function(){
		$('#confirmHer').hide();
		//aclarar fondo
		$('#backdrop').fadeOut(100);
	})


//confirmar vaciar herramienta
	$('#btnConfirmHer').click(function(){
		$('#btnConfirmHer').text('Eliminando....');

		setTimeout(function(){
		$('#backdrop').fadeOut(100);
		$('#confirmHer').hide();
		$('#tabla-seleccionados tbody').html('');
		$('#herramienta_select').val($('#herramienta_select').prop("defaultValue"));
		$('#cantidad_input').val($('#cantidad_input').prop("defaultValue"));
		$('#cantidad_input').prop("placeholder", "");
		selected = [];
		},500);
		
		
	});

});
</script>