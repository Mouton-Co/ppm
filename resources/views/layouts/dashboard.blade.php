@extends('layouts.dark')

@section('html-class')
    bg-gray-800
@endsection

@section('body-class')
    z-10
@endsection

@section('content')
    <div>
        {{-- mobile sidebar --}}
        <div id="mobile-nav" role="dialog" aria-modal="true" class="-z-10 relative">
            <div id="curtain" class="opacity-0 fixed inset-0 bg-gray-900/80"></div>
            <div class="fixed inset-0 flex">
                <div id="side-panel" class="-translate-x-full relative mr-16 flex w-full max-w-xs flex-1">
                    <div id="close-button" class="opacity-0 absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" class="-m-2.5 p-2.5" id="close-menu">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @include('layouts.sidebar')
                </div>
            </div>
        </div>

        {{-- desktop sidebar --}}
        <div class="shadow-lg hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-72 lg:flex-col">
            @include('layouts.sidebar')
        </div>

        <div class="lg:pl-">
            <div
                class="sticky top-0 z-30 flex justify-between lg:justify-end h-16 shrink-0 items-center gap-x-4
            border-b border-gray-900 shadow-md bg-gray-800 px-4 sm:gap-x-6 sm:px-6 lg:px-8">

                <button id="open-menu" type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgb(156, 163, 175)"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="relative">
                    <button type="button" class="-m-1.5 flex items-center p-1.5" id="user-menu-button"
                        aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <span class="inline-block h-9 w-9 overflow-hidden rounded-full bg-gray-100">
                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354
                                11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                        <span class="flex items-center">
                            <span class="nav-item-default hover:text-gray-400 ml-2" aria-hidden="true">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75
                                0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    <div class="hidden transform opacity-0 scale-95 absolute right-0 mt-2.5 w-32 origin-top-right
                    rounded-md bg-dark-field shadow-md ring-1 ring-dark-field-border focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                        id="user-menu">
                        <a href="{{ route('user.edit', auth()->user()->id) }}"
                            class="dropdown-item-top
                            !my-0 !text-nav-link hover:!text-white"
                            role="menuitem" tabindex="-1" id="user-menu-item-0">{{ __('Your profile') }}</a>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="dropdown-item-bottom !my-0 !text-nav-link hover:!text-white" type="submit">
                                {{ __('Sign out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <main class="py-5 px-5 md:px-10 lg:pl-[328px]">
                @if (session('success'))
                    @include('components.success-message', ['success' => session('success')])
                @endif
                @if (session('error'))
                    @include('components.error-message', ['error' => session('error')])
                @endif
                @yield('dashboard-content')
            </main>
        </div>
    </div>
@endsection
