<div class="flex items-center justify-start gap-2">
    @if (! empty($datum->submission->submission_code))
        <a
            class="max-w-fit !p-0"
            href="{{ route('zip.download', $datum->submission->submission_code) }}"
            download
        >
            <x-icon.zip class="h-6 text-gray-300 hover:text-sky-700" />
        </a>
    @else
        <x-icon.zip class="h-6 text-gray-300" />
    @endif
    @if (! empty($datum->submission->submission_code))
        <a
            class="max-w-fit !p-0"
            href="{{ route('submissions.show', [
                'id' => $datum->submission->id,
                'part' => $datum->id,
            ]) }}"
            target="_blank"
        >
            <x-icon.part class="h-5 text-gray-300 hover:text-sky-700" />
        </a>
    @else
        <x-icon.part class="h-5 text-gray-300" />
    @endif
    {{ $datum->submission->submission_code ?? '-' }}
</div>
