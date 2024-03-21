@php
    $current = !empty($current) ? $current : '';
@endphp
<div class="flex grow flex-col gap-y-3 overflow-y-auto bg-gray-900 px-6 pb-4 z-50">
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
                        <a class="nav-item-{{ request()->segment(1) == 'projects' ? 'active' : 'default' }}"
                        href="{{ route('projects.index') }}">
                            <x-icon.project class="h-6 w-6 shrink-0" />
                            {{ __('Projects') }}
                        </a>
                    </li>
                    <li>
                        <a class="nav-item-{{ request()->segment(1) == 'submisssions' ? 'active' : 'default' }}"
                        href="{{ route('submissions.index') }}">
                            <x-icon.submission class="h-6 w-6 shrink-0" />
                            {{ __('Design') }}
                        </a>
                    </li>
                    <li>
                        <a class="nav-item-{{ request()->segment(2) == 'procurement' ? 'active' : 'default' }}"
                        href="{{ route('parts.procurement.index', ['status' => 'processing']) }}">
                            <x-icon.procurement class="h-6 w-6 shrink-0" />
                            {{ __('Procurement') }}
                        </a>
                    </li>
                    <li>
                        <a class="nav-item-{{ request()->segment(2) == 'warehouse' ? 'active' : 'default' }}"
                        href="{{ route('parts.warehouse.index', ['status' => 'supplier']) }}">
                            <x-icon.warehouse class="h-6 w-6 shrink-0" />
                            {{ __('Warehouse') }}
                        </a>
                    </li>
                    <li>
                        <a class="nav-item-{{ request()->segment(1) == 'orders' ? 'active' : 'default' }}"
                        href="{{ route('orders.index') }}">
                            <x-icon.order class="h-6 w-6 shrink-0" />
                            {{ __('Purchase Orders') }}
                        </a>
                    </li>
                </ul>
            </li>
            @if (auth()->user()->role->role == 'Admin')
                <li>
                    <div class="text-sm font-semibold leading-6 text-gray-400">{{ __("Administration") }}</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a class="nav-item-{{ request()->segment(1) == 'users' ? 'active' : 'default' }}"
                            href="{{ route('user.index') }}">
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
                        <li>
                            <a class="nav-item-{{ request()->segment(1) == 'suppliers' ? 'active' : 'default' }}"
                            href="{{ route('suppliers.index') }}">
                                <x-icon.suitcase class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Suppliers') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-item-{{ request()->segment(1) == 'representatives' ? 'active' : 'default' }}"
                            href="{{ route('representatives.index') }}">
                                <x-icon.representative class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Representatives') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('autofill-suppliers.index') }}"
                            class="nav-item-{{ request()->segment(1) == 'autofill-suppliers' ? 'active' : 'default' }}">
                                <x-icon.vlookup class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Autofill Suppliers') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('process-types.index') }}"
                            class="nav-item-{{ request()->segment(1) == 'process-types' ? 'active' : 'default' }}">
                                <x-icon.process-type class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Process Types') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('project-statuses.index') }}"
                            class="nav-item-{{ request()->segment(1) == 'project-statuses' ? 'active' : 'default' }}">
                                <x-icon.statuses class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Project statuses') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</div>
