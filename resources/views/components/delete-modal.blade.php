<div id="delete-modal-{{ $model->id }}" class="hidden relative z-30" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">

    <div id="delete-modal-curtain-{{ $model->id }}" class="opacity-0 inset-0 bg-gray-900/80 z-40 curtain-closed">
    </div>

    <div
        class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0 z-50
        fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div id="delete-modal-popup-{{ $model->id }}" class="field-card modal-close max-w-[540px]">
            <div class="flex w-full gap-3">
                <x-icon.warning class="text-red-500 w-7 h-7" />
                <h2> {{ __('Are you sure?') }} </h2>
            </div>
            <p class="text-sm text-gray-400 mt-2 text-left text-wrap">
                {{ $message ?? __('Are you sure you want to delete this record?') }}
            </p>
            <form class="flex gap-3 mt-5 sm:mt-6" action="{{ route("$route.destroy", $model->id) }}" method="post">
                @csrf
                @if (!empty($method))
                    @method($method)
                @endif
                <input class="btn-sky" type="submit" value="{{ __('Delete') }}">
                <button id="delete-modal-cancel-{{ $model->id }}" type="button" class="btn-sky-light">
                    {{ __('Cancel') }}
                </button>
            </form>
        </div>
    </div>
</div>
