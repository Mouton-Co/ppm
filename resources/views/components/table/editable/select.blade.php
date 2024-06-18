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
    
    $structure = $structure ?? $model::structure();

    $options = [];
    if (! empty($structure[$key]['filterable_options']) && is_array($structure[$key]['filterable_options'])) {
        $options = $structure[$key]['filterable_options'];
    } elseif (! empty($structure[$key]['filterable_options']) && $structure[$key]['filterable_options'] == 'custom') {
        $customKey = \Str::camel("get_custom_{$key}_attribute");
        $options = $model::$customKey();
    } elseif ($structure[$key]['type'] == 'relationship') {
        $options = $structure[$key]['relationship_model']::orderBy($structure[$key]['relationship_field'])->get();
    }
@endphp

<select
    class="field {{ $bg }} cell-dropdown {{ $key == 'status' ? 'project-status-dropdown' : '' }} !w-[195px] cursor-pointer border-none !ring-0 focus:outline-none focus:ring-0"
    name="{{ $key }}"
    item-id="{{ $datum->id }}"
    model="{{ $model }}"
>
    <option
        value=""
        disabledw
        selected
    >
        {{ __("--Please select--") }}
    </option>
    @foreach ($options as $option)
        @if ($option instanceof \Illuminate\Database\Eloquent\Model)
            <option
                value="{{ $option->id }}"
                @if ($option->id === $datum->$key) selected @endif
            >
                {{ $option->{$structure[$key]['relationship_field']} }}
            </option>
        @else
            <option
                value="{{ $option }}"
                @if ($option === $datum->$key) selected @endif
            >
                {{ $option }}
            </option>
        @endif
    @endforeach
</select>
