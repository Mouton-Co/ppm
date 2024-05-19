<div
    {{ $attributes->merge([
        'class' =>
            'text-wrap bg-dark-field border-dark-field-border fixed left-1/2 top-2/3 z-50 hidden w-full max-w-sm -translate-x-1/2 -translate-y-1/2 rounded border px-5 py-3 text-center text-white shadow',
    ]) }}>
    {{ $slot }}
</div>
