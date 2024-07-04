export function cellEdit() {
    // Editable text cell
    $('.cell-text').on("focusout", function () {
        if ($(this).attr('model') == 'App\\Models\\Part') {
            let value = $(this).val();
            let field = $(this).attr('name');
            let id    = $(this).attr('item-id');
    
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
                            $("input[item-id='" + id + "'][name='quantity_in_stock']").val(data.quantity_in_stock);
                            $("input[item-id='" + id + "'][name='quantity_ordered']").val(data.quantity_ordered);
                            $("input[item-id='" + id + "'][name='quantity']").val(data.quantity);
                        }
                    }
                });
            }, 200);
        }
    });

    // Editable checkbox cell
    $(".editable-cell-boolean").on("click", function () {
        let value = $(this).is(':checked') ? 1 : 0;
        let field = $(this).attr('name');
        let id    = $(this).attr('item-id');

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

                    // set quantity received
                    $('input[item-id="' + data['part_id'] + '"][name="qty_received"]').val(data['qty_received']);
                }
            });
        }, 200);
    });

    // Editable dropdown cell
    $(".cell-dropdown").on("change", function () {
        if ($(this).attr('model') == 'App\\Models\\Part') {
            let value = $(this).val();
            let field = $(this).attr('name');
            let id    = $(this).attr('item-id');
    
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
                            field == 'treatment_1' ||
                            field == 'treatment_2'
                        ) {
                            let inputName = field == 'treatment_1' ? 'treatment_1_part_received' : 'treatment_2_part_received';
                            let input = $('input[name="' + inputName + '"][item-id="' + id + '"]');
                            if (value == '') {
                                input.attr('disabled', true);
                            } else {
                                input.removeAttr('disabled');
                            }
                        }
                        if (field == 'supplier->name') {
                            if (value == 0) {
                                $('#' + id + '-po_number input').addClass('cursor-not-allowed').attr('disabled', true);
                            } else {
                                $('#' + id + '-po_number input').removeClass('cursor-not-allowed').removeAttr('disabled');
                            }
                        }
                    }
                });
            }, 200);
        }
    });
}