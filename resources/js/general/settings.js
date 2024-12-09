export function settings() {
    $("#supplier-emails-checkbox").on("change", function () {
        $.ajax({
            url: "/settings/update-ajax",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                key: "supplier_emails",
                value: $(this).is(":checked") ? "true" : "false",
            },
        });
    });
}
