export function processTypes() {

    $(".process-type-option input").on('change', function() {
        if (this.id.includes('or')) {

            let filetype1 = this.id.split('or')[0];
            let filetype2 = this.id.split('or')[1];
            let currentId = this.id;
            let currentChecked = this.checked;

            $('.process-type-option input').each(function() {
                if (
                    this.id !== currentId &&
                    (this.id.includes(filetype1) || this.id.includes(filetype2))
                ) {
                    if (currentChecked) {
                        $(this).prop('checked', false).attr('disabled', true).addClass('bg-gray-300');
                        $('#' + this.id + '-strikethrough').removeClass('hidden');
                    } else {
                        $(this).attr('disabled', false).removeClass('bg-gray-300');
                        $('#' + this.id + '-strikethrough').addClass('hidden');
                    }
                }
            });

        }
    });
    
}
