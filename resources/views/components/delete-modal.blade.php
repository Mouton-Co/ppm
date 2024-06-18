<div
    class="fixed top-0 left-0 z-50 hidden"
    id="delete-modal-{{ $model->id }}"
    aria-labelledby="modal-title"
    aria-modal="true"
>
    <div
        class="fixed left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center p-4 text-center sm:items-center sm:p-0">
        <div
            class="field-card modal-close max-w-[540px]"
            id="delete-modal-popup-{{ $model->id }}"
        >
            <div class="flex w-full gap-3">
                <x-icon.warning class="h-7 w-7 text-red-500" />
                <h2> {{ __('Are you sure?') }} </h2>
            </div>
            <p class="text-wrap mt-2 text-left text-sm text-gray-400">
                {{ $message ?? __('Are you sure you want to delete this record?') }}
            </p>
            <form
                class="mt-5 flex gap-3 sm:mt-6"
                action="{{ route("$route.destroy", $model->id) }}"
                method="post"
            >
                @csrf
                @if (!empty($method))
                    @method($method)
                @endif
                <input
                    class="btn-sky"
                    type="submit"
                    value="{{ __('Delete') }}"
                >
                <button
                    class="btn-sky-light"
                    id="delete-modal-cancel-{{ $model->id }}"
                    type="button"
                >
                    {{ __('Cancel') }}
                </button>
            </form>
        </div>
    </div>
</div>
