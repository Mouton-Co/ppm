@if (auth()->user()->role->customer)
    <div class="flex w-full items-center justify-center gap-3">
        {{ $datum?->submission?->submission_code ?? 'N/A' }}
    </div>
@else
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
                class="btn btn-sky link-submission-button !max-h-fit max-w-fit !rounded !py-1 !text-xs"
                href="{{ route('submissions.create', [
                    'machine_number' => $datum->machine_nr,
                    'submission_type' => 0,
                    'project_id' => $datum->id,
                    'notes' => $datum->notes,
                ]) }}"
                project-id="{{ $datum->id }}"
            >
                {{ __('Link submission') }}
            </div>
        </div>
    @endif
@endif
