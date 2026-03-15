import $ from 'jquery';
import { dtLangEs } from './datatables-lang';

export function initCategoriesTable(apiUrl) {
    const table = $('#tabla-categorias');

    if (!table.length) return;

    table.DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        language: dtLangEs,
        ajax: {
            url: apiUrl,
            type: 'GET',
            data: function (d) {
                d.type = $('#filter-type').val();
            },
            error: function (xhr) {
                console.error('Error DataTables categories:', xhr.status, xhr.responseText);
            },
        },
        columns: [
            {
                data: 'name',
                className: 'align-middle',
            },
            {
                data: 'type',
                className: 'align-middle text-center',
                render: function (data, type, row) {
                    const color = row.type_raw === 'income' ? 'success' : 'danger';
                    return `<span class="badge badge-${color}">${data}</span>`;
                },
            },
            {
                data: 'subcategories',
                className: 'align-middle text-muted',
            },
            {
                data: 'actions',
                orderable: false,
                searchable: false,
                className: 'align-middle text-center',
            },
        ],
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 100, targets: 3 },
        ],
        order: [[0, 'asc']],
    });

    $('#filter-type').on('change', function () {
        table.DataTable().ajax.reload();
    });
}