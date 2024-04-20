export function modals() {
    // purchase order modal
    $('div[id^="order-card-"]').on("click", function () {
        let id = $(this).attr('id').split('-')[2];
        $('#order-modal-' + id).removeClass('hidden').addClass('flex');
        $('#modal-curtain').removeClass('hidden').addClass('flex');
    });

    $('#modal-curtain').on("click", function () {
        $('.purchase-order-modal').removeClass('flex').addClass('hidden');
        $('#modal-curtain').removeClass('flex').addClass('hidden');
    });

    // tooltip trigger
    $('.tooltip-trigger').hover(function () {
        let id = $(this).attr('tooltip-id');
        $('#' + id).removeClass('hidden').addClass('fixed');
    }, function () {
        let id = $(this).attr('tooltip-id');
        $('#' + id).addClass('hidden').removeClass('fixed');
    });
}
