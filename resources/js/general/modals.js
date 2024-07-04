export function modals() {
    // purchase order modal
    $('div[id^="order-card-"]').on("click", function () {
        let id = $(this).attr('id').split('-')[2];
        $('#order-modal-' + id).removeClass('hidden').addClass('flex');
        $('#curtain').addClass('curtain-expanded').removeClass('curtain-closed');
    });

    $('#curtain, #generic-delete-modal-cancel').on("click", function () {
        $('.purchase-order-modal').removeClass('flex').addClass('hidden');
        $('#curtain').addClass('curtain-closed').removeClass('curtain-expanded');
        $('#generic-delete-modal').removeClass('flex').addClass('hidden');
        $('#generic-delete-modal-popup').removeClass('modal-popup').addClass('modal-close');
    });

    $('.delete-po').on("click", function () {
        $('.purchase-order-modal').removeClass('flex').addClass('hidden');
        $('#generic-delete-modal').removeClass('hidden').addClass('flex');
        $('#generic-delete-modal-popup').removeClass('modal-close').addClass('modal-popup');
        $('#curtain').addClass('curtain-expanded').removeClass('curtain-closed');
        $('#heading').html("Are you sure?");
        $('#message').html("Are you sure you want to delete this purchase order?");
        $('#generic-delete-modal-form').attr('action', $(this).attr('route'));
    });

    // tooltip trigger
    $('.tooltip-trigger').hover(function () {
        let id = $(this).attr('tooltip-id');
        $('#' + id).removeClass('hidden').addClass('fixed');
    }, function () {
        let id = $(this).attr('tooltip-id');
        $('#' + id).addClass('hidden').removeClass('fixed');
    });

    // delete modal
    $('*[id^="delete-button-"]').on("click", function () {
        let id = this.getAttribute('id').split('-')[2];
        $("#delete-modal-"+id).removeClass('hidden');
        setTimeout(function () {
            $("#curtain").addClass('curtain-expanded').removeClass('curtain-closed');
            $("#delete-modal-popup-"+id).removeClass('modal-close').addClass('modal-popup');
        }, 300);
    });
    $('#curtain, button[id^="delete-modal-cancel-"]').on("click", function () {
        $("#curtain").addClass('curtain-closed').removeClass('curtain-expanded');
        $("*[id^='delete-modal-popup-']")
            .removeClass('modal-popup')
            .removeClass('modal-close')
            .addClass('modal-close');
        setTimeout(function () {
            $("*[id^='delete-modal-']")
                .removeClass('hidden')
                .addClass('hidden');
            $("*[id^='delete-modal-popup-']")
                .removeClass('hidden');
        }, 200);
    });

    // unlink modal
    $('*[id^="unlink-button-"]').on("click", function () {
        let id = this.getAttribute('id').split('-')[2];
        $("#unlink-modal-"+id).removeClass('hidden');
        $("#curtain").addClass('curtain-expanded').removeClass('curtain-closed');
        $("#unlink-modal-popup-"+id).removeClass('modal-close').addClass('modal-popup');
    });
    $('#curtain, button[id^="unlink-modal-cancel-"]').on("click", function () {
        let id = this.getAttribute('id').split('-')[3];
        $("#curtain").addClass('curtain-closed').removeClass('curtain-expanded');
        $("#unlink-modal-popup-"+id).removeClass('modal-popup').addClass('modal-close');
        $("*[id^='unlink-modal-']").removeClass('hidden').addClass('hidden');
        $("*[id^='unlink-modal-popup-']").removeClass('hidden');
    });

    // link submission modal
    $('.link-submission-button').on("click", function () {
        $("#link-submission-modal").removeClass('hidden');
        $("#curtain").addClass('curtain-expanded').removeClass('curtain-closed');
        $("#link-submission-popup").removeClass('modal-close').addClass('modal-popup');
        $('#create-new-submission-button').attr('href', $(this).attr('href'));
        $('#link-submission-button').attr('project-id', $(this).attr('project-id'));
    });
    $('#curtain, #cancel-link-submission-button').on("click", function () {
        $("#curtain").addClass('curtain-closed').removeClass('curtain-expanded');
        $("#link-submission-popup").removeClass('modal-popup').addClass('modal-close');
        $("#link-submission-modal").removeClass('hidden').addClass('hidden');
    });
    $('#link-submission-button').on("click", function () {
        $.ajax({ // route('projects.link')
            type: 'POST',
            url: '/project/link/' + $(this).attr('project-id'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                submission_id: $('#link-submission-select').val()
            },
            success: function (data) {
                location.reload();
            }
        });
    });
}
