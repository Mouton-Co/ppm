export function designerFormSubmission() {
    $("#submit-button").on("click", function () {

        $('#submit-button').html($('#submit-button').html().replace('Submit', 'Submitting'));
        $('#dots').removeClass('hidden');

        let valid = true;

        let fieldIds = [
            'assembly_name',
            'machine_number',
            'submission_type',
            'current_unit_number',
        ];

        for (let fieldId of fieldIds) {
            if (!$('#' + fieldId).val()) {
                if (
                    fieldId == "current_unit_number"
                    && $('#submission_type').val() == "additional_project"
                ) {
                    $('#current_unit_number').addClass('field-dark');
                    $('#current_unit_number').removeClass('field-error');
                    continue;
                }
                $('#' + fieldId).addClass('field-error');
                $('#' + fieldId).removeClass('field-dark');
                $('#error-message').html("Please fill in required fields");
                $('#error-message').parent().parent().parent().removeClass('hidden');
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
                valid = false;
            } else {
                $('#' + fieldId).addClass('field-dark');
                $('#' + fieldId).removeClass('field-error');
            }
        }

        if (valid) {
            $('#submission-form').trigger("submit");
        } else {
            $('#submit-button').html($('#submit-button').html().replace('Submitting', 'Submit'));
            $('#dots').addClass('hidden');
        }

    });

    $("#machine_number, #current_unit_number").on("input", function () {
        let machine_number = $('#machine_number').val();
        let current_unit_number = $('#current_unit_number').val();
        
        $.ajax({
            url: '/submission/replacement-options',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                machine_number: machine_number,
                current_unit_number: current_unit_number,
            },
            success: function (response) {
                $("#replacement").html("<option value=''>--Please select--</option>");
                $.each(response, function(i, item) {
                    $("#replacement").append(
                        $("<option></option>")
                            .attr("value", i)
                            .text(item)
                    );
                });
            }
        });
    });
}
