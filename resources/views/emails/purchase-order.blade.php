@extends('layouts.dashboard')

@section('custom-scripts')
    <x-head.tinymce-config :value="$body"/>
@endsection

@section('dashboard-content')

    {{-- title --}}
    <div class="flex mb-3 items-center gap-3 flex-wrap">
        <a href="{{ route('orders.index', $request->all()) }}" class="btn-sky max-w-fit">
            <span aria-hidden="true">&larr;</span>
            <span>{{ __('Back to orders') }}</span>
        </a>

        <h2>{{ __('Preparing email for ') . $order->po_number }}</h2>
    </div>

    {{-- index table --}}
    <form action="{{ route('email.purchase-order.send', array_merge($request->all(), ['id' => $order->id])) }}"
        method="post" class="flex flex-col gap-3">
        @csrf

        <hr>

        <div class="flex gap-3 items-center">
            <label class="min-w-[100px] text-white">{{ __('To') }}</label>
            <input type="email" name="to" placeholder="johndoe@gmail.com" class="field-dark max-w-full"
            value="{{ $to ?? '' }}">
        </div>

        <hr>

        <div class="flex gap-3 items-center">
            <label class="min-w-[100px] text-white">{{ __('cc') }}</label>
            <input type="text" name="cc" class="field-dark max-w-full"
            value="{{ $cc ?? '' }}">
        </div>

        <hr>

        <div class="flex gap-3 items-center">
            <label class="min-w-[100px] text-white">{{ __('Subject') }}</label>
            <input type="text" name="subject" placeholder="Subject..." class="field-dark max-w-full"
            value="{{ __('PPM PO NUMBER: ') . $order->po_number }}">
        </div>

        <hr>

        <div class="flex gap-3 items-start">
            <label class="min-w-[100px] text-white">{{ __('Body') }}</label>
            <x-forms.tinymce-editor name="body" />
        </div>

        <div class="flex gap-3 items-start">
            <label class="min-w-[100px] text-white">{{ __('Attachments') }}</label>
            <div>
                @foreach ($order->parts()->get() as $part)
                    @foreach ($part->files as $file)
                        <div class="flex gap-3 items-center">
                            @if ($file->file_type == 'stp')
                                <img class="h-6" src="{{ asset('images/step.png') }}"
                                alt="No image found">
                            @else
                                <img class="h-6" src="{{ asset('images/' . $file->file_type . '.png') }}"
                                alt="No image found">
                            @endif
                            <span class="text-white">
                                {{ $file->name . '.' . $file->file_type . ' (' . $file->size . ')'  }}
                            </span>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
        <hr>
        
        <div class="flex w-full justify-end gap-3">
            <button type="submit" class="btn-sky max-w-fit">{{ __('Send') }}</button>
        </div>
    </form>
    
@endsection
