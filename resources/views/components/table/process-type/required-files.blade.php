<ul class="list-disc pl-3">
    @foreach (explode(',', $datum->required_files) as $file)
        <li>
            @if (str_contains($file, '/'))
                @php
                    [$file_1, $file_2] = explode('/', $file);
                @endphp
                <div class="flex translate-y-1 items-center">
                    <img
                        class="inline-block h-5 w-5"
                        src="{{ asset('images/' . strtolower($file_1) . '.png') }}"
                        alt="None"
                    >
                    {{ $file_1 }}
                    <div class="px-2">OR</div>
                    <img
                        class="inline-block h-5 w-5"
                        src="{{ asset('images/' . strtolower($file_2) . '.png') }}"
                        alt="None"
                    >
                    {{ $file_2 }}
                </div>
            @else
                <img
                    class="inline-block h-5 w-5"
                    src="{{ asset('images/' . strtolower($file) . '.png') }}"
                    alt="None"
                >
                {{ $file }}
            @endif
        </li>
    @endforeach
</ul>
