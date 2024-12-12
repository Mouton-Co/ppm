@php
    $current = !empty($current) ? $current : '';
@endphp
<div class="z-50 flex grow flex-col gap-y-3 overflow-y-auto bg-gray-900 px-6 pb-4">
    <div class="mt-5 flex h-16 shrink-0 items-center">
        <a href="{{ route('dashboard') }}">
            <img
                class="h-auto w-[6rem]"
                src="{{ asset('images/logo_notext.png') }}"
                alt="Your Company"
            >
        </a>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul class="-mx-2 space-y-1">
                    @if (auth()->user()->can('read', App\Models\Project::class) || auth()->user()->role->customer)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'projects' ? 'active' : 'default' }}"
                                href="{{ route('projects.index', [
                                    'order_by' => 'coc',
                                    'order' => 'asc',
                                    'status' => 'All except closed',
                                ]) }}"
                            >
                                <x-icon.project class="h-6 w-6 shrink-0" />
                                {{ __('Projects') }}
                            </a>
                        </li>
                    @endif
                    @can('read', App\Models\Submission::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'submisssions' ? 'active' : 'default' }}"
                                href="{{ route('submissions.index') }}"
                            >
                                <x-icon.submission class="h-6 w-6 shrink-0" />
                                {{ __('Design') }}
                            </a>
                        </li>
                    @endcan
                    @if (auth()->user()->role->hasPermission('read_procurement'))
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(2) == 'procurement' ? 'active' : 'default' }}"
                                href="{{ route('parts.procurement.index', ['status' => 'processing', 'redundant' => '0']) }}"
                            >
                                <x-icon.procurement class="h-6 w-6 shrink-0" />
                                {{ __('Procurement') }}
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->role->hasPermission('read_warehouse'))
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(2) == 'warehouse' ? 'active' : 'default' }}"
                                href="{{ route('parts.warehouse.index', ['status' => 'supplier', 'redundant' => '0']) }}"
                            >
                                <x-icon.warehouse class="h-6 w-6 shrink-0" />
                                {{ __('Warehouse') }}
                            </a>
                        </li>
                    @endif
                    @can('read', App\Models\Order::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'orders' ? 'active' : 'default' }}"
                                href="{{ route('orders.index', [
                                    'status' => 'All except ordered',
                                ]) }}"
                            >
                                <x-icon.order class="h-6 w-6 shrink-0" />
                                {{ __('Purchase Orders') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            <li>
                @if (auth()->user()->role->hasPermission('read_users') ||
                        auth()->user()->role->hasPermission('read_roles') ||
                        auth()->user()->role->hasPermission('read_suppliers') ||
                        auth()->user()->role->hasPermission('read_representatives') ||
                        auth()->user()->role->hasPermission('read_autofill_suppliers') ||
                        auth()->user()->role->hasPermission('read_process_types') ||
                        auth()->user()->role->hasPermission('read_project_statuses'))
                    <div class="text-sm font-semibold leading-6 text-gray-400">{{ __('Administration') }}</div>
                @endif
                <ul class="-mx-2 mt-2 space-y-1">
                    @can('read', App\Models\User::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'users' ? 'active' : 'default' }}"
                                href="{{ route('user.index') }}"
                            >
                                <svg
                                    class="h-6 w-6 shrink-0"
                                    aria-hidden="true"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0
                                        002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15
                                        19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0
                                        018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0
                                        0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25
                                        2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                    />
                                </svg>
                                <span class="truncate">{{ __('Users') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\Role::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'roles' ? 'active' : 'default' }}"
                                href="{{ route('roles.index') }}"
                            >
                                <x-icon.role class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Roles') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\Supplier::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'suppliers' ? 'active' : 'default' }}"
                                href="{{ route('suppliers.index') }}"
                            >
                                <x-icon.suitcase class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Suppliers') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\Representative::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'representatives' ? 'active' : 'default' }}"
                                href="{{ route('representatives.index') }}"
                            >
                                <x-icon.representative class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Representatives') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\AutofillSupplier::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'autofill-suppliers' ? 'active' : 'default' }}"
                                href="{{ route('autofill-suppliers.index') }}"
                            >
                                <x-icon.vlookup class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Autofill Suppliers') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\ProcessType::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'process-types' ? 'active' : 'default' }}"
                                href="{{ route('process-types.index') }}"
                            >
                                <x-icon.process-type class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Process Types') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\ProjectStatus::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'project-statuses' ? 'active' : 'default' }}"
                                href="{{ route('project-statuses.index') }}"
                            >
                                <x-icon.statuses class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Project statuses') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\ProjectResponsibles::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'project-responsibles' ? 'active' : 'default' }}"
                                href="{{ route('project-responsibles.index') }}"
                            >
                                <svg
                                    class="h-6 w-6 shrink-0"
                                    aria-hidden="true"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0
                                        002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15
                                        19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0
                                        018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0
                                        0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25
                                        2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                    />
                                </svg>
                                <span class="truncate">{{ __('Departments') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('read', App\Models\RecipientGroup::class)
                        <li>
                            <a
                                class="nav-item-{{ request()->segment(1) == 'recipient-groups' ? 'active' : 'default' }}"
                                href="{{ route('recipient-groups.index') }}"
                            >
                                <x-icon.email class="h-6 w-6 shrink-0" />
                                <span class="truncate">{{ __('Email triggers') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        </ul>
    </nav>
</div>
