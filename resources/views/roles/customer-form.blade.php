@if (!empty($role))
    <input
        name="role_id"
        type="hidden"
        value="{{ $role->id }}"
    >
@endif

{{-- role --}}
<label
    class="label-dark"
    for="role"
>{{ __('Role') }}</label>
<input
    class="{{ !empty($errors->get('role')) ? 'field-error' : 'field-dark' }} mb-5"
    name="role"
    type="text"
    value="{{ $role->role ?? old('role') }}"
>
@if (!empty($errors->get('role')))
    @foreach ($errors->get('role') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

{{-- machine numbers --}}
<label
    class="label-dark"
    for="machine_numbers"
>{{ __('Machine numbers') }}</label>

{{-- list of all machine numbers --}}
@foreach ($machineNumbers as $machineNumber)
    <div class="flex items-center gap-3">
        <input
            id="machine_number_{{ $machineNumber }}"
            name="machine_numbers[]"
            type="checkbox"
            class="mb-1"
            value="{{ $machineNumber }}"
            {{ !empty($role) && $role->hasPermission($machineNumber) ? 'checked' : '' }}
        >
        <label
            class="label-dark"
            for="machine_number_{{ $machineNumber }}"
        >{{ $machineNumber }}</label>
    </div>
@endforeach
