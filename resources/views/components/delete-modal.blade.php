<div id="delete-modal-{{ $user->id }}" class="hidden relative z-50" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    
    <div id="delete-modal-curtain-{{ $user->id }}" class="fixed inset-0 bg-gray-500 bg-opacity-75
        transition-opacity curtain-closed"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="delete-modal-popup-{{ $user->id }}" class="field-card modal-close">
                <div>
                    <div class="text-center">
                        <h2>
                            {{ __('Are you sure?') }}
                        </h2>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">
                                {{ __('Are you sure you want to delete user account '.$user->email.'?') }}
                            </p>
                        </div>
                    </div>
                </div>
                <form class="flex gap-3 mt-5 sm:mt-6" action="{{ route('user.destroy', $user->id) }}" method="post">
                    @csrf
                    <input class="btn-sky" type="submit" value="{{__('Delete')}}">
                    <button id="delete-modal-cancel-{{ $user->id }}" type="button" class="btn-sky-light">
                        {{ __('Cancel') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
