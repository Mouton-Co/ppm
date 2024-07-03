@if (!empty($edit) && $edit)
    <input type="hidden" name="process_type_id" value="{{ $processType->id ?? '' }}">
@endif

<label class="label-dark" for="process_type">{{ __('Process type') }}</label>
<input type="text" id="process_type" name="process_type" required
    class="{{ !empty($errors->get('process_type')) ? 'field-error' : 'field-dark' }} mb-5"
    value="{{ $processType->process_type ?? old('process_type') }}">
@if (!empty($errors->get('process_type')))
    @foreach ($errors->get('process_type') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif

<label for="required_files" class="label-dark">{{ __('Required Files') }}</label>
<ul class="list-disc text-gray-300 pl-5">
    @php
        $options = [
            'PDF',
            'DWG',
            'DXF',
            'STEP',
            'PDFORDWG',
            'PDFORSTEP',
            'PDFORDXF',
            'DWGORSTEP',
            'DWGORDXF',
            'STEPORDXF',
        ];
    @endphp

    @foreach ($options as $option)
        <li class="process-type-option">
            <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
                <input
                    type="checkbox"
                    name="required_files[{{ strtolower($option) }}]"
                    id="{{ strtolower($option) }}"
                    @if (
                        (!empty($processType->required_files) && in_array($option, explode(',', $processType->required_files))) ||
                        old('required_files.' . strtolower($option)) == 'on'
                    )
                        checked
                    @endif
                    @if (!empty($processType) && $processType->isDisabled(strtolower($option)))
                        disabled class="bg-gray-300"
                    @endif
                >
                @if (str_contains($option, 'OR'))
                    @php
                        $files = explode('OR', $option);
                    @endphp
                    <img src="{{ asset('images/' . strtolower($files[0]) . '.png') }}" alt="None" class="w-5 h-5">
                    <div class="h-full text-gray-300 text-sm">{{ $files[0] }}</div>
                    <div class="h-full text-gray-300 text-sm px-1">OR</div>
                    <img src="{{ asset('images/' . strtolower($files[1]) . '.png') }}" alt="None" class="w-5 h-5">
                    <div class="h-full text-gray-300 text-sm">{{ $files[1] }}</div>
                    <div class="strikethrough {{ !empty($processType) && $processType->isDisabled(strtolower($option)) ? '' : 'hidden' }}"
                        id="{{ strtolower($option) }}-strikethrough"></div>
                @else
                    <img src="{{ asset('images/' . strtolower($option) . '.png') }}" alt="None" class="w-5 h-5">
                    <div class="h-full text-gray-300 text-sm">{{ $option }}</div>
                    <div class="strikethrough {{ !empty($processType) && $processType->isDisabled(strtolower($option)) ? '' : 'hidden' }}"
                    id="{{ strtolower($option) }}-strikethrough"></div>
                @endif
            </div>
        </li>
    @endforeach
</ul>

@if (!empty($errors->get('required_files')))
    @foreach ($errors->get('required_files') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif
