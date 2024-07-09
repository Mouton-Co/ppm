<div class="flex gap-1 items-center">
    @if (! empty($datum->pdf()))
        <a
            class="max-w-fit !p-0"
            href="{{ route('file.open', $datum->pdf()->id) }}"
            target="_blank"
        >
            <x-icon.pdf class="h-6 text-gray-300 hover:text-sky-700" />
        </a>
    @endif
    {{ $datum?->{$key} ?? 'N/A' }}
</div>
