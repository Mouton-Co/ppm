<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Services\RoleService;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @var array
     */
    protected array $landingPages = ['Projects', 'Design', 'Procurement', 'Warehouse', 'Purchase Orders'];

    /**
     * @var array
     */
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

    /**
     * @var array
     */
    protected array $permissionTableExcludes = ['create_procurement', 'delete_procurement', 'restore_procurement', 'force_delete_procurement', 'create_warehouse', 'delete_warehouse', 'restore_warehouse', 'force_delete_warehouse'];

    /**
     * @var RoleService
     */
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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', Role::class)) {
            abort(403);
        }

        Role::create(array_merge($request->validated(), ['permissions' => json_encode($this->service->formatPermissionsFromRequest($request))]));

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
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        if ($request->user()->cannot('update', Role::class)) {
            abort(403);
        }

        $role = Role::findOrFail($id);

        $role->update(array_merge($request->validated(), ['permissions' => json_encode($this->service->formatPermissionsFromRequest($request))]));

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
}
