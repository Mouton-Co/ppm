<div class="flex gap-1 items-center">
    @if (! empty($datum->pdf()))
        <a
            class="max-w-fit !p-0"
            href="{{ route('file.open', $datum->pdf()->id) }}"
            target="_blank"
        >
            <x-icon.pdf class="h-6 hover:text-sky-700 {{ ! empty($datum->replaced_by_submission) ? 'text-red-500' : 'text-gray-300' }}" />
        </a>
    @endif
    {{ $datum?->{$key} ?? 'N/A' }}
</div>
