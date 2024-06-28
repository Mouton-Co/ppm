<div
    class="fixed top-0 left-0 z-50 hidden"
    id="confirmation-modal"
    aria-labelledby="modal-title"
    aria-modal="true"
    datum-id=""
>
    <div
        class="fixed left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center p-4 text-center sm:items-center sm:p-0">
        <div
            class="field-card modal-close max-w-[540px]"
            id="confirmation-modal-popup"
        >
            <div class="flex w-full gap-3">
                <x-icon.warning class="h-7 w-7 text-red-500" />
                <h2> {{ __('Send notification?') }} </h2>
            </div>
            <p class="text-wrap mt-2 text-left text-sm text-gray-400">
                {{ __("Would you like to send an email notification of this update?") }}
            </p>
            <div class="flex items-center justify-between gap-3 mt-2">
                <button
                    id="confirmation-modal-yes"
                    class="btn-sky"
                >
                    {{ __('Yes') }}
                </button>
                <button
                    id="confirmation-modal-no"
                    class="btn-sky-light"
                >
                    {{ __('No') }}
                </button>
            </div>
        </div>
    </div>
</div>
