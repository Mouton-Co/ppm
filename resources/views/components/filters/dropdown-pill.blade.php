<div class="relative flex h-7 items-center text-sm text-white shadow" field="{{ $key }}">
    <div class="h-full cursor-default rounded-l bg-gray-700 px-2 leading-loose text-gray-400 flex items-center gap-1">
        <span>
            {{ $label ?? 'No label'}}
        </span>
        <x-icon.arrow class="h-4 w-4" />
    </div>
    <div class="flex h-full w-40 items-center">
        <select
            class="h-full w-full cursor-pointer truncate rounded-r border-0 bg-sky-700 bg-left py-0 pl-2 pr-6 text-sm focus:ring-0"
            name="{{ $key ?? 'no-key' }}"
            style="background-image: none;"
        >
            {!! $slot !!}
        </select>
    </div>
    <x-icon.x class="absolute right-0 aspect-square h-full cursor-pointer py-1 text-gray-300 hover:text-gray-400 close-filter-pill" />
</div>
