<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStatus\StoreRequest;
use App\Http\Requests\ProjectStatus\UpdateRequest;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;

class ProjectStatusController extends Controller
{
    /**
     * ProjectStatusController constructor.
     */
    public function __construct()
    {
        $this->model = ProjectStatus::class;
        $this->route = 'project-statuses';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', ProjectStatus::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('project-statuses', ProjectStatus::class);
        $projectStatuses = $this->filter(ProjectStatus::class, ProjectStatus::query(), $request)->paginate(15);

        if ($projectStatuses->currentPage() > 1 && $projectStatuses->lastPage() < $projectStatuses->currentPage()) {
            return redirect()->route('project-statuses.index', array_merge(['page' => $projectStatuses->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Project Statuses',
            'table' => 'project-statuses',
            'route' => 'project-statuses',
            'indexRoute' => 'project-statuses.index',
            'data' => $projectStatuses,
            'model' => ProjectStatus::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', ProjectStatus::class)) {
            abort(403);
        }

        return view('project-status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', ProjectStatus::class)) {
            abort(403);
        }

        ProjectStatus::create($request->validated());

        return redirect()->route('project-statuses.index')->with('success', 'Status created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, ProjectStatus $projectStatus)
    {
        if ($request->user()->cannot('update', $projectStatus)) {
            abort(403);
        }

        return view('project-status.edit', [
            'projectStatus' => $projectStatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ProjectStatus $projectStatus)
    {
        if ($request->user()->cannot('update', $projectStatus)) {
            abort(403);
        }

        $projectStatus->update($request->validated());

        return redirect()->route('project-statuses.index')->with('success', 'Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ProjectStatus $projectStatus)
    {
        if ($request->user()->cannot('delete', $projectStatus)) {
            abort(403);
        }
        
        $projectStatus->delete();

        return redirect()->back()->with('success', 'Status deleted successfully');
    }
}
