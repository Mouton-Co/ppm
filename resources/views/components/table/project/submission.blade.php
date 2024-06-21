@if (!empty($datum->submission->submission_code))
    <div class="flex w-full items-center justify-center gap-3">
        {{ $datum->submission->submission_code }}
        <x-icon.unlink
            class="h-[20px] cursor-pointer text-red-500 hover:text-red-700"
            id="unlink-button-{{ $datum->id }}"
        />
        @include('components.unlink-modal', [
            'model' => $datum,
        ])
    </div>
@else
    <div class="flex w-full justify-start">
        <div
            class="btn btn-sky !max-h-fit max-w-fit !rounded !py-1 !text-xs link-submission-button"
            project-id="{{ $datum->id }}"
            href="{{ route('submissions.create', [
                'machine_number' => $datum->machine_nr,
                'submission_type' => 0,
                'project_id' => $datum->id,
                'notes' => $datum->notes,
            ]) }}"
        >
            {{ __('Link submission') }}
        </div>
    </div>
@endif
