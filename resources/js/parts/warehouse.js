export function warehouse() {
    // when checkbox with name "mark_as" value is changed
    $('select[name="mark_as"]').on('change', function() {
        console.log('hits');
        if (this.value == 'qc_passed') {
            $('select[name="qc_by"]').addClass('field-dark').removeClass('hidden');
            $('label[for="qc_by"]').removeClass('hidden');
        } else {
            $('select[name="qc_by"]').removeClass('field-dark').addClass('hidden');
            $('label[for="qc_by"]').addClass('hidden');
        }
    });
}
