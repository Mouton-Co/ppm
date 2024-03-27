export function cocGenerate() {
    $('#coc-generate').on('click', function() {
        let machine_nr = $('#machine_nr').val();

        if (machine_nr != '') {
            $.ajax({ // route('projects.coc', $machine_nr)
                type: 'GET',
                url: '/project/coc/' + machine_nr,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.coc) {
                        $('#coc').val(data.coc);
                    }
                }
            });
        }

    });
}