export function dashboard() {

    $("#user-menu-button").on("click", function () {
        if ($("#user-menu-button").attr('aria-expanded') == "false") {
            $('#user-menu').removeClass('hidden');
            setTimeout(function () {
                $('#user-menu').addClass('user-menu-expanded').removeClass('user-menu-closed');
                $("#user-menu-button").attr('aria-expanded', "true");
            }, 100);
        } else {
            $('#user-menu').addClass('user-menu-closed').removeClass('user-menu-expanded');
            $("#user-menu-button").attr('aria-expanded', "false");
            setTimeout(function () {
                $('#user-menu').addClass('hidden');
            }, 75);
        }
    });

    $('#open-menu').on("click", function () {
        $("#mobile-nav").removeClass('-z-10').addClass('z-50');
        $('#curtain').addClass('curtain-expanded').removeClass('curtain-closed');
        $('#side-panel').addClass('side-panel-expanded').removeClass('side-panel-closed');
        $('#close-button').addClass('close-button-expanded').removeClass('close-button-closed');
    });

    $('#close-menu').on("click", function () {
        $('#curtain').addClass('curtain-closed').removeClass('curtain-expanded');
        $('#side-panel').addClass('side-panel-closed').removeClass('side-panel-expanded');
        $('#close-button').addClass('close-button-closed').removeClass('close-button-expanded');
        $("#mobile-nav").removeClass('z-50').addClass('-z-10');
    });

};