
<div class="text-xs bg-gray-700 uppercase border cursor-pointer border-dark-field-border text-gray-400 w-full px-12 py-2 flex items-center justify-center relative" {{ $attributes }}>
    {{ $slot }}
    @if (! empty($orderable) && $orderable)
        <x-icon.drag class="h-3 w-3 cursor-pointer absolute left-2" />
    @endif
</div>
