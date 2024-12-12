export function roleForm() {
    /**
     * change from standard to customer role
     */
    $("#customer-role").on("click", function () {
        if ($(this).prop("checked")) {
            /**
             * hide standard role and show customer role
             */
            $("#standard-form").hide();
            $("#customer-form").show();
        } else {
            /**
             * hide customer role and show standard role
             */
            $("#customer-form").hide();
            $("#standard-form").show();
        }
    });
}
