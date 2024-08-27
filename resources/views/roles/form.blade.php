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
    id="role"
    name="role"
    type="text"
    value="{{ $role->role ?? old('role') }}"
    required
>
@if (!empty($errors->get('role')))
    @foreach ($errors->get('role') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

{{-- landing page --}}
<label
    class="label-dark"
    for="landing_page"
>{{ __('Landing page') }}</label>
<select
    class="{{ !empty($errors->get('landing_page')) ? 'field-error' : 'field-dark' }} mb-5 w-full"
    id="landing_page"
    name="landing_page"
    required
>
    @foreach ($landingPages as $landingPage)
        <option
            value="{{ $landingPage }}"
            {{ !empty($role) && $role->landing_page == $landingPage ? 'selected' : '' }}
        >
            {{ $landingPage }}
        </option>
    @endforeach
</select>

{{-- permissions --}}
<label
    class="label-dark"
    for="permissions"
>{{ __('Permissions') }}</label>
<div class="relative min-h-[550px] overflow-x-auto rounded">
    <table class="border-dark-field-border w-full border text-left text-sm text-gray-400 rtl:text-right">
        <thead class="bg-gray-700 text-xs uppercase text-gray-400">
            <tr>
                <th class="flex items-center gap-2 px-6 py-2">
                    {{ __('Select all') }}
                    <input
                        id="select-all"
                        type="checkbox"
                    >
                </th>
                <th class="px-6 py-2">{{ __('Create') }}</th>
                <th class="px-6 py-2">{{ __('Read') }}</th>
                <th class="px-6 py-2">{{ __('Update') }}</th>
                <th class="px-6 py-2">{{ __('Delete') }}</th>
                <th class="px-6 py-2">{{ __('Restore') }}</th>
                <th class="px-6 py-2">{{ __('Force Delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissionTable as $permissionTableKey => $permissionTableValue)
                <tr class="border-b border-gray-700 bg-gray-800">
                    <td class="flex items-center gap-2 text-nowrap px-6 py-2">
                        <input
                            id="select-all-{{ $permissionTableKey }}"
                            type="checkbox"
                        >
                        {{ $permissionTableValue }}
                    </td>
                    <td class="text-nowrap px-6 pl-8 py-2">
                        @if (! in_array("create_{$permissionTableKey}", $permissionTableExcludes))
                            <input
                                id="create-{{ $permissionTableKey }}"
                                name="create_{{ $permissionTableKey }}"
                                type="checkbox"
                                @if (! empty($role) && $role->hasPermission("create_{$permissionTableKey}"))
                                    checked
                                @endif
                            >
                        @endif
                    </td>
                    <td class="text-nowrap px-6 pl-8 py-2">
                        @if (! in_array("read_{$permissionTableKey}", $permissionTableExcludes))
                            <input
                                id="read-{{ $permissionTableKey }}"
                                name="read_{{ $permissionTableKey }}"
                                type="checkbox"
                                @if (! empty($role) && $role->hasPermission("read_{$permissionTableKey}"))
                                    checked
                                @endif
                            >
                        @endif
                    </td>
                    <td class="text-nowrap px-6 pl-8 py-2">
                        @if (! in_array("update_{$permissionTableKey}", $permissionTableExcludes))
                            <input
                                id="update-{{ $permissionTableKey }}"
                                name="update_{{ $permissionTableKey }}"
                                type="checkbox"
                                @if (! empty($role) && $role->hasPermission("update_{$permissionTableKey}"))
                                    checked
                                @endif
                            >
                        @endif
                    </td>
                    <td class="text-nowrap px-6 pl-8 py-2">
                        @if (! in_array("delete_{$permissionTableKey}", $permissionTableExcludes))
                            <input
                                id="delete-{{ $permissionTableKey }}"
                                name="delete_{{ $permissionTableKey }}"
                                type="checkbox"
                                @if (! empty($role) && $role->hasPermission("delete_{$permissionTableKey}"))
                                    checked
                                @endif
                            >
                        @endif
                    </td>
                    <td class="text-nowrap px-6 pl-8 py-2">
                        @if (! in_array("restore_{$permissionTableKey}", $permissionTableExcludes))
                            <input
                                id="restore-{{ $permissionTableKey }}"
                                name="restore_{{ $permissionTableKey }}"
                                type="checkbox"
                                @if (! empty($role) && $role->hasPermission("restore_{$permissionTableKey}"))
                                    checked
                                @endif
                            >
                        @endif
                    </td>
                    <td class="text-nowrap px-6 pl-8 py-2">
                        @if (! in_array("force_delete_{$permissionTableKey}", $permissionTableExcludes))
                            <input
                                id="force_delete-{{ $permissionTableKey }}"
                                name="force_delete_{{ $permissionTableKey }}"
                                type="checkbox"
                                @if (! empty($role) && $role->hasPermission("force_delete_{$permissionTableKey}"))
                                    checked
                                @endif
                            >
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
