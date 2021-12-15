@extends('layouts.plantilla')

@section('contenido')
<div class="container">
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
							<label>Comentario:</label>
							<textarea name="comentario" id="comentarioRegreso" class="form-control" rows="2" placeholder="Ej. las pinzas ya estaban rotas al momento del regreso" title="el comentario se ligará a la entrega"></textarea>
							<small style="color: rgb(122, 122, 122)">*Opcional</small>
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
                        <div>
                            <button type="button" class="btn btn-success" id="btnEntregar">Entregar</button>
                        </div>
					</div>
				</div>
      </div>

      <!--modal-resumen-->
        <div class="modal" id="confirmEntrega" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog modal-md" role="document">
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


			 <!--modal-advertencia-pedido cerrado-->
			 <div class="modal" id="pedidoCerrado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog modal-md" role="document">
					<div class="modal-content">
						<div class="modal-body" id="advertencia-modal-body">
						</div>
					</div>
				</div>
			</div><!--modal-advertencia-pedido cerrado-->

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
	const ticket = urlParams.get('ticket')

	//si recibo un id de ticket, cargar herramientas
        if(ticket !== null){
            $.ajax({
                url:"inventario/getMovimiento/"+ticket,
                method:"GET",
                error: function(data) {
                toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
                return false;
                },
                success:function(data)
                {
					
					//obtener id del movimiento que le corresponde a ese ticket y cargar las herramientas
					if(data == "Este pedido ya fue regresado"){
						info = `<h3 style="text-align:center;">${data}</h3>
								<p style="text-align:center";>
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-check" width="100" height="100" viewBox="0 0 24 24" stroke-width="1.5" stroke="#cc0000" fill="none" stroke-linecap="round" stroke-linejoin="round">
									<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
									<rect x="4" y="4" width="16" height="16" rx="2" />
									<path d="M9 12l2 2l4 -4" />
									</svg>
								</p>`;

						$('#pedidoCerrado .modal-body').append(info)
						$('#pedidoCerrado').modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						})
					}else{
						cargarEntrega(data);
					}
					
                },
            });

        }


        var entregados = [];
		var id_kardex = '';
		

        //cargarEntrega(85);
		function cargarEntrega(id){
			$('input:checkbox').prop("checked",false);
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
			var comentario = $('#comentarioRegreso').val();
	
			if(entregados.length > 0){
				$('#btnConfirmarEntrega').text('Confirmando....');	

				$.ajax({
					url:"inventario/regresarPrestamo",
					method:"POST",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					//enviar el codigo o serie de la herramienta
					data:{entregadas_lista: entregados , comentario: comentario, id: id_kardex},
					error: function(error) {
						toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
						return false;
					},
					success:function(data)
					{
						console.log(data);
						setTimeout(function(){
							entregados = [];
							$('#backdrop').fadeOut(100);
							$('#confirmEntrega').hide();
							$('#regresoModal').modal('toggle');
							$('#listaRegresos').hide();
							$('#tabla-entregados tbody').html('');
							$('#formEntrega')[0].reset();
							toastr.success(data, 'Exito', {timeOut: 3000});
							$('#btnConfirmarEntrega').prop('disabled', false);
						},2000);
							
					},
				});
			}else{
				toastr.error('Hay un error en la lista de herramientas', 'Error en la lista', {timeOut: 3000});
				return false;
			}
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
			var codigos_array = [];
			var lista = "";
	

			$('input[name=checkboxes]').each(function(){
				if($(this).is(":checked")){
					checked_array.push($(this));
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
			$('#confirmEntrega').hide();
			$('#backdrop').fadeOut(100);
		});	

    });

</script>

@endsection