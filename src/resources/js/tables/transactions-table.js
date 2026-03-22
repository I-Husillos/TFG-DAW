import $ from 'jquery';
import { dtLangEs } from './datatables-lang';

export function initTransactionsTable(apiUrl) {
    const tabla = $('#tabla-transacciones');

    if (!tabla.length) return;

    // Verificamos que no esté ya inicializada
    if ($.fn.DataTable.isDataTable(tabla)) return;

    tabla.DataTable({
        processing: true,
        serverSide: true,
        responsive: {
            details: {
                type: 'inline',
                target: 'tr',
            },
        },
        autoWidth: false,
        language: dtLangEs,
        order: [[0, 'desc']],
        ajax: {
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            data: function (d) {
                d.type        = $('#filter-type').val();
                d.category_id = $('#filter-category').val();
                d.date_from   = $('#filter-date-from').val();
                d.date_to     = $('#filter-date-to').val();
                d.currency    = $('#filter-currency').val();
            },
            error: function (xhr) {
                console.error('Error DataTables transactions:', xhr.status, xhr.responseText);
            },
        },
        columns: [
            {
                data: 'date',
                className: 'align-middle',
                orderable: true,
            },
            {
                data: 'name',
                className: 'align-middle text-wrap',
                orderable: true,
            },
            {
                data: 'category',
                className: 'align-middle',
                orderable: true,
            },
            {
                data: 'type',
                className: 'text-center align-middle',
                orderable: true,
                render: function (data, type, row) {
                    const color = row.type_raw === 'income' ? 'success' : 'danger';
                    return `<span class="badge badge-${color}">${data}</span>`;
                },
            },
            {
                data: 'amount',
                className: 'text-right align-middle',
                orderable: true,
                render: function (data, type, row) {
                    const color = row.type_raw === 'income' ? 'text-success' : 'text-danger';
                    const sign  = row.type_raw === 'income' ? '+' : '-';
                    return `<span class="${color} font-weight-bold">${sign}${data} ${row.currency}</span>`;
                },
            },
            {
                data: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center align-middle',
            },
        ],
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 4 },
            { responsivePriority: 3, targets: 3 },
            { responsivePriority: 4, targets: 1 },
            { responsivePriority: 100, targets: 5 },
            { responsivePriority: 101, targets: 2 },
        ],
    });


    // Recarga al cambiar filtros
    $('#filter-type, #filter-category, #filter-date-from, #filter-date-to, #filter-currency')
        .on('change', function () {
            tabla.DataTable().ajax.reload();
        });

    // Botón limpiar filtros
    $('#clear-filters').on('click', function () {
        $('#filter-type, #filter-category').val('');
        $('#filter-date-from, #filter-date-to').val('');
        $('#filter-currency').val('');
        tabla.DataTable().search('').ajax.reload();
    });
}