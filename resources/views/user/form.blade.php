@if (!empty($edit) && $edit)
    <input type="hidden" name="user_id" value="{{ $user->id }}">
@endif

<label class="label-dark" for="name">{{ __('Name') }}</label>
<input type="text" id="name" name="name" required
    class="{{ !empty($errors->get('name')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $user->name ?? old('name') }}">
@if (!empty($errors->get('name')))
    @foreach ($errors->get('name') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="email">{{ __('Email') }}</label>
<input type="email" id="email" name="email" required
    class="{{ !empty($errors->get('email')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $user->email ?? old('email') }}">
@if (!empty($errors->get('email')))
    @foreach ($errors->get('email') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="role">{{ __('Role') }}</label>
<select {{ (!empty($edit) && $edit) && (auth()->user()->id == $user->id) ? 'disabled' : '' }}
    class="w-full {{ !empty($errors->get('role')) ? 'field-error' : 'field-dark' }} mb-5" name="role" id="role">
    @foreach ($roles as $role)
        <option {{ $role->id == $user->role->id ? 'selected' : '' }}
        value="{{ $role->id }}">
            {{ $role->role }}
        </option>
    @endforeach
</select>
{{-- if select is disabled still send through role_id --}}
@if ((!empty($edit) && $edit) && (auth()->user()->id == $user->id))
    <input type="hidden" name="role" value="{{ $role->id }}">
@endif
@if (!empty($errors->get('role')))
    @foreach ($errors->get('role') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="password">{{ __('Password') }}</label>
<input type="password" id="password" name="password"
    class="{{ !empty($errors->get('password')) || !empty($errors->get('confirm_password'))
    ? 'field-error' : 'field-dark' }} mb-5">
@if (!empty($errors->get('password')))
    @foreach ($errors->get('password') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label class="label-dark" for="confirm_password">{{ __('Confirm password') }}</label>
<input type="password" id="confirm_password" name="confirm_password"
    class="{{ !empty($errors->get('password')) || !empty($errors->get('confirm_password'))
    ? 'field-error' : 'field-dark' }} mb-5">
@if (!empty($errors->get('confirm_password')))
    @foreach ($errors->get('confirm_password') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif
