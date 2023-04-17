export function dismiss() {
    $("#dimiss-button").on("click", function () {
        let msg = $(this).parent().parent();
        msg.fadeOut(700);

        setTimeout(function () {
            msg.css('display', '');
            msg.addClass('hidden');
        }, 800);
    });
}