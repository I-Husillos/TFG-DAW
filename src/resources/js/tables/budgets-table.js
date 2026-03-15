import $ from 'jquery';
import { dtLangEs } from './datatables-lang';

export function initBudgetsTable(apiUrl) {
    const tabla = $('#tabla-presupuestos');

    if (!tabla.length) return;

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
        order: [[1, 'desc']],
        ajax: {
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            data: function (d) {
                d.year  = $('#filter-year').val();
                d.month = $('#filter-month').val();
            },
            error: function (xhr) {
                console.error('Error DataTables budgets:', xhr.status, xhr.responseText);
            },
        },
        columns: [
            {
                data: 'category',
                className: 'align-middle',
                orderable: true,
            },
            {
                data: 'period',
                className: 'text-center align-middle',
                orderable: true,
            },
            {
                data: 'spent',
                className: 'text-right align-middle',
                orderable: true,
                render: function (data, type, row) {
                    return `<span class="text-${row.color} font-weight-bold">${data} €</span>`;
                },
            },
            {
                data: 'limit',
                className: 'text-right align-middle',
                orderable: true,
                render: function (data) {
                    return `${data} €`;
                },
            },
            {
                data: 'percentage',
                className: 'align-middle',
                orderable: true,
                render: function (data, type, row) {
                    const width = Math.min(data, 100);
                    return `
                        <div class="progress progress-sm mb-1">
                            <div class="progress-bar bg-${row.color}"
                                 style="width:${width}%" role="progressbar">
                            </div>
                        </div>
                        <small class="text-${row.color}">${data}% (alerta: ${row.threshold})</small>
                    `;
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
            { responsivePriority: 3, targets: 2 },
            { responsivePriority: 4, targets: 1 },
            { responsivePriority: 100, targets: 5 },
            { responsivePriority: 101, targets: 3 },
        ],
    });

    $('#filter-year, #filter-month').on('change', function () {
        tabla.DataTable().ajax.reload();
    });

    $('#clear-filters').on('click', function () {
        $('#filter-year').val(new Date().getFullYear());
        $('#filter-month').val(new Date().getMonth() + 1);
        tabla.DataTable().search('').ajax.reload();
    });
}