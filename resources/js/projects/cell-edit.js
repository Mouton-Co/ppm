export function cellEditProject() {
    $(".cell-text").on("focusout", function () {
        if ($(this).attr('model') == 'App\\Models\\Project') {
            let value = $(this).val();
            let field = $(this).attr('name');
            let id    = $(this).attr('item-id');
    
            updateField(id, field, value);
        }
    });

    $(".cell-dropdown").on("change", function () {
        if ($(this).attr('model') == 'App\\Models\\Project') {
            let value = $(this).val();
            let field = $(this).attr('name');
            let id    = $(this).attr('item-id');
    
            updateField(id, field, value);
        }
    });

    function updateField(id, field, value) {
        setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: 'project/update/ajax/' + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    field: field,
                    value: value
                },
                success: function (data) {
                    $('#' + data.update.id + '-status select').val(data.update.status);
                    $('#' + data.update.id + '-resolved_at').text(data.update.resolved_at == null ? '-' : data.update.resolved_at);
                }
            });
        }, 200);
    }

    // change status dropdown colour
    $(".project-status-dropdown[model='\App\Models\Project']").on("change", function () {
        let status = $(this).val();

        // remove all classes
        $(this).removeClass('!bg-green-300 !text-green-800')
            .removeClass('!bg-cyan-300 !text-cyan-800')
            .removeClass('!bg-zinc-300 !text-zinc-800')
            .removeClass('!bg-orange-300 !text-orange-800')
            .removeClass('!bg-transparent !text-gray-400');

        // add class based on status
        if (status.toLowerCase() == 'closed') {
            $(this).addClass('!bg-green-300 !text-green-800');
        } else if (status.toLowerCase() == 'waiting for customer') {
            $(this).addClass('!bg-cyan-300 !text-cyan-800');
        } else if (status.toLowerCase() == 'prepare') {
            $(this).addClass('!bg-zinc-300 !text-zinc-800');
        } else if (status.toLowerCase() == 'work in progress') {
            $(this).addClass('!bg-orange-300 !text-orange-800');
        } else {
            $(this).addClass('!bg-transparent !text-gray-400');
        }

    });
}