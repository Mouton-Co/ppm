@if (! empty($recipientGroup->id))
    <input type="hidden" name="recipient_group_id" value="{{ $recipientGroup->id }}">
@endif

<div class="flex gap-6 items-center mb-5">
    {{-- Triggers when... --}}
    <label class="label-dark text-nowrap" for="field">{{ __('Triggers when...') }}</label>
    <select class="w-full cursor-pointer {{ !empty($errors->get('field')) ? 'field-error' : 'field-dark' }}"
        name="field" required id="select-triggers-when">
        <option value="" disabled selected>{{ __("--Please select--") }}</option>
        <option value="Currently responsible" {{
            ! empty($recipientGroup) && $recipientGroup->field == 'Currently responsible' ? 'selected' : old('field')
        }}>
            {{ __('Currently responsible') }}
        </option>
        <option value="Status" {{
            ! empty($recipientGroup) && $recipientGroup->field == 'Status' ? 'selected' : old('field')
        }}>
            {{ __('Status') }}
        </option>
        <option value="Item created" {{
            ! empty($recipientGroup) && $recipientGroup->field == 'Item created' ? 'selected' : old('field')
        }}>
            {{ __('Item created') }}
        </option>
        <option value="Status updated for" {{
            ! empty($recipientGroup) && $recipientGroup->field == 'Status updated for' ? 'selected' : old('field')
        }}>
            {{ __('Status updated for') }}
        </option>
        <option value="Order confirmed by supplier" {{
            ! empty($recipientGroup) && $recipientGroup->field == 'Order confirmed by supplier' ? 'selected' : old('field')
        }}>
            {{ __('Order confirmed by supplier') }}
        </option>
    </select>
    @if (!empty($errors->get('field')))
        @foreach ($errors->get('field') as $error)
            @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
        @endforeach
    @endif

    {{-- Changed to... / Item... --}}
    <label class="label-dark text-nowrap" for="value" id="select-changed-to-label">
        {{ __('Changed to...') }}
    </label>
    <select class="w-full cursor-pointer {{ !empty($errors->get('value')) ? 'field-error' : 'field-dark' }}"
        name="value" required id="select-changed-to" {{ empty($recipientGroup) ? 'disabled' : '' }}>
        <option value="" disabled selected>{{ __("--Please select--") }}</option>

        {{-- list of departments --}}
        @foreach ($departments as $department)
            <option
                value="{{ $department->name }}"
                class="department-item {{ ! empty($recipientGroup) && $recipientGroup->field != 'Currently responsible' ? 'hidden' : '' }}"
                {{ ! empty($recipientGroup?->value) && $recipientGroup?->value == $department->name ? 'selected' : '' }}
            >
                {{ $department->name }}
            </option>
        @endforeach

        {{-- list of statuses --}}
        @foreach ($statuses as $status)
            <option
                value="{{ $status->name }}"
                class="status-item {{ ! empty($recipientGroup) && $recipientGroup->field != 'Status' ? 'hidden' : '' }}"
                {{ ! empty($recipientGroup?->value) && $recipientGroup?->value == $status->name ? 'selected' : '' }}
            >
                {{ $status->name }}
            </option>
        @endforeach

        {{-- list of items --}}
        <option
            value="CoC"
            class="created-item {{ ! empty($recipientGroup) && $recipientGroup->field != 'Item created' ? 'hidden' : '' }}"
            {{ ! empty($recipientGroup?->value) && $recipientGroup?->value == 'CoC' ? 'selected' : '' }}
        >
            {{ __("CoC") }}
        </option>
        <option
            value="Submission"
            class="created-item {{ ! empty($recipientGroup) && $recipientGroup->field != 'Item created' ? 'hidden' : '' }}"
            {{ ! empty($recipientGroup?->value) && $recipientGroup?->value == 'Submission' ? 'selected' : '' }}
        >
            {{ __("Submission") }}
        </option>

        {{-- list of machine numbers --}}
        @foreach ($machineNumbers as $machineNumber)
            <option
                value="{{ $machineNumber }}"
                class="machine-item {{ ! empty($recipientGroup) && $recipientGroup->field != 'Status updated for' ? 'hidden' : '' }}"
                {{ ! empty($recipientGroup?->value) && $recipientGroup?->value == $machineNumber ? 'selected' : '' }}
            >
                {{ $machineNumber }}
            </option>
        @endforeach

    </select>
    @if (!empty($errors->get('value')))
        @foreach ($errors->get('value') as $error)
            @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
        @endforeach
    @endif
</div>

{{-- Recipients --}}
<label class="label-dark" for="recipients">
    {{ __('Recipients') }}
    <span class="text-gray-400 text-xs ml-3">
        {{ __('* put each email on a new line') }}
    </span>
</label>
<textarea id="recipients" name="recipients"
    class="{{ !empty($errors->get('recipients')) ? 'field-error' : 'field-dark' }} mb-5"
    rows="5">{{ $recipientGroup->recipients ?? old('recipients') }}</textarea>
@if (!empty($errors->get('recipients')))
    @foreach ($errors->get('recipients') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif
