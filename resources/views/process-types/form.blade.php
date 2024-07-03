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

<label class="label-dark">{{ __('Required Files') }}</label>
<ul class="list-disc text-gray-300 pl-5">
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[pdf]" id="pdf"
            @if (
                (!empty($processType->required_files) && in_array('PDF', explode(',', $processType->required_files))) ||
                old('required_files.pdf') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('pdf'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/pdf.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">PDF</div>
            <div class="strikethrough {{ !empty($processType) && $processType->isDisabled('pdf') ? '' : 'hidden' }}"
            id="pdf-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[dwg]" id="dwg"
            @if (
                (!empty($processType->required_files) && in_array('DWG', explode(',', $processType->required_files)))
                || old('required_files.dwg') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('dwg'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DWG</div>
            <div class="strikethrough {{ !empty($processType) && $processType->isDisabled('dwg') ? '' : 'hidden' }}"
            id="dwg-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[dxf]" id="dxf"
            @if (
                (!empty($processType->required_files) && in_array('DXF', explode(',', $processType->required_files)))
                || old('required_files.dxf') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('dxf'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DXF</div>
            <div class="strikethrough {{ !empty($processType) && $processType->isDisabled('dxf') ? '' : 'hidden' }}"
            id="dxf-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[step]" id="step"
            @if (
                (!empty($processType->required_files) && in_array('STEP', explode(',', $processType->required_files)))
                || old('required_files.step') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('step'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/step.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">STEP</div>
            <div class="strikethrough {{ !empty($processType) && $processType->isDisabled('step') ? '' : 'hidden' }}"
            id="step-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[pdfOrDwg]" id="pdfOrDwg"
            @if (
                (
                    !empty($processType->required_files) &&
                    in_array('PDF/DWG', explode(',', $processType->required_files))
                ) ||
                old('required_files.pdfOrDwg') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('pdfOrDwg'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/pdf.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">PDF</div>
            <div class="h-full text-gray-300 text-sm px-1">OR</div>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DWG</div>
            <div class="strikethrough {{
                !empty($processType) && $processType->isDisabled('pdfOrDwg') ? '' : 'hidden'
            }}" id="pdfOrDwg-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[pdfOrStep]" id="pdfOrStep"
            @if (
                (
                    !empty($processType->required_files) &&
                    in_array('PDF/STEP', explode(',', $processType->required_files))
                ) ||
                old('required_files.pdfOrStep') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('pdfOrStep'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/pdf.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">PDF</div>
            <div class="h-full text-gray-300 text-sm px-1">OR</div>
            <img src="{{ asset('images/step.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">STEP</div>
            <div class="strikethrough {{
                !empty($processType) && $processType->isDisabled('pdfOrStep') ? '' : 'hidden'
            }}" id="pdfOrStep-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[pdfOrDxf]" id="pdfOrDxf"
            @if (
                (
                    !empty($processType->required_files) &&
                    in_array('PDF/DXF', explode(',', $processType->required_files))
                ) ||
                old('required_files.pdfOrDxf') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('pdfOrDxf'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/pdf.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">PDF</div>
            <div class="h-full text-gray-300 text-sm px-1">OR</div>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DXF</div>
            <div class="strikethrough {{
                !empty($processType) && $processType->isDisabled('pdfOrDxf') ? '' : 'hidden'
            }}" id="pdfOrDxf-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[dwgOrStep]" id="dwgOrStep"
            @if (
                (
                    !empty($processType->required_files) &&
                    in_array('DWG/STEP', explode(',', $processType->required_files))
                ) ||
                old('required_files.dwgOrStep') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('dwgOrStep'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DWG</div>
            <div class="h-full text-gray-300 text-sm px-1">OR</div>
            <img src="{{ asset('images/step.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">STEP</div>
            <div class="strikethrough {{
                !empty($processType) && $processType->isDisabled('dwgOrStep') ? '' : 'hidden'
            }}" id="dwgOrStep-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[dwgOrDxf]" id="dwgOrDxf"
            @if (
                (
                    !empty($processType->required_files) &&
                    in_array('DWG/DXF', explode(',', $processType->required_files))
                ) ||
                old('required_files.dwgOrDxf') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('dwgOrDxf'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DWG</div>
            <div class="h-full text-gray-300 text-sm px-1">OR</div>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DXF</div>
            <div class="strikethrough {{
                !empty($processType) && $processType->isDisabled('dwgOrDxf') ? '' : 'hidden'
            }}" id="dwgOrDxf-strikethrough"></div>
        </div>
    </li>
    <li>
        <div class="flex items-center h-5 gap-1 translate-y-1 relative w-fit">
            <input type="checkbox" name="required_files[stepOrDxf]" id="stepOrDxf"
            @if (
                (
                    !empty($processType->required_files) &&
                    in_array('STEP/DXF', explode(',', $processType->required_files))
                ) ||
                old('required_files.stepOrDxf') == 'on'
            )
                checked
            @endif
            @if (!empty($processType) && $processType->isDisabled('stepOrDxf'))
                disabled class="bg-gray-300"
            @endif>
            <img src="{{ asset('images/step.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">STEP</div>
            <div class="h-full text-gray-300 text-sm px-1">OR</div>
            <img src="{{ asset('images/dwg.png') }}" alt="None" class="w-5 h-5">
            <div class="h-full text-gray-300 text-sm">DXF</div>
            <div class="strikethrough {{
                !empty($processType) && $processType->isDisabled('stepOrDxf') ? '' : 'hidden'
            }}" id="stepOrDxf-strikethrough"></div>
        </div>
    </li>
</ul>
@if (!empty($errors->get('required_files')))
    @foreach ($errors->get('required_files') as $error)
        @include('components.error-message', ['error' => $error, 'hidden' => 'false', 'class' => 'mb-5'])
    @endforeach
@endif
