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

    // add filter pill
    $('.add-filter-pill').on('click', function () {
        $.ajax({ // route('pill-html')
            type: 'GET',
            url: '/pill-html',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                model: $(this).attr('model'),
                field: $(this).attr('field'),
            },
            success: function (data) {
                console.log(data);
                if (data != 'error') {
                    $('#filter-options-toggle').before(data['html']);
                    $('#filter-options').addClass('hidden').attr('aria-hidden', true);
                    console.log(data['field']);
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

}
