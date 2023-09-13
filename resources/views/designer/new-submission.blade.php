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
    <a href="{{ route('submissions.index') }}" class="btn btn-sky max-w-fit mb-5">
        <svg class="w-4 mr-2 aspect-square" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"></path>
                <path fill="currentColor"
                    d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32
                0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z">
                </path>
            </g>
        </svg>
        {{ __('All submission') }}
    </a>

    <div class="flex flex-wrap">
        <h2 class="w-full md:w-auto">{{ __('Adding new submission') }}</h2>
        <h2 class="mx-2 hidden md:block">-</h2>
        <h2 class="w-full md:w-auto">{{ $submission->submission_code }}</h2>
    </div>

    @include('components.error-message')

    <div class="card">
        @include('designer.submission-form')

        <div class="block md:flex gap-3 w-full justify-between">
            <div class="w-full mb-5 md:mb-0">
                <label class="label-dark">{{ __('Submission files') }}</label>
                <form action="{{ route('designer.upload') }}" method="POST"
                enctype="multipart/form-data" id="file-upload" class="dropzone flex flex-col !min-h-[155px]">
                    @csrf
                    <input type="hidden" name="submission_code" value="{{ $submission->submission_code }}">
                </form>
            </div>

            <div class="w-full">
                <label class="label-dark !flex gap-3">
                    {{ __('Feedback') }}
                    <svg class="h-5 cursor-pointer hover:text-gray-400" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg" id="refresh-feedback">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier"><path d="M19.9381 13C19.979 12.6724 20 12.3387
                            20 12C20 7.58172 16.4183 4 12 4C9.49942 4 7.26681 5.14727
                            5.7998 6.94416M4.06189 11C4.02104 11.3276 4 11.6613 4 12C4
                            16.4183 7.58172 20 12 20C14.3894 20 16.5341 18.9525 18
                            17.2916M15 17H18V17.2916M5.7998 4V6.94416M5.7998 6.94416V6.99993L8.7998
                            7M18 20V17.2916" stroke="currentColor" stroke-width="1.512" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </label>
                <div id="submission-feedback" class="field-card min-h-[155px]">
                    <hr class='my-2'>
                    <h3 class='flex items-center gap-2 h-[58px]'>
                        {{ __('Waiting on Excel sheet') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="pb-12 mt-10 flex justify-end mx-auto">
            <button type="button" id="submit-button" disabled class="btn-disabled gap-2 max-w-none md:max-w-fit !px-10">
                Submit
                <svg class="hidden w-5 h-auto" id="dots" width="132px" height="58px" viewBox="0 0 132 58"
                    version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
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
