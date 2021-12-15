@extends('layouts.plantilla')

@section('contenido')
<div class="container">
          <p><strong>Selecciona las herramientas que serán prestadas</strong></p>
			<div class="row">
				<div class="col-md-5">
					<form method="post" id="sample_form">   
                        <!-- @csrf -->
						<div class="form-group">
						<label>Fecha:</label>
							<input type="text" class="form-control" id="fecha" name="fecha" value="<?=date("d-m-Y",time());?>" disabled >
						</div>
						<div class="form-group">
							<label>Solicitante:</label>
							<input type="text" id="solicitanteTxt" class="form-control" placeholder="A quien será prestada la herramienta" disabled>
						</div>
						<div class="form-group">
							<label>Comentario:</label>
							<textarea name="comentario" id="comentarioTxt" class="form-control" rows="2" placeholder="Ej. las pinzas ya estaban rotas al momento del préstamo" title="el comentario se ligará al préstamo"></textarea>
							<small style="color: rgb(122, 122, 122)">*Opcional</small>
						</div>	
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
                                        <th>Max</th>
										<th id="th-qty">#</th>
										<th><a class="btn btn-danger btn-xs" style="pointer-events: none;">
										<!-- width="10" height="10" viewBox="5 -4 14 24" -->
											<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="2 0 20 20" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
						<div>
							<button type="button" id="btnFlush" class="btn btn-primary ml-3">Vaciar</button>
                            <button type="button" id="btnPrestamo" class="btn btn-success">Hacer prestamo</button>
						</div>
				</div>

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



        </div><!--.container-->


<script>
    //si quiero heredar de la plantilla para obtener los recursos de los links, pero no quiero poder cambiar entre paginas
    $("document").ready(function(){

    $("nav").css('display', 'none');
    $("body").css('background-color', 'white'); 

     var selected = []; //codigos,descripciones cantidades de las herramientas seleccionadas
	$(document).tooltip();

	const href = window.location.search;
	const urlParams = new URLSearchParams(href);
	const ticket = urlParams.get('ticket');

	//si recibo un id de ticket, cargar herramientas
	if(ticket !== null){
		
		$.ajax({
			url:"inventario/getTicket/"+ticket,
			method:"GET",
			error: function(data) {
			toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
			return false;
			},
			success:function(data)
			{
				let herramientas = data[0];
				let solicitante = data[1];
				$('#solicitanteTxt').val(solicitante);
				mostrarHerramientas(herramientas);
			},
		});

	}


    //funcion agregar herramientas si viene desde un ticket
	function mostrarHerramientas(herramientas){
		
		herramientas.forEach(herramienta => {
			var codigo = '';
			var id = herramienta.herramienta;
			var descripcion = herramienta.descripcion.charAt(0).toUpperCase() + herramienta.descripcion.slice(1);
			var cantidad = herramienta.qty_peticion;

			if(herramienta.codigo == null){
			codigo = herramienta.numserie;
			}else{
			codigo = herramienta.codigo;
			}

			$.ajax({
				url:"inventario/getTool/"+codigo,
				method:"GET",
				error: function(data) {
					toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
					return false;
				},
				success:function(data)
				{
					var row = '';
					if(data !== null){
						var herramientaDB = data;
						verifyQty(herramientaDB, codigo, id, descripcion, cantidad);
					}

				},
			});
			
		});
	}// funcion mostrarHerramientas


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
				data:{selected_list: selected, comentario: comentario, ticket: ticket, solicitante: solicitante},
				error: function(error) {
					toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
					return false;
				},
				success:function(data)
				{
					setTimeout(function(){
						selected = [];
						$('#backdrop').fadeOut(100);
						$('#confirmPrestamo').hide();
						$('#tabla-seleccionados tbody').html('');
						$('#sample_form')[0].reset();
						toastr.success(data, 'Exito', {timeOut: 3000});
					},2000);	
				},
			});

			
		}else{
			toastr.error('Hay un error en la lista de herramientas', 'Error en la lista', {timeOut: 3000});
			return false;
		}
	}


	


	function verifyQty(herramientaDB, codigo, id, descripcion, cantidad){
		var row = '';     

				if(herramientaDB.descripcion !== descripcion || herramientaDB.id !== id){
					toastr.error('Alguna herramienta no existe', 'Error', {timeOut: 3000});
					return false;	
				}else if(parseInt(cantidad) <= parseInt(herramientaDB.qtyf)){

					row += `<tr>
									<td class="herramientaIDCell style-td">${codigo}</td>
									<td>${descripcion}</td>
									<td>${herramientaDB.qtyf}</td>
									<td class="qty-ok qty"><a href="#" class="increment desc">-</a>${cantidad}<a href="#" class="increment asc">+</a></td>
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

							
					
				}else{
					row += `<tr>
									<td class="herramientaIDCell style-td">${codigo}</td>
									<td>${descripcion}</td>
									<td>${herramientaDB.qtyf}</td>
									<td class="qty-error qty"><a href="#" class="increment desc">-</a>${cantidad}<a href="#" class="increment asc">+</a></td>
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
				}	



				selected.push({
					codigo: codigo,
					descripcion: descripcion,
					cantidad: cantidad
				});

				$('#tabla-seleccionados').append(row);

	}


	$("tbody").on('DOMSubtreeModified', function() {
					//va estrictamente aqui, no en document.ready porque al momento de que el archivo está listo, los elementos <a class=".incrementador"> aun no estan cargados
			$('.asc').on('click', function(e){
				//++ a la canitdad solicitada
				e.stopPropagation(e);
				e.stopImmediatePropagation(e);

				var tr_qty = $(this).closest('tr');
				if(tr_qty != "" && typeof tr_qty == "object"){
					var max = (tr_qty.find("td").eq(2)[0].innerHTML);
					var qty = (tr_qty.find("td").eq(3)[0].innerHTML);
					var qty_requested = qty.split('<a href="#" class="increment asc">')[0].substring(40);
					var qty_new = (parseInt(qty_requested))+1;
					var td_new = (tr_qty.find("td").eq(3)[0]);

					if(parseInt(qty_new) > parseInt(max)){
						td_new.classList.remove("qty-ok");
						td_new.classList.add("qty-error");
					}
					
					var td_new_txt =`<a href="#" class="increment desc">-</a>${qty_new}<a href="#" class="increment asc">+</a>`
					td_new.innerHTML = (td_new_txt);

				}			
			})
				
			

			$('.desc').on('click', function(e){
				//-- a la cantidad solicitada
				e.stopPropagation();
				e.stopImmediatePropagation();

				var tr_qty = $(this).closest('tr');
				if(tr_qty != "" && typeof tr_qty == "object"){
					var max = (tr_qty.find("td").eq(2)[0].innerHTML);
					var qty = (tr_qty.find("td").eq(3)[0].innerHTML);
					var qty_requested = qty.split('<a href="#" class="increment asc">')[0].substring(40);
					if(parseInt(qty_requested) > 1){
						var qty_new = (parseInt(qty_requested))-1;
						var td_new = (tr_qty.find("td").eq(3)[0]);

						if((parseInt(qty_new) <= parseInt(max)) && td_new.classList.contains("qty-error")){
							td_new.classList.remove("qty-error");
							td_new.classList.add("qty-ok");
						}

						var td_new_txt =`<a href="#" class="increment desc">-</a>${qty_new}<a href="#" class="increment asc">+</a>`
						td_new.innerHTML = (td_new_txt);
						console.log(qty_new);
					}else{
						toastr.error('No puedes elegir menos de 1 unidad', 'Error', {timeOut: 3000});
					}
				
				}			
			})
	 });


//botn hacer el prestamo
	$('#btnPrestamo').click(function(){
		let resumen = '';
		let lista_exceso = '';
		let excesivos = [];

		 var elementos_error = $('.qty-error');
		 if(elementos_error.length !== 0){

			elementos_error.each(function(){
				var herramienta_exceso = $(this).closest('tr').find("td").eq(1)[0].innerHTML;
				lista_exceso += `
						<li><strong>${herramienta_exceso}</strong></li>
						`;

			});

			$("#exceso_ul").html(lista_exceso);

			var backdropHeight = window.screen.height;
			$('#backdrop').css('height', backdropHeight);
			$('#backdrop').fadeIn(100);

				$("#ticket-warning").show(); //no funciona con efectos
				$("#ticket-warning").css('opacity', '1');


			
		 }else{
			if(ticket !== null){

				var cantidades = [];

				var td_cantidades = $('#tabla-seleccionados td:nth-child(4)');


				//si ya todas las cantidades estn correctas
				if(td_cantidades.length == selected.length){
					

					for(let i=0; i<selected.length; i++){
						var qty = td_cantidades[i].innerHTML.split('<a href="#" class="increment asc">')[0].substring(40);
						selected[i].cantidad = qty;
					}

				}
			}


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
		 }
	});

    //cerrar modal de resumen
	$('#closemodalR').click(function(){
		$("#btnPrestamo").css( "display", "inline-block" ); 
		$('#confirmPrestamo').hide();
		//aclarar fondo
		$('#backdrop').fadeOut(100);
	})

//cerrar modal de advertencia de exceso de cantidad
	$('#close-ticket-warning').click(function(){
		$("#ticket-warning").hide();
		$('#backdrop').fadeOut(100);
	});


	$('#btnConfirmPrestamo').click(function(){
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


    //cerrar modal de vaciar lista
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

@endsection