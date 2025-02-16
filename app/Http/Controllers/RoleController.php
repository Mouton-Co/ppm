<?php

namespace App\Http\Controllers;

use App\Exports\RoleExport;
use App\Http\Services\RoleService;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected array $landingPages = ['Projects', 'Design', 'Procurement', 'Warehouse', 'Purchase Orders'];

    protected array $permissionTable = [
        'projects' => 'Projects',
        'design' => 'Design',
        'procurement' => 'Procurement',
        'warehouse' => 'Warehouse',
        'purchase_orders' => 'Purchase Orders',
        'users' => 'Users',
        'roles' => 'Roles',
        'suppliers' => 'Suppliers',
        'representatives' => 'Representatives',
        'autofill_suppliers' => 'Autofill Suppliers',
        'process_types' => 'Process Types',
        'project_statuses' => 'Project Statuses',
        'departments' => 'Departments',
        'email_triggers' => 'Email Triggers',
    ];

    protected array $permissionTableExcludes = ['create_procurement', 'delete_procurement', 'restore_procurement', 'force_delete_procurement', 'create_warehouse', 'delete_warehouse', 'restore_warehouse', 'force_delete_warehouse'];

    protected RoleService $service;

    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        $this->model = Role::class;
        $this->route = 'roles';
        $this->service = new RoleService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', Role::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('roles', Role::class);

        $roles = $this->filter(Role::class, Role::query(), $request)->paginate(15);

        if ($roles->currentPage() > 1 && $roles->lastPage() < $roles->currentPage()) {
            return redirect()->route('roles.index', array_merge(['page' => $roles->lastPage()], request()->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Roles',
            'table' => 'roles',
            'route' => 'roles',
            'indexRoute' => 'roles.index',
            'data' => $roles,
            'model' => Role::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Role::class)) {
            abort(403);
        }

        return view('roles.create', [
            'landingPages' => $this->landingPages,
            'permissionTable' => $this->permissionTable,
            'permissionTableExcludes' => $this->permissionTableExcludes,
            'machineNumbers' => Project::select('machine_nr')->distinct()->pluck('machine_nr'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Role::class)) {
            abort(403);
        }

        if ($request->has('customer') && $request->get('customer') == 'on') {
            Role::create([
                'role' => $request->get('role_customer') ?? 'N/A',
                'permissions' => json_encode($request->get('machine_numbers', [])),
                'customer' => true,
            ]);
        } else {
            Role::create([
                'role' => $request->get('role_standard') ?? 'N/A',
                'landing_page' => $request->get('landing_page') ?? 'Projects',
                'permissions' => json_encode($this->service->formatPermissionsFromRequest($request)),
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->user()->cannot('update', Role::class)) {
            abort(403);
        }

        $role = Role::findOrFail($id);

        return view('roles.edit', [
            'role' => $role,
            'landingPages' => $this->landingPages,
            'permissionTable' => $this->permissionTable,
            'permissionTableExcludes' => $this->permissionTableExcludes,
            'machineNumbers' => Project::select('machine_nr')->distinct()->pluck('machine_nr'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->user()->cannot('update', Role::class)) {
            abort(403);
        }

        $role = Role::findOrFail($id);

        if ($request->has('customer') && $request->get('customer') == 'on') {
            $role->update([
                'role' => $request->get('role_customer') ?? 'N/A',
                'permissions' => json_encode($request->get('machine_numbers', [])),
                'customer' => true,
            ]);
        } else {
            $role->update([
                'role' => $request->get('role_standard') ?? 'N/A',
                'landing_page' => $request->get('landing_page') ?? 'Projects',
                'permissions' => json_encode($this->service->formatPermissionsFromRequest($request)),
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if ($request->user()->cannot('delete', Role::class)) {
            abort(403);
        }

        Role::findOrFail($id)->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    /**
     * Export roles to excel
     *
     * @return \Maatwebsite\Excel\Excel
     */
    public function export(Request $request)
    {
        if ($request->user()->cannot('read', Role::class)) {
            abort(403);
        }

        return (new RoleExport($request))->download('roles.xlsx');
    }
}
