<div class="container">
 <div class="modal fade" id="ajustesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">Lista de herramientas faltantes</h5>
          </div>
          <div class="modal-body mx-4" style="height: 30rem;">
			<div class="row">
				<div class="d-flex flex-column col-md-12">
					<p>Estas son las herramientas <strong>que no han sido regresadas:</strong></p>
					<div class="table-wrapper-scroll-y my-custom-scrollbar  p-3">
						<div class="table-responsive">
							<table class="table table-bordered" id="tabla-faltantes">
								<colgroup>
								<col span="1" style="width: 15%;">
								<col span="1" style="width: 15%;">
								<col span="1" style="width: 5%;">
                                <col span="1" style="width: 20%;">
								<col span="1" style="width: 5%;">
								<col span="1" style="width: 30%;">
                                <col span="1" style="width: 20%;">
								</colgroup>
								<thead>
								<th>Codigo</th>
                                        <th>Herramienta</th>
                                        <th>Cantidad</th>
										<th>Fecha de prestamo</th>
										<th>Solicitante</th>
                                        <th>Motivo</th>
                                        <th>Recuperado</th>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrar-ajustes">Cerrar</button>
      </div>
		</div><!--.modal-content-->
	  </div><!--.menu-dialog-->
</div><!--modal-fade-->



<!--modal-resumen-->
<div class="modal" id="confirmEliminacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<p class="modal-title" id="">Confirmas la eliminación <strong>permantente</strong> de esta herramienta?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="btnCerrarEliminacion">Cancelar</button>
						<button type="button" class="btn btn-danger" id="btnConfirmarEliminacion">Eliminar</button>
					</div>
					</div>
				</div>
			</div><!--modal resumen-->

</div>


<script>
	$(document).ready(function(){

        function obtenerFaltantes(){
			$.ajax({
			url:"inventario/fetchFaltantes",
			method:"GET",
			error: function(data) {
				toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
				return false;
			},
				success:function(data)
			{
			
				if(data == "No hay ninguna herramienta pendiente"){
					let tbody = `<tr>
									<td colspan=7 style="text-align: center;">${data}</td>
								</tr>`;;
					
					$("#tabla-faltantes tbody").html(tbody);
				}else{
					let faltantes = JSON.parse(data);
					listarFaltantes(faltantes);
				}
				
			},
			});
		}

		function listarFaltantes(faltantes){
			var tbody = '';
			faltantes.forEach(faltante => {
				tbody += `<tr class="faltantes-tr">
								<td>${faltante["codigo"]}</td>
								<td>${faltante["descripcion"]}</td>
								<td>${faltante["cantidad"]}</td>
								<td>${faltante["fecha"]}</td>
								<td>${faltante["solicitante"]}</td>
								<td>${faltante["motivo"]}</td>
								<td style="text-align:center;"><button class="btn btn-success btn-sm link-recuperar" value="${faltante["id"]}" title="Recuperarla">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
										<path d="M5 12l5 5l10 -10" />
										</svg>
									</button>
									<button class="btn btn-danger btn-sm link-perder" value="${faltante["id"]}" title="Darla por perdida">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
										<line x1="18" y1="6" x2="6" y2="18" />
										<line x1="6" y1="6" x2="18" y2="18" />
										</svg>
									</button>
								</td>
							</tr>`;
			});

			$("#tabla-faltantes tbody").html(tbody);

			
			$(".link-recuperar").click(function(e){
				e.preventDefault;
				let codigo = $(this).attr("value");
				recuperarHerramienta(codigo);
			})


			$(".link-perder").click(function(e){
				e.preventDefault;
				let codigo = $(this).attr("value");
				toggleConfirmacion(codigo);
			})

		}//listarFaltantes


		function toggleConfirmacion(codigo){
				$("#btnConfirmarEliminacion").val(codigo);
				$('#btnConfirmarEliminacion').text('Eliminar');	

				var backdropHeight = window.screen.height;
				$('#backdrop').css('height', backdropHeight);
				$('#backdrop').fadeIn(100);

				$("#confirmEliminacion").show(); //no funciona con efectos
				$("#confirmEliminacion").css('opacity', '1');
			
		}

		function recuperarHerramienta(codigo){
			
			$.ajax({
					url:"inventario/confirmarPendiente",
					method:"POST",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					//enviar el codigo o serie de la herramienta
					data:{id: codigo, accion: "recuperar"},
					error: function(error) {
						toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
						return false;
					},
					success:function(data)
					{
						toastr.success(data, 'Exito', {timeOut: 2000});	
						obtenerFaltantes();
					},
				});
			
		}

		function perderHerramienta(codigo){

			$.ajax({
					url:"inventario/confirmarPendiente",
					method:"POST",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					//enviar el codigo o serie de la herramienta
					data:{id: codigo, accion: "eliminar"},
					error: function(error) {
						toastr.error('Hubo un error en la parte del servidor', 'Error', {timeOut: 3000});
						return false;
					},
					success:function(data)
					{
						toastr.error(data, 'Herramienta eliminada', {timeOut: 500});	
						setTimeout(function(){
							$('#backdrop').fadeOut(100);
							$('#confirmEliminacion').hide();
							$('#btnConfirmarEliminacion').prop('disabled', false);
							obtenerFaltantes();
						}, 1000);	
						
					},
				});
		}


        $("#link-ajuste").click(function(e){
            e.preventDefault;
			obtenerFaltantes();
        });


		$("#cerrar-ajustes").click(function(){
			$('#tabla-inventario').DataTable().ajax.reload(null, false);
			//location.reload();
        });

		$("#btnConfirmarEliminacion").click(function(){
			let codigo = $(this).attr("value");
			$('#btnConfirmarEliminacion').text('Eliminando...');
			$('#btnConfirmarEliminacion').prop('disabled', true);
			perderHerramienta(codigo);
		});


		$("#btnCerrarEliminacion").click(function(){
			$('#backdrop').fadeOut(100);
			$('#confirmEliminacion').hide();
		});


    });
</script>