export function roleTable() {

    /**
     * when the select all checkbox is checked/unchecked
     */
    $("#select-all").on("click", function() {
        if ($(this).prop("checked")) {
            $("input[type='checkbox']").prop("checked", true);
        } else {
            $("input[type='checkbox']").prop("checked", false);
        }
    });

    /**
     * when any checkbox is unchecked, uncheck the select all checkbox
     */
    $("input[type='checkbox']").on("click", function() {
        if (!$(this).prop("checked")) {
            $("#select-all").prop("checked", false);
        }
    });

    /**
     * if checkbox with id "select-all-" is checked/unchecked
     */
    $("input[id^='select-all-']").on("click", function() {
        let role = $(this).attr("id").split("-")[2];
        if ($(this).prop("checked")) {
            $("#create-" + role).prop("checked", true);
            $("#read-" + role).prop("checked", true);
            $("#update-" + role).prop("checked", true);
            $("#delete-" + role).prop("checked", true);
            $("#restore-" + role).prop("checked", true);
            $("#force_delete-" + role).prop("checked", true);
        } else {
            $("#create-" + role).prop("checked", false);
            $("#read-" + role).prop("checked", false);
            $("#update-" + role).prop("checked", false);
            $("#delete-" + role).prop("checked", false);
            $("#restore-" + role).prop("checked", false);
            $("#force_delete-" + role).prop("checked", false);
        }
    });

    /**
     * if any checkbox is unchecked
     */
    $("input[id^='create-'], input[id^='read-'], input[id^='update-'], input[id^='delete-'], input[id^='restore-'], input[id^='force_delete-']").on("click", function() {
        let role = $(this).attr("id").split("-")[1];
        if (!$(this).prop("checked")) {
            $("#select-all-" + role).prop("checked", false);
        }
    });

    /**
     * if a checkbox is checked, see if the entire row is checked
     */
    $("input[id^='create-'], input[id^='read-'], input[id^='update-'], input[id^='delete-'], input[id^='restore-'], input[id^='force_delete-']").on("click", function() {
        let role = $(this).attr("id").split("-")[1];
        if ($("#create-" + role).prop("checked") && $("#read-" + role).prop("checked") && $("#update-" + role).prop("checked") && $("#delete-" + role).prop("checked") && $("#restore-" + role).prop("checked") && $("#force_delete-" + role).prop("checked")) {
            $("#select-all-" + role).prop("checked", true);
        }
    });

    /**
     * if any checkbox is checked, check to see if all checkboxes are checked
     */
    $('input[type="checkbox"]').on("click", function() {
        let allChecked = true;
        $("input[type='checkbox']").each(function() {
            if (!$(this).prop("checked") && $(this).attr("id") !== "select-all") {
                allChecked = false;
            }
        });
        if (allChecked) {
            $("#select-all").prop("checked", true);
        }
    });

}