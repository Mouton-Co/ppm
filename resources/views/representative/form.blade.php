@if (!empty($representative))
    <input type="hidden" name="representative_id" value="{{ $representative->id }}">
@endif

<label class="label-dark" for="name">{{ __('Name') }}</label>
<input type="text" id="name" name="name" required
    class="{{ !empty($errors->get('name')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $representative->name ?? old('name') }}">
@if (!empty($errors->get('name')))
    @foreach ($errors->get('name') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="email">{{ __('Email') }}</label>
<input type="email" id="email" name="email" required
    class="{{ !empty($errors->get('email')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $representative->email ?? old('email') }}">
@if (!empty($errors->get('email')))
    @foreach ($errors->get('email') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="phone_1">{{ __('Phone 1') }}</label>
<input type="text" id="phone_1" name="phone_1"
    class="{{ !empty($errors->get('phone_1')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $representative->phone_1 ?? old('phone_1') }}">
@if (!empty($errors->get('phone_1')))
    @foreach ($errors->get('phone_1') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="phone_2">{{ __('Phone 2') }}</label>
<input type="text" id="phone_2" name="phone_2"
    class="{{ !empty($errors->get('phone_2')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $representative->phone_2 ?? old('phone_2') }}">
@if (!empty($errors->get('phone_2')))
    @foreach ($errors->get('phone_2') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="supplier_id">{{ __('Supplier') }}</label>
<select class="w-full {{ !empty($errors->get('role')) ? 'field-error' : 'field-dark' }} mb-5" name="supplier_id"
id="supplier_id" required>
    @foreach ($suppliers as $supplier)
        <option {{ !empty($representative) && $representative->supplier_id == $supplier->id ? 'selected' : '' }}
        value="{{ $supplier->id }}">
            {{ $supplier->name }}
        </option>
    @endforeach
</select>
