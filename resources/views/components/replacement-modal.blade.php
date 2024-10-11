{{-- modal for when replacement submission is made --}}
<div
    class="{{ !empty($replacement) ? 'curtain-expanded' : 'curtain-closed' }} inset-0 z-40 bg-gray-900/80 opacity-0"
    id="curtain-replacement"
></div>

@if (!empty($replacement))
    <div
        class="{{ !empty($replacement) ? 'flex' : 'hidden' }} fixed left-0 top-0 z-50"
        id="replacement-modal"
        aria-labelledby="modal-title"
        aria-modal="true"
    >
        <div
            class="fixed left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="field-card {{ !empty($replacement) ? 'modal-opened' : 'modal-closed' }} max-w-xl text-gray-400"
                id="replacement-popup"
            >
                <div class="flex w-full gap-3">
                    <x-icon.warning class="h-7 w-7 text-amber-400" />
                    <h2> {{ __('Select parts to replace') }} </h2>
                </div>
                <div class="mt-3 flex items-center justify-start gap-3">
                    <span>{{ $submission->submission_code ?? 'N/A' }}</span>
                    <x-icon.back class="h-5 w-5 min-w-fit rotate-180" />
                    <a
                        href="{{ route('submissions.show', $replacement->id) }}"
                        class="text-sky-400 hover:underline"
                        target="_blank"
                    >{{ $replacement->submission_code ?? 'N/A' }}</a>
                </div>
                <p class="text-wrap mt-2 text-left text-sm">
                    This will autofill the the "Replaced by" field for the following parts with
                    <span>{{ $submission->submission_code }}</span>.
                    The following parts are eligible for replacement. Please check the ones that should be replaced.
                </p>
                <div class="my-3 flex w-full items-center justify-start gap-3">
                    <input
                        id="select-all"
                        type="checkbox"
                    >
                    <span>{{ __('Select all') }}</span>
                </div>
                <div class="max-h-96 w-full overflow-scroll">
                    <form id="replace-form" action="{{ route('submissions.replace') }}" method="post">
                        @csrf
                        <input type="hidden" name="submission_code" value="{{ $submission->submission_code }}">
                        @foreach ($replacement->parts as $part)
                            <div class="flex w-full items-center justify-start gap-3">
                                <input
                                    class="part-checkbox"
                                    name="{{ $part->id }}"
                                    type="checkbox"
                                >
                                <span class="text-nowrap">{{ $part->name ?? 'N/A' }}</span>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="mt-3 flex w-full justify-end gap-3">
                    <button
                        class="btn btn-sky max-w-fit"
                        id="replace-modal-proceed-button"
                        type="button"
                    >
                        {{ __('Proceed') }}
                    </button>
                    <button
                        class="btn btn-sky-light max-w-fit"
                        id="replace-modal-cancel-button"
                        type="button"
                    >
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
