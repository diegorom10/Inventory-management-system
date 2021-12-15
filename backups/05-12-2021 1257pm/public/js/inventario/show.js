$(document).ready(function(){

    var tablaInventario = $('#tabla-inventario').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url: "/inventario",
    },
    method: "GET",
    columns:[
        {data: 'codigo',
        className: 'dt-body-center'},
        {data: 'descripcion',
        className: 'dt-body-center'},
        {data: 'Cantidad original',
        className: 'dt-body-center'},
        {data: 'Cantidad fisica',
        className: 'dt-body-center disponible'},
        {data: 'Cantidad comprometida',
        className: 'dt-body-center prestado'},

    ],
    responsive: true,
    autoWidth: false,
    "language": {
        searchPlaceholder: "Codigo | Serie | Descripcion",
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
