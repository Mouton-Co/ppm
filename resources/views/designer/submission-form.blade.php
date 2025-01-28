<form action="{{ route('store.submission') }}" id="submission-form" method="POST" class="flex flex-col">
    @csrf

    <input type="hidden" name="submission_code" value="{{ $submission->submission_code }}">

    <label class="label-dark" for="assembly_name">{{ __('Assemly name') }}</label>
    <input type="text" id="assembly_name" name="assembly_name" class="field-dark mb-5" required>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 mb-5">
        <div class="w-full">
            <label class="label-dark" for="submission_type">{{ __('Submission type') }}</label>
            <select class="w-full field-dark" id="submission_type" name="submission_type" required>
                <option value="">{{ __('--Please select--') }}</option>
                @foreach ($submission_types as $key => $submission_type)
                    <option value="{{ $key }}" @if (request()->get('submission_type') == $key) selected @endif>
                        {{ $submission_type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-full">
            <label class="label-dark" for="machine_number">{{ __('Machine number') }}</label>
            <input type="text" name="machine_number" id="machine_number" required class="field-dark"
            value="{{ request()->get('machine_number') ?? '' }}">
        </div>
        <div class="w-full">
            <label class="label-dark text-nowrap" for="current_unit_number">{{ __('Current unit number') }}</label>
            <select class="w-full field-dark" name="current_unit_number" id="current_unit_number" required>
                <option value="">{{ __('--Please select--') }}</option>
                @foreach ($unit_numbers as $key => $unit_number)
                    <option value="{{ $key }}">{{ $key . ' - ' . $unit_number }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full">
            <label class="label-dark" for="project_id">{{ __('Project') }}</label>
            <select class="w-full field-dark" name="project_id" id="project_id">
                <option value="">{{ __('--Please select--') }}</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}"
                    @if (request()->get('project_id') == $project->id) selected @endif>
                        {{ $project->coc }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-full">
            <label class="label-dark" for="project_id">{{ __('Quantity') }}</label>
            <input type="number" name="quantity" min="1" max="100" value="1" class="w-full field-dark">
        </div>
    </div>

    <label class="label-dark" for="notes">{{ __('Notes') }}</label>
    <textarea name="notes" cols="30" rows="6" class="field-dark mb-5" placeholder="Additional notes...">{!!
        request()->get('notes') ?? ''
    !!}</textarea>
</form>
