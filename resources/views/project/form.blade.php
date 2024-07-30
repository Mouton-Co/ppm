<label
    class="label-dark"
    for="machine_nr"
>{{ __('Machine Nr') }}</label>
<input
    class="{{ !empty($errors->get('machine_nr')) ? 'field-error' : 'field-dark' }} mb-5"
    id="machine_nr"
    name="machine_nr"
    type="text"
    value="{{ $project->machine_nr ?? old('machine_nr') }}"
    required
>
@if (!empty($errors->get('machine_nr')))
    @foreach ($errors->get('machine_nr') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label
    class="label-dark"
    for="country"
>{{ __('Country/Company') }}</label>
<input
    class="{{ !empty($errors->get('country')) ? 'field-error' : 'field-dark' }} mb-5"
    id="country"
    name="country"
    type="text"
    value="{{ $project->country ?? old('country') }}"
    required
>
@if (!empty($errors->get('country')))
    @foreach ($errors->get('country') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<div class="flex items-center gap-3">
    <label
        class="label-dark"
        for="coc"
    >{{ __('Ticket Nr') }}</label>
    <span class="text-sm text-slate-500">{{ __('*Auto generation recommended') }}</span>
</div>
<div class="mb-5 flex items-center gap-3">
    <div
        class="btn btn-sky max-w-fit"
        id="coc-generate"
    >
        {{ __('Generate') }}
    </div>
    <input
        class="{{ !empty($errors->get('coc')) ? 'field-error' : 'field-dark' }}"
        id="coc"
        name="coc"
        type="text"
        value="{{ $project->coc ?? old('coc') }}"
        required
    >
</div>
@if (!empty($errors->get('coc')))
    @foreach ($errors->get('coc') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label
    class="label-dark"
    for="noticed_issue"
>{{ __('Noticed Issue') }}</label>
<textarea
    class="field-dark mb-5"
    name="noticed_issue"
    cols="30"
    rows="6"
    placeholder="Noticed issue..."
>{{ $project->noticed_issue ?? old('noticed_issue') }}</textarea>

<label
    class="label-dark"
    for="proposed_solution"
>{{ __('Proposed Solution') }}</label>
<textarea
    class="field-dark mb-5"
    name="proposed_solution"
    cols="30"
    rows="6"
    placeholder="Proposed solution..."
>{{ $project->proposed_solution ?? old('proposed_solution') }}</textarea>

<label
    class="label-dark"
    for="currently_responsible"
>{{ __('Currently Responsible') }}</label>
<select
    class="{{ !empty($errors->get('currently_responsible')) ? 'field-error' : 'field-dark' }} mb-5 w-full cursor-pointer"
    id="currently_responsible"
    name="currently_responsible"
    required
>
    <option
        value=""
        disabled
        selected
    >{{ __('--Please select--') }}</option>
    @foreach ($responsibles as $responsible)
        <option
            value="{{ $responsible }}"
            {{ !empty($project) && $project->currently_responsible == $responsible ? 'selected' : '' }}
        >
            {{ $responsible }}
        </option>
    @endforeach
</select>

<label
    class="label-dark"
    for="status"
>{{ __('Costing') }}</label>
<select
    class="{{ !empty($errors->get('costing')) ? 'field-error' : 'field-dark' }} mb-5 w-full cursor-pointer"
    id="costing"
    name="costing"
    required
>
    <option
        value="CoC"
        {{ !empty($project) && $project->costing == 'CoC' ? 'selected' : '' }}
    >
        {{ __('CoC') }}
    </option>
    <option
        value="APL"
        {{ !empty($project) && $project->costing == 'APL' ? 'selected' : '' }}
    >
        {{ __('APL') }}
    </option>
</select>

<label
    class="label-dark"
    for="status"
>{{ __('Status') }}</label>
<select
    class="{{ !empty($errors->get('status')) ? 'field-error' : 'field-dark' }} mb-5 w-full cursor-pointer"
    id="status"
    name="status"
    required
>
    <option
        value=""
        disabled
        selected
    >{{ __('--Please select--') }}</option>
    @foreach ($statuses as $status)
        <option
            value="{{ $status->name }}"
            {{ (!empty($project) && $project->status == $status->name) || $status->name == 'Prepare' ? 'selected' : '' }}
        >
            {{ $status->name }}
        </option>
    @endforeach
</select>

@if (!empty($project))
    <label
        class="label-dark"
        for="related_po"
    >{{ __('Related PO') }}</label>
    <input
        class="{{ !empty($errors->get('related_po')) ? 'field-error' : 'field-dark' }} mb-5"
        id="related_po"
        name="related_po"
        type="text"
        value="{{ $project->related_po ?? old('related_po') }}"
    >
    @if (!empty($errors->get('related_po')))
        @foreach ($errors->get('related_po') as $error)
            @include('components.error-message', [
                'error' => $error,
                'hidden' => 'false',
                'class' => 'mb-5',
            ])
        @endforeach
    @endif
@endif

<label
    class="label-dark"
    for="customer_comment"
>{{ __('Customer Comment') }}</label>
<textarea
    class="field-dark mb-5"
    name="customer_comment"
    cols="30"
    rows="6"
    placeholder="Customer comment..."
>{{ $project->customer_comment ?? old('customer_comment') }}</textarea>

<label
    class="label-dark"
    for="commisioner_comment"
>{{ __('Commisioner Comment') }}</label>
<textarea
    class="field-dark mb-5"
    name="commisioner_comment"
    cols="30"
    rows="6"
    placeholder="Commisioner comment..."
>{{ $project->commisioner_comment ?? old('commisioner_comment') }}</textarea>

<label
    class="label-dark"
    for="logistics_comment"
>{{ __('Notes') }}</label>
<textarea
    class="field-dark mb-5"
    name="logistics_comment"
    cols="30"
    rows="6"
    placeholder="Logistics comment..."
>{{ $project->logistics_comment ?? old('logistics_comment') }}</textarea>
