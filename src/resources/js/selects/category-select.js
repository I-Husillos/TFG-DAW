import $ from 'jquery';

/**
 * Inicializa Select2 en todos los selectores
 * que tengan el atributo [data-category-select].
 *
 * Se llama una vez que el DOM está listo.
 */
export function initCategorySelect() {
    // Seleccionamos todos los <select> marcados con
    // el atributo personalizado data-category-select.
    // Esto permite reutilizar la función en cualquier
    // vista sin acoplar el código al nombre del campo.
    const selectors = $('[data-category-select]');

    if (!selectors.length) return; // No hay selectores en esta página, salir.

    // Si Select2 aún no está cargado (puede ocurrir
    // si el bundle no lo incluye), lo cargamos
    // dinámicamente desde CDN, igual que hace el
    // módulo de tags del proyecto de tickets.
    if (typeof $.fn.select2 === 'function') {
        _apply(selectors);
    } else {
        // Exponemos jQuery globalmente porque Select2
        // (formato UMD) lo busca en window.
        window.jQuery = window.jQuery || $;
        window.$      = window.$      || $;

        const linkTheme = document.createElement('link');
        linkTheme.rel = 'stylesheet';
        linkTheme.href = 'https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css';
        document.head.appendChild(linkTheme);

        const script  = document.createElement('script');
        script.src    = 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js';
        script.onload = () => _apply(selectors);
        document.head.appendChild(script);

        // Cargamos también el CSS de Select2 si no está
        const link   = document.createElement('link');
        link.rel     = 'stylesheet';
        link.href    = 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css';
        document.head.appendChild(link);
    }
}

/**
 * Aplica Select2 a los selectores recibidos.
 *
 * @param {jQuery} selectors - Elementos <select> a transformar
 * @private
 */
function _apply(selectors) {
    selectors.each(function () {
        const placeholder = $(this).data('placeholder') || 'Buscar o seleccionar categoría...';
        const allowClear  = $(this).data('allow-clear') !== false; // true por defecto

        $(this).select2({
            theme: 'bootstrap4',
            // Texto del campo cuando no hay nada seleccionado.
            // Se toma del atributo data-placeholder del <select>
            // o del valor por defecto definido arriba.
            placeholder: placeholder,

            width: '100%',

            // Muestra una "x" para limpiar la selección.
            allowClear: allowClear,

            // Texto mostrado cuando la búsqueda no da resultados.
            language: {
                noResults: () => 'No se encontraron categorías',
                searching: () => 'Buscando...',
            },

            // Select2 necesita encontrar la caja de diálogo
            // dentro del mismo contenedor del formulario
            // (importante cuando hay modales Bootstrap).
            // Si el selector está en un modal, usa el body.
            dropdownParent: $(this).closest('.modal').length
                ? $(this).closest('.modal')
                : $(document.body),
        });
    });
}