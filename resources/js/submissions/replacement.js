export function replacement() {
    // replacement modal
    $('#replace-modal-cancel-button').on("click", function () {
        $("#replacement-popup").removeClass('modal-opened').addClass('modal-closed');
        $("#replacement-modal").removeClass('flex').addClass('hidden');
        $("#curtain-replacement").removeClass('curtain-expanded').addClass('curtain-closed');
    });

    // when select all checkbox is checked
    $('#select-all').on("click", function () {
        let checked = this.checked;
        $('.part-checkbox').each(function () {
            this.checked = checked;
        });
    });

    // when proceed button is clicked
    $('#replace-modal-proceed-button').on("click", function () {
        $('#replace-form').trigger("submit");
    });
}