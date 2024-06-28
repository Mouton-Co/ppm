<div
    class="fixed top-0 left-0 z-50 hidden"
    id="link-submission-modal"
>
    <div
        class="fixed left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center p-4 text-center sm:items-center sm:p-0">
        <div
            class="field-card modal-closed w-96"
        >
            <div
                class="flex w-full justify-between items-center mb-3"
                id="link-submission-popup"
            >
                <h2> {{ __('Link submission') }} </h2>
                <a
                    id="create-new-submission-button"
                    class="btn btn-sky !max-h-fit max-w-fit !rounded !py-1 !text-xs"
                    href="#"
                >
                    {{ __('Create new') }}
                </a>
            </div>
            <select
                name="submission_id"
                class="field-dark cursor-pointer mb-3"
                id="link-submission-select"
            >
                @foreach ($availableSubmissions as $submission)
                    <option value="{{ $submission->id }}">
                        {{ $submission->submission_code }}
                    </option>
                @endforeach
            </select>
            <div class="w-full flex justify-between gap-3 items-center">
                <button
                    class="w-full h-7 rounded border border-sky-600 px-2 py-1 text-sm text-sky-600 shadow hover:border-sky-700 hover:bg-sky-700 hover:text-white"
                    id="cancel-link-submission-button"
                >
                    {{ __('Cancel') }}
                </button>
                <button
                    class="w-full h-7 rounded border border-sky-700 bg-sky-700 px-2 py-1 text-sm text-white shadow hover:border-sky-600 hover:bg-sky-600"
                    id="link-submission-button"
                    project-id=""
                >
                    {{ __('Link') }}
                </button>
            </div>
        </div>
    </div>
</div>

@include('components.confirmation-modal')
