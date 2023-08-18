/**
 * Theme: Simulor - Responsive Bootstrap 4 Admin Dashboard
 * Author: Coderthemes
 * Module/App: Data tables 
 */


$(document).ready(function() {

    // Default Datatable
    $('#basic-datatable').DataTable({
        "language": {
            "paginate": {
                "previous": "<i class='mdi mdi-chevron-left'>",
                "next": "<i class='mdi mdi-chevron-right'>"
            }
        },
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        },
        "pageLength": 100,
        "ordering": false
    });

    $('.import-datatable').DataTable({
        "language": {
            "paginate": {
                "previous": "<i class='mdi mdi-chevron-left'>",
                "next": "<i class='mdi mdi-chevron-right'>"
            }
        },
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        },
        "pageLength": 25,
        "ordering": false
    });

    $('.basic-datatable').DataTable({
        "language": {
            "paginate": {
                "previous": "<i class='mdi mdi-chevron-left'>",
                "next": "<i class='mdi mdi-chevron-right'>"
            }
        },
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        },
        "pageLength": 100,
        "ordering": false
    });

    $(".clients-datatable").DataTable({
        "iDisplayLength": 25,
        "language": {
             "paginate": {
                 "previous": "<i class='mdi mdi-chevron-left'>",
                 "next": "<i class='mdi mdi-chevron-right'>"
             }
         },
         "drawCallback": function () {
             $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
         },
        "ordering": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/admin/clients/getPaginator"
            }
        }).on('page.dt',function(){
            // window.scrollTo(0,0);
        });
} );
    