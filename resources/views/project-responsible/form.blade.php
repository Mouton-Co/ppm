@if (! empty($projectResponsible->id))
    <input type="hidden" name="project_responsible_id" value="{{ $projectResponsible->id }}">
@endif

<label class="label-dark" for="name">{{ __('Name') }}</label>
<input type="text" id="name" name="name" required
    class="{{ !empty($errors->get('name')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $projectResponsible->name ?? old('name') }}">
@if (!empty($errors->get('name')))
    @foreach ($errors->get('name') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif
