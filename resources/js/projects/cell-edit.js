export function cellEditProject() {
    $(".cell-text").on("focusout", function () {
        let value = $(this).val();
        let field = $(this).attr('name');
        let id    = $(this).attr('item-id');

        updateField(id, field, value);
    });

    $(".cell-dropdown").on("change", function () {
        let value = $(this).val();
        let field = $(this).attr('name');
        let id    = $(this).attr('item-id');

        updateField(id, field, value);
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
}