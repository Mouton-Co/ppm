@if (!empty($supplier))
    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
@endif

<label class="label-dark" for="name">{{ __('Name') }}</label>
<input type="text" id="name" name="name" required
    class="{{ !empty($errors->get('name')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $supplier->name ?? old('name') }}">
@if (!empty($errors->get('name')))
    @foreach ($errors->get('name') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="name">{{ __('Template') }}</label>
<select name="template" id="template" class="field-dark mb-5">
    <option value="1" selected>{{ __("1") }}</option>
    <option value="2">{{ __("2") }}</option>
</select>
@if (!empty($errors->get('template')))
    @foreach ($errors->get('template') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="average_lead_time">{{ __('Average lead time') }}</label>
<input type="text" id="average_lead_time" name="average_lead_time"
    class="{{ !empty($errors->get('average_lead_time')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $supplier->average_lead_time ?? old('average_lead_time') }}">
@if (!empty($errors->get('average_lead_time')))
    @foreach ($errors->get('average_lead_time') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif
