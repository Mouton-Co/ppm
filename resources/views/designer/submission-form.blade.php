<form action="{{ route('store.submission') }}" id="submission-form" method="POST" class="flex flex-col">
    @csrf

    <input type="hidden" name="submission_code" value="{{ $submission->submission_code }}">

    <label class="label-dark" for="assembly_name">{{ __('Assemly name') }}</label>
    <input type="text" id="assembly_name" name="assembly_name" class="field-dark mb-5" required>

    <div class="flex mb-5 flex-col sm:flex-row sm:gap-5">
        <div class="w-full mb-5 md:mb-0">
            <label class="label-dark" for="machine_number">{{ __('Machine number') }}</label>
            <input type="text" name="machine_number" id="machine_number" required class="field-dark">
        </div>
        <div class="w-full mb-5 md:mb-0">
            <label class="label-dark" for="submission_type">{{ __('Submission type') }}</label>
            <select class="w-full field-dark" id="submission_type" name="submission_type" required>
                <option value="">{{ __('--Please select--') }}</option>
                @foreach ($submission_types as $key => $submission_type)
                    <option value="{{ $key }}">{{ $submission_type }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full">
            <label class="label-dark" for="current_unit_number">{{ __('Current unit number') }}</label>
            <select class="w-full field-dark" name="current_unit_number" id="current_unit_number" required>
                <option value="">{{ __('--Please select--') }}</option>
                @foreach ($unit_numbers as $key => $unit_number)
                    <option value="{{ $key }}">{{ $key . ' - ' . $unit_number }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <label class="label-dark" for="notes">{{ __('Notes') }}</label>
    <textarea name="notes" cols="30" rows="6" class="field-dark mb-5" placeholder="Additional notes..."></textarea>
</form>
