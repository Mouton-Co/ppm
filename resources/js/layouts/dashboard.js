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

    $('div[id^="part-name-"]').on("click", function () {
        let id = this.getAttribute('id').split('-')[2];
        if ($("#part-info-"+id).attr('aria-expanded') == "false") {
            $("#part-info-"+id).removeClass('hidden');
            setTimeout(function () {
                $("#part-info-"+id).addClass('parts-info-expanded').removeClass('parts-info-closed');
                $("#part-name-"+id).addClass('bg-sky-700');
                $("#part-name-"+id).children().addClass('!text-white');
                $("#part-info-"+id).attr('aria-expanded', "true");
                $("#arrow-right-"+id).addClass('hidden');
                $("#arrow-up-"+id).removeClass('hidden');
            }, 100);
        } else {
            $("#part-info-"+id).addClass('parts-info-closed').removeClass('parts-info-expanded');
            $("#part-info-"+id).attr('aria-expanded', "false");
            $("#part-name-"+id).removeClass('bg-sky-700');
            $("#part-name-"+id).children().removeClass('!text-white');
            $("#arrow-up-"+id).addClass('hidden');
            $("#arrow-right-"+id).removeClass('hidden');
            setTimeout(function () {
                $("#part-info-"+id).addClass('hidden');
            }, 75);
        }
    });

    $('svg[id^="delete-button-"], div[id^="delete-button-"]').on("click", function () {
        console.log('pressed');
        let id = this.getAttribute('id').split('-')[2];
        $("#delete-modal-"+id).removeClass('hidden');
        setTimeout(function () {
            $("#delete-modal-curtain-"+id).removeClass('curtain-closed');
            $("#delete-modal-curtain-"+id).addClass('curtain-expanded');
            $("#delete-modal-popup-"+id).removeClass('modal-close');
            $("#delete-modal-popup-"+id).addClass('modal-popup');
        }, 300);
    });
    $('button[id^="delete-modal-cancel-"]').on("click", function () {
        let id = this.getAttribute('id').split('-')[3];
        $("#delete-modal-curtain-"+id).removeClass('curtain-expanded');
        $("#delete-modal-curtain-"+id).addClass('curtain-closed');
        $("#delete-modal-popup-"+id).removeClass('modal-popup');
        $("#delete-modal-popup-"+id).addClass('modal-close');
        setTimeout(function () {
            $("#delete-modal-"+id).addClass('hidden');
        }, 200);
    });

};