(function($){

    'use strict';

    function burgerTablaDatosBool(value){
        return value === 1 || value === '1' || value === true || value === 'true';
    }

    function burgerTablaDatosFallback($table, options){

        var $wrapper = $table.closest('.tabla-datos-wrapper');
        var $allRows = $table.find('tbody tr');
        var filteredRows = $allRows.toArray();

        var pageLength = parseInt($table.attr('data-page-length'), 10) || 10;
        var currentPage = 1;

        var paging = $table.attr('data-paging') === '1';
        var searching = $table.attr('data-searching') === '1';
        var info = $table.attr('data-info') === '1';

        var searchLabel = $table.attr('data-search-label') || 'Buscar:';
        var emptyText = $table.attr('data-empty-text') || 'No se encontraron resultados.';

        var $controls = $('<div class="burger-table-fallback-controls d-flex justify-content-between align-items-center gap-3 mb-3 flex-wrap"></div>');
        var $length = $('<div><label>Mostrar <select class="form-select form-select-sm d-inline-block w-auto mx-1"><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option></select> registros</label></div>');
        var $search = $('<div><label>' + searchLabel + ' <input type="search" class="form-control form-control-sm d-inline-block w-auto ms-2"></label></div>');

        var $footer = $('<div class="burger-table-fallback-footer d-flex justify-content-between align-items-center gap-3 mt-3 flex-wrap"></div>');
        var $info = $('<div class="burger-table-fallback-info"></div>');
        var $pagination = $('<div class="burger-table-fallback-pagination"></div>');
        var $empty = $('<div class="burger-table-empty text-center py-3" style="display:none;">' + emptyText + '</div>');

        $length.find('select').val(String(pageLength));

        if (paging) $controls.append($length);
        if (searching) $controls.append($search);
        if (paging || searching) $wrapper.before($controls);

        $wrapper.after($empty);

        if (info) $footer.append($info);
        if (paging) $footer.append($pagination);
        if (info || paging) $empty.after($footer);

        function render(){

            var total = filteredRows.length;
            var totalPages = paging ? Math.ceil(total / pageLength) : 1;

            if (totalPages < 1) totalPages = 1;
            if (currentPage > totalPages) currentPage = totalPages;

            var start = paging ? (currentPage - 1) * pageLength : 0;
            var end = paging ? start + pageLength : total;

            $allRows.hide();

            $(filteredRows).each(function(index){
                if (!paging || (index >= start && index < end)) {
                    $(this).show();
                }
            });

            $empty.toggle(total === 0);

            if (info) {
                if (total === 0) {
                    $info.text('Mostrando 0 a 0 de 0 registros');
                } else {
                    $info.text('Mostrando ' + (start + 1) + ' a ' + Math.min(end, total) + ' de ' + total + ' registros');
                }
            }

            if (paging) {
                $pagination.empty();

                var $prev = $('<button type="button" class="btn btn-sm btn-outline-white me-1">&lt;</button>');
                $prev.prop('disabled', currentPage === 1);
                $prev.on('click', function(){
                    if (currentPage > 1) {
                        currentPage--;
                        render();
                    }
                });

                var $next = $('<button type="button" class="btn btn-sm btn-outline-white ms-1">&gt;</button>');
                $next.prop('disabled', currentPage === totalPages);
                $next.on('click', function(){
                    if (currentPage < totalPages) {
                        currentPage++;
                        render();
                    }
                });

                $pagination.append($prev);

                for (var i = 1; i <= totalPages; i++) {
                    var $btn = $('<button type="button" class="btn btn-sm me-1"></button>');
                    $btn.text(i);
                    $btn.addClass(i === currentPage ? 'btn-white' : 'btn-outline-white');

                    (function(page){
                        $btn.on('click', function(){
                            currentPage = page;
                            render();
                        });
                    })(i);

                    $pagination.append($btn);
                }

                $pagination.append($next);
            }

            burgerTablaDatosRefreshLayout();
        }

        $search.find('input').on('input search keyup', function(){
            var value = $(this).val().toLowerCase();

            filteredRows = $allRows.filter(function(){
                return $(this).text().toLowerCase().indexOf(value) !== -1;
            }).toArray();

            currentPage = 1;
            render();
        });

        $length.find('select').on('change', function(){
            pageLength = parseInt($(this).val(), 10) || 10;
            currentPage = 1;
            render();
        });

        render();
    }

    function burgerInitTablaDatos(context){
        var $context = context ? $(context) : $(document);

        $context.find('table.burger-datatable').each(function(){
            var $table = $(this);

            if ($table.data('burgerTablaDatosInit')) return;
            $table.data('burgerTablaDatosInit', true);

            var orderColumn = parseInt($table.data('order-column'), 10);
            var orderDirection = $table.data('order-direction') || 'asc';
            var columnDefs = [];

            try {
                columnDefs = JSON.parse($table.attr('data-column-defs') || '[]');
            } catch(e) {
                columnDefs = [];
            }

            var options = {
                responsive: true,
                searching: burgerTablaDatosBool($table.data('searching')),
                paging: burgerTablaDatosBool($table.data('paging')),
                info: burgerTablaDatosBool($table.data('info')),
                pageLength: parseInt($table.data('page-length'), 10) || 10,
                lengthChange: true,
                ordering: true,
                dom: 'lfrtip',
                columnDefs: columnDefs,
                language: {
                    search: $table.data('search-label') || 'Buscar:',
                    emptyTable: $table.data('empty-text') || 'No se encontraron resultados.',
                    zeroRecords: $table.data('empty-text') || 'No se encontraron resultados.',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                    infoFiltered: '(filtrado de _MAX_ registros)',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: '>',
                        previous: '<'
                    }
                }
            };

            if (orderColumn >= 0) {
                options.order = [[orderColumn, orderDirection]];
            } else {
                options.order = [];
            }

            if ($.fn.DataTable) {

                if (
                    typeof $.fn.DataTable.isDataTable === 'function' &&
                    $.fn.DataTable.isDataTable($table[0])
                ) {
                    $table.DataTable().destroy();
                }

                $table.DataTable(options);

                $table.on('draw.dt responsive-display.dt', function () {
                    burgerTablaDatosRefreshLayout();
                });

                burgerTablaDatosRefreshLayout();

            } else {

                burgerTablaDatosFallback($table, options);
                burgerTablaDatosRefreshLayout();
            }

        });

    }

    var burgerTablaDatosRefreshTimer;

    function burgerTablaDatosRefreshLayout() {

        clearTimeout(burgerTablaDatosRefreshTimer);

        burgerTablaDatosRefreshTimer = setTimeout(function () {

            /*
            * No usar refreshHard().
            * refreshHard vuelve a registrar todos los elementos AOS
            * y puede interferir con Owl Carousel.
            */
            if (window.AOS && typeof window.AOS.refresh === 'function') {
                window.AOS.refresh();
            }

            /*
            * Obliga a los Owl existentes a recalcular medidas.
            */
            $('.owl-carousel').each(function () {

                var $owl = $(this);

                if ($owl.hasClass('owl-loaded')) {
                    $owl.trigger('refresh.owl.carousel');
                }

            });

            /*
            * Algunos componentes recalculan dimensiones con resize.
            */
            $(window).trigger('resize');

        }, 250);
    }

    $(document).ready(function(){
        burgerInitTablaDatos(document);
    });

    $(document).on('burger/blocks-loaded', function(e, context){
        burgerInitTablaDatos(context || document);
    });

})(jQuery);
