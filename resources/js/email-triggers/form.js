export function emailTriggerForm() {

    // when triggered when dropdown value changes we must filter the changed to dropdown options
    $("#select-triggers-when").on("change", function () {
        // enable changed to dropdown
        $("#select-changed-to").attr("disabled", false);

        // filter select options
        if ($(this).val() == 'Currently responsible') {
            $('.department-item').removeClass('hidden');
            $('.status-item').addClass('hidden');
            $('.created-item').addClass('hidden');
        } else if ($(this).val() == 'Status') {
            $('.status-item').removeClass('hidden');
            $('.department-item').addClass('hidden');
            $('.created-item').addClass('hidden');
        } else if ($(this).val() == 'Item created') {
            $('.created-item').removeClass('hidden');
            $('.department-item').addClass('hidden');
            $('.status-item').addClass('hidden');
        }
    });
    
}