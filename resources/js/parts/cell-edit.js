export function cellEdit() {
    // Editable text cell
    $(".editable-cell-text").on("focusout", function () {
        let value = $(this).val();
        let field = $(this).attr('name');
        let id    = $(this).attr('part-id');

        setTimeout(function () {
            $.ajax({ // route('parts.update', $part->id)
                type: 'POST',
                url: '/parts/update/' + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    field: field,
                    value: value
                },
                success: function (data) {
                    if (data.qty_updated) {
                        $("#" + id + "-quantity input").val(data.quantity);
                        $("#" + id + "-quantity_in_stock input").val(data.quantity_in_stock);
                        $("#" + id + "-quantity_ordered input").val(data.quantity_ordered);
                    }
                }
            });
        }, 200);
    });

    // Editable checkbox cell
    $(".editable-cell-boolean").on("click", function () {
        let value = $(this).is(':checked') ? 1 : 0;
        let field = $(this).attr('name');
        let id    = $(this).attr('part-id');

        setTimeout(function () {
            $.ajax({ // route('parts.update-checkbox', $part->id)
                type: 'POST',
                url: '/parts/update-checkbox/' + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    field: field,
                    value: value
                },
                success: function (data) {
                    $("#" + data['part_id'] + "-status").html(data['status']);

                    Object.entries(data['checkboxes']).forEach(checkbox => {
                        const [field, checkboxValues] = checkbox;

                        // set checkbox value
                        if (checkboxValues['checked'] == 1) {
                            $("#" + data['part_id'] + "-" + field + " input").prop('checked', true);
                        } else {
                            $("#" + data['part_id'] + "-" + field + " input").prop('checked', false);
                        }

                        // set checkbox disabled/enabled
                        if (checkboxValues['enabled']) {
                            $("#" + data['part_id'] + "-" + field + " input").removeAttr('disabled');
                        } else {
                            $("#" + data['part_id'] + "-" + field + " input").attr('disabled', true);
                        }

                        // set stamp value
                        $("#" + data['part_id'] + "-" + field + "_at").html(checkboxValues['at']);
                    });
                }
            });
        }, 200);
    });

    // Editable dropdown cell
    $(".editable-cell-dropdown").on("change", function () {
        let value = $(this).val();
        let field = $(this).attr('name');
        let id    = $(this).attr('part-id');

        setTimeout(function () {
            $.ajax({ // route('parts.update', $part->id)
                type: 'POST',
                url: '/parts/update/' + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    field: field,
                    value: value
                },
                success: function (data) {
                    if (
                        field == 'treatment_1' &&
                        value != '-' &&
                        $('#' + id + '-status').text().includes('Treatment')
                    ) {
                        $($('input[name="treatment_1_part_received"][part-id="' + id + '"]')).removeAttr('disabled');
                    }
                    if (
                        field == 'treatment_2' &&
                        value != '-' &&
                        $('#' + id + '-status').text() == 'Treatment'
                    ) {
                        $($('input[name="treatment_2_part_received"][part-id="' + id + '"]')).removeAttr('disabled');
                    }
                }
            });
        }, 200);
    });
}