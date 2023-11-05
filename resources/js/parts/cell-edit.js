export function cellEdit() {
    $('.cell-edit').on("click", function () {
        // hide all other cell-edit forms
        $('.cell-form').removeClass('flex').addClass('hidden');

        // show the form for the cell that was clicked
        let id = this.getAttribute('id').split('-')[1];
        $("#cell-form-" + id).removeClass('hidden').addClass('flex');

        // show the cell form curtain
        $("#cell-curtain").removeClass('hidden').addClass('flex');

        // focus on the input
        $("#cell-form-" + id + " form input").focus();
    });

    $('#cell-curtain').on("click", function () {
        // hide all cell-edit forms
        $('.cell-form').removeClass('flex').addClass('hidden');

        // hide the cell form curtain
        $("#cell-curtain").removeClass('flex').addClass('hidden');
    });

    $('.cell-form-input').keypress(function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which;
        if (keyCode == 13) {
            $(this).parent().submit();
        }
    });
}