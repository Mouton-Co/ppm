<div
    class="{{ !empty($replacementOptions) ? 'curtain-expanded' : 'curtain-closed' }} inset-0 z-40 bg-gray-900/80 opacity-0"
    id="curtain-replacement"
></div>

@if (!empty($replacementOptions))
    <div
        class="{{ !empty($replacementOptions) ? 'flex' : 'hidden' }} fixed left-0 top-0 z-50"
        id="replacement-modal"
        aria-labelledby="modal-title"
        aria-modal="true"
    >
        <div
            class="fixed left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="field-card {{ !empty($replacementOptions) ? 'modal-opened' : 'modal-closed' }} max-w-4xl text-gray-400"
                id="replacement-popup"
            >
                <div class="flex w-full gap-3">
                    <x-icon.warning class="h-7 w-7 text-amber-400" />
                    <h2> {{ __('Select parts to replace') }} </h2>
                </div>
                <div class="mt-3 flex items-center justify-start gap-3">
                    <a
                        class="text-sky-400 hover:underline"
                        href="{{ route('submissions.show', $submission->id) }}"
                        target="_blank"
                    >{{ $submission->submission_code ?? 'N/A' }}</a>
                    <x-icon.back class="h-5 w-5 min-w-fit rotate-180" />
                    <a
                        class="text-sky-400 hover:underline"
                        href="{{ route('submissions.show', $replacement->id) }}"
                        target="_blank"
                    >{{ $replacement->submission_code ?? 'N/A' }}</a>
                </div>
                <p class="text-wrap mt-2 text-left text-sm">
                    The following replacements has been auto detected. Please select which ones you'd like
                    to proceed with.
                </p>
                <div class="my-3 flex w-full items-center justify-start gap-3">
                    <input
                        id="select-all"
                        type="checkbox"
                    >
                    <span>{{ __('Select all') }}</span>
                </div>
                <div class="max-h-96 w-full overflow-scroll">
                    <form
                        id="replace-form"
                        action="{{ route('submissions.replace') }}"
                        method="post"
                    >
                        @csrf
                        <input
                            name="original_id"
                            type="hidden"
                            value="{{ $replacement->id }}"
                        >
                        <input
                            name="new_id"
                            type="hidden"
                            value="{{ $submission->id }}"
                        >
                        @foreach ($replacementOptions as $index => $replacementOption)
                            <div class="flex w-full items-center justify-start gap-3">
                                <input
                                    class="part-checkbox"
                                    name="{{ $index }}"
                                    type="checkbox"
                                >
                                <div class="flex items-center justify-start gap-3">
                                    <span>
                                        @if ($replacementOption['reason'] === 'QTY changed')
                                            ({{ \App\Models\Part::find($replacementOption['new'])->quantity }})
                                        @endif
                                        {{ \App\Models\Part::find($replacementOption['new'])->name }}
                                    </span>
                                    <x-icon.back class="h-5 w-5 min-w-fit rotate-180" />
                                    <span>
                                        @if ($replacementOption['reason'] === 'QTY changed')
                                            ({{ \App\Models\Part::find($replacementOption['original'])->quantity }})
                                        @endif
                                        {{ \App\Models\Part::find($replacementOption['original'])->name }}
                                    </span>
                                </div>
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
