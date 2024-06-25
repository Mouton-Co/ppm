export function filters() {

    // remove filter pill from search bar
    $(document).on('click', '.close-filter-pill', function () {
        let pill = $(this).parent();
        pill.fadeOut(300);

        setTimeout(function () {
            $('.filter-option').each(function () {
                if ($(this).attr('field') == pill.attr('field')) {
                    $(this).removeClass('hidden');
                }
            });
            pill.remove();
        }, 300);
    });

    // toggle filter options
    $('#filter-options-toggle').on('click', function () {
        if ($('#filter-options').attr('aria-hidden') == 'true') {
            $('#filter-options').removeClass('hidden').attr('aria-hidden', false);
        } else {
            $('#filter-options').addClass('hidden').attr('aria-hidden', true);
        }
    });
    $(document).on('click', function (e) {
        if (
            ! $(e.target).closest('#filter-options').length &&
            ! $(e.target).closest('#filter-options-toggle').length
        ) {
            $('#filter-options').addClass('hidden').attr('aria-hidden', true);
        }
    });

    // toggle column configurations
    $('#column-config-toggle').on('click', function () {
        if ($('#column-config').attr('aria-hidden') == 'true') {
            $('#column-config').removeClass('hidden').attr('aria-hidden', false);
        } else {
            $('#column-config').addClass('hidden').attr('aria-hidden', true);
        }
    });
    $(document).on('click', function (e) {
        if (
            ! $(e.target).closest('#column-config').length &&
            ! $(e.target).closest('#column-config-toggle').length
        ) {
            $('#column-config').addClass('hidden').attr('aria-hidden', true);
        }
    });

    // add filter pill
    $('.add-filter-pill').on('click', function () {
        $.ajax({ // route('pill-html')
            type: 'GET',
            url: '/pill-html',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                model: $(this).attr('model'),
                field: $(this).attr('field'),
                structure: $(this).attr('structure'),
            },
            success: function (data) {
                if (data != 'error') {
                    $('#filter-options-toggle').before(data['html']);
                    $('#filter-options').addClass('hidden').attr('aria-hidden', true);
                    $('.filter-option').each(function () {
                        if ($(this).attr('field') == data['field']) {
                            $(this).addClass('hidden');
                        }
                    });
                }
            }
        });
    });

    // submit filter form
    $('#filter').on('click', function () {
        $('#form').trigger('submit');
    });
    
    $(document).on("keypress", ".submit-on-enter" , function(e) {
        if (e.which == 13) {
            $('#form').trigger('submit');
        }
    });

    // sortable list of items
    jQuery('.sortable-list').sortable({
        group: 'list',
        animation: 200,
        ghostClass: 'ghost',
        onSort: itemSorted,
    });

    function itemSorted()
    {
        let table = $('#column-config').attr('table');

        let columns = [];
        $('.sortable-list div').each(function () {
            columns.push($(this).attr('key'));
        });

        $.ajax({ // route('update-configs')
            type: 'POST',
            url: '/update-configs',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                table: table,
                columns: columns,
            }
        });
    }

}
