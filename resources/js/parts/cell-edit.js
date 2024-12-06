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

    $('.cell-date').on("change", function () {
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
                        if (field == 'due_date') {
                            $("td[item-id='"+id+"'][item-key='due_days']").html(data.due_days);
                        }
                    }
                });
            }, 200);
        }

        if ($(this).attr('model') == 'App\\Models\\Order') {
            let value = $(this).val();
            let field = $(this).attr('name');
            let id    = $(this).attr('item-id');
    
            setTimeout(function () {
                $.ajax({ // route('orders.update-date', $order->id)
                    type: 'POST',
                    url: '/orders/update-date/' + id,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: id,
                        field: field,
                        value: value
                    },
                    success: function (data) {
                        console.log(data);
                        $('#' + data.id + '-due_days').html('Due in (days): ' + data.days);
                        $('#' + data.id + '-due_date').html('Due date: ' + data.date);
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

        if ($(this).attr('model') == 'App\\Models\\Supplier') {
            updateSupplierCheckbox(id, field, value);
        } else {
            updatePartCheckbox(id, field, value);
        }
        
    });

    function updateSupplierCheckbox(id, field, value)
    {
        setTimeout(function () {
            $.ajax({ // route('suppliers.update-checkbox', $part->id)
                type: 'POST',
                url: '/suppliers/update-checkbox/' + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    field: field,
                    value: value
                }
            });
        }, 200);
    }

    function updatePartCheckbox(id, field, value)
    {
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
                    // set status
                    $("td[item-id='"+data.part_id+"'][item-key='status']").html(data['status']);
                    
                    // set qc_issue_logged_at
                    $("td[item-id='"+data.part_id+"'][item-key='qc_issue_at']").html(data['qc_issue_logged_at']);

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
    }

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