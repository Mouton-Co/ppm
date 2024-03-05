export function processTypes() {
    $('#pdfOrDwg').on('change', function() {
        if (this.checked) {
            $('#pdf').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#dwg').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#pdfOrStep').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#dwgOrStep').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#pdf-strikethrough').removeClass('hidden');
            $('#dwg-strikethrough').removeClass('hidden');
            $('#pdfOrStep-strikethrough').removeClass('hidden');
            $('#dwgOrStep-strikethrough').removeClass('hidden');
        } else {
            $('#pdf').attr('disabled', false).removeClass('bg-gray-300');
            $('#dwg').attr('disabled', false).removeClass('bg-gray-300');
            $('#pdfOrStep').attr('disabled', false).removeClass('bg-gray-300');
            $('#dwgOrStep').attr('disabled', false).removeClass('bg-gray-300');
            $('#pdf-strikethrough').addClass('hidden');
            $('#dwg-strikethrough').addClass('hidden');
            $('#pdfOrStep-strikethrough').addClass('hidden');
            $('#dwgOrStep-strikethrough').addClass('hidden');
        }
    });
    $('#pdfOrStep').on('change', function() {
        if (this.checked) {
            $('#pdf').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#step').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#pdfOrDwg').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#dwgOrStep').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#pdf-strikethrough').removeClass('hidden');
            $('#step-strikethrough').removeClass('hidden');
            $('#pdfOrDwg-strikethrough').removeClass('hidden');
            $('#dwgOrStep-strikethrough').removeClass('hidden');
        } else {
            $('#pdf').attr('disabled', false).removeClass('bg-gray-300');
            $('#step').attr('disabled', false).removeClass('bg-gray-300');
            $('#pdfOrDwg').attr('disabled', false).removeClass('bg-gray-300');
            $('#dwgOrStep').attr('disabled', false).removeClass('bg-gray-300');
            $('#pdf-strikethrough').addClass('hidden');
            $('#step-strikethrough').addClass('hidden');
            $('#pdfOrDwg-strikethrough').addClass('hidden');
            $('#dwgOrStep-strikethrough').addClass('hidden');
        }
    });
    $('#dwgOrStep').on('change', function() {
        if (this.checked) {
            $('#dwg').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#step').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#pdfOrDwg').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#pdfOrStep').prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
            $('#dwg-strikethrough').removeClass('hidden');
            $('#step-strikethrough').removeClass('hidden');
            $('#pdfOrDwg-strikethrough').removeClass('hidden');
            $('#pdfOrStep-strikethrough').removeClass('hidden');
        } else {
            $('#dwg').attr('disabled', false).removeClass('bg-gray-300');
            $('#step').attr('disabled', false).removeClass('bg-gray-300');
            $('#pdfOrDwg').attr('disabled', false).removeClass('bg-gray-300');
            $('#pdfOrStep').attr('disabled', false).removeClass('bg-gray-300');
            $('#dwg-strikethrough').addClass('hidden');
            $('#step-strikethrough').addClass('hidden');
            $('#pdfOrDwg-strikethrough').addClass('hidden');
            $('#pdfOrStep-strikethrough').addClass('hidden');
        }
    });
}
