export function designerFormSubmission() {
    $("#submit-button").on("click", function () {

        $('#submit-button').attr('class', 'btn-ticked');
        $('#submit-button').val('Submitting');
        $('#submit-button img').attr('class', 'aspect-square w-7');
        $('#submit-button img').attr('src', dots_loading);

        let valid = true;

        let fieldIds = [
            'assembly_name',
            'machine_number',
            'submission_type',
            'current_unit_number',
        ];

        for (let fieldId of fieldIds) {
            if (!$('#'+fieldId).val()) {
                if (
                    fieldId == "current_unit_number"
                    && $('#submission_type').val() == "additional_project"
                ) {
                    $('#current_unit_number').addClass('field-normal');
                    $('#current_unit_number').removeClass('field-error');
                    continue;
                }
                $('#'+fieldId).addClass('field-error');
                $('#'+fieldId).removeClass('field-normal');
                $('#error-message').html("Please fill in required fields");
                $('#error-message').parent().parent().removeClass('hidden');
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
                valid = false;
            } else {
                $('#'+fieldId).addClass('field-normal');
                $('#'+fieldId).removeClass('field-error');
            }
        }

        if (valid) {
            $('#submission-form').trigger("submit");
        } else {
            $('#submit-button').attr('class', 'btn-ticked');
            $('#submit-button').val('Submitting');
            $('#submit-button img').attr('class', 'aspect-square w-7');
            $('#submit-button img').attr('src', green_tick);
        }

    });
}