@php
    $bg = '!bg-transparent !text-gray-400';
    if ($key == 'status') {
        // color code the statuses
        if (strtolower($datum->$key) == 'closed') {
            $bg = '!bg-green-300 !text-green-800';
        } elseif (strtolower($datum->$key) == 'waiting for customer') {
            $bg = '!bg-cyan-300 !text-cyan-800';
        } elseif (strtolower($datum->$key) == 'prepare') {
            $bg = '!bg-zinc-300 !text-zinc-800';
        } elseif (strtolower($datum->$key) == 'work in progress') {
            $bg = '!bg-orange-300 !text-orange-800';
        }
    }
@endphp

@if ($model::$structure[$key]['filterable_options'] == 'custom')
    @php
        $customKey = \Str::camel("get_custom_{$key}_attribute");
        $options = $model::$customKey();
    @endphp
@else
    @php
        $options = $model::$structure[$key]['filterable_options'];
    @endphp
@endif

<select
    class="field {{ $bg }} cell-dropdown {{ $key == 'status' ? 'project-status-dropdown' : '' }} !w-[195px] cursor-pointer border-none !ring-0 focus:outline-none focus:ring-0"
    name="{{ $key }}"
    item-id="{{ $datum->id }}"
>
    <option
        value=""
        disabled
        selected
    >
        {{ $value ?? '' }}
    </option>
    @foreach ($options as $option)
        <option
            value="{{ $option }}"
            @if ($option === $datum->$key) selected @endif
        >
            {{ $option }}
        </option>
    @endforeach
</select>
