@extends('layouts.dashboard')

@section('custom-scripts')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @vite(['resources/js/designer/dropzone.js'])
    <script type="text/javascript">
        let pdf_img = "{{ asset('images/pdf.png') }}";
        let excel_img = "{{ asset('images/excel.png') }}";
        let dwg_img = "{{ asset('images/dwg.png') }}";
        let step_img = "{{ asset('images/step.png') }}";
        let upload_img = "{{ asset('images/upload.png') }}";
        let delete_img = "{{ asset('images/delete.png') }}";
        let green_tick = "{{ asset('images/green-tick.png') }}";
        let submission_code = "{{ $submission->submission_code }}";
    </script>
@endsection

@section('dashboard-content')
    <div class="flex flex-wrap">
        <h2 class="w-full md:w-auto">{{ __('Adding new submission') }}</h2>
        <h2 class="mx-2 hidden md:block">-</h2>
        <h2 class="w-full md:w-auto">{{ $submission->submission_code }}</h2>
    </div>

    @include('components.error-message')

    <div class="card">
        @include('designer.submission-form')

        <label class="label-dark">{{ __('Submission files') }}</label>
        <form action="{{ route('designer.upload') }}" method="POST" enctype="multipart/form-data" id="file-upload"
            class="dropzone flex flex-col">
            @csrf
        </form>

        <hr id="submission-line" class="hidden my-12">
        <div id="submission-feedback" class="hidden field-card"></div>

        <div class="pb-12 mt-10 flex justify-end mx-auto">
            <button type="button" id="submit-button" disabled class="btn-disabled gap-2 max-w-none md:max-w-fit !px-10">
                Submit
                <svg class="hidden w-5 h-auto" id="dots" width="132px" height="58px" viewBox="0 0 132 58" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                    <title>dots</title>
                    <desc>Created with Sketch.</desc>
                    <defs></defs>
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                        sketch:type="MSPage">
                        <g id="dots" sketch:type="MSArtboardGroup" fill="#A3A3A3">
                            <circle id="dot1" sketch:type="MSShapeGroup" cx="25" cy="30" r="13">
                            </circle>
                            <circle id="dot2" sketch:type="MSShapeGroup" cx="65" cy="30" r="13">
                            </circle>
                            <circle id="dot3" sketch:type="MSShapeGroup" cx="105" cy="30" r="13">
                            </circle>
                        </g>
                    </g>
                </svg>
            </button>
        </div>
    </div>
@endsection
