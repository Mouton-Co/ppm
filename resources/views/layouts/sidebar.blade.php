@php
    $current = !empty($current) ? $current : '';
@endphp
<div class="flex grow flex-col gap-y-3 overflow-y-auto bg-gray-900 px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center mt-5">
        <a href="{{ route('dashboard') }}">
            <img class="h-auto w-[6rem]" src="{{ asset('images/logo_notext.png') }}" alt="Your Company">
        </a>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="#" class="nav-item-default">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0
                                .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125
                                1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504
                                1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621
                                0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12
                                6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375
                                3.375 0 00-3.375-3.375H9.75" />
                            </svg>
                            {{ __('My submisions') }}
                        </a>
                    </li>
                    <li>
                        <a class="nav-item-{{ $current == 'new-submission' ? 'active' : 'default' }}"
                        href="{{ route('new.submission') }}">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0
                                014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0
                                00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25
                                2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                            </svg>
                            {{ __('New submission') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <div class="text-sm font-semibold leading-6 text-gray-400">{{ __("Administration") }}</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li>
                        <a href="#" class="nav-item-default">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0
                                002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15
                                19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0
                                018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0
                                0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25
                                2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span class="truncate">{{ __('Users') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
