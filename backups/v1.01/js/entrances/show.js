$(document).ready(function(){
    var tablatipo = $('#tabla-entradas').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url: "/entradas",
    },
    columns:[
        {data: 'entrada',
        className: 'dt-body-center'},
        {data: 'action', orderable:false,
        className: 'dt-body-center'}
    ],
    responsive: true,
    autoWidth: false,
    "language": {
        "lengthMenu": "Mostrar _MENU_ registros por página",
        "zeroRecords": "Nada encontrado - Disculpa",
        "info": "Mostrando la página _PAGE_ de _PAGES_",
        "infoEmpty": "Sin registros disponibles",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        'search': 'Buscar',
        'paginate': {
            'next': 'Siguiente',
            'previous': 'Anterior',
        }
    }
    });
});