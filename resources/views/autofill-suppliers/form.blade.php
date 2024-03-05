@if (!empty($edit) && $edit)
    <input type="hidden" name="autofill_supplier_id" value="{{ $autofillSupplier->id }}">
@endif

<label class="label-dark" for="text">{{ __('If part name contains...') }}</label>
<input type="text" id="text" name="text" required
    class="{{ !empty($errors->get('text')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $autofillSupplier->text ?? old('text') }}">
@if (!empty($errors->get('text')))
    @foreach ($errors->get('text') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="supplier_id">{{ __('Autofill supplier to...') }}</label>
<select class="w-full {{ !empty($errors->get('role')) ? 'field-error' : 'field-dark' }} mb-5" name="supplier_id"
id="supplier_id" required>
    <option value="">
        {{ __('--Please select--') }}
    </option>
    @foreach ($suppliers as $supplier)
        <option {{ // check if selected
            !empty($autofillSupplier->supplier->id) && $supplier->id == $autofillSupplier->supplier->id ?
            'selected' : ''
        }}
        value="{{ $supplier->id }}">
            {{ $supplier->name }}
        </option>
    @endforeach
</select>
