<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreRequest;
use App\Models\Project;
use App\Models\ProjectResponsible;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::with(['submission']);

        if (request()->has('search')) {
            $projects->where('machine_nr', 'like', '%' . request('search') . '%')
                ->orWhere('country', 'like', '%' . request('search') . '%')
                ->orWhere('coc', 'like', '%' . request('search') . '%')
                ->orWhere('noticed_issue', 'like', '%' . request('search') . '%')
                ->orWhere('proposed_solution', 'like', '%' . request('search') . '%')
                ->orWhere('currently_responsible', 'like', '%' . request('search') . '%')
                ->orWhere('status', 'like', '%' . request('search') . '%')
                ->orWhere('resolved_at', 'like', '%' . request('search') . '%')
                ->orWhere('related_po', 'like', '%' . request('search') . '%')
                ->orWhere('customer_comment', 'like', '%' . request('search') . '%')
                ->orWhere('commisioner_comment', 'like', '%' . request('search') . '%')
                ->orWhere('logistics_comment', 'like', '%' . request('search') . '%')
                ->orWhereHas('submission', function ($query) use ($request) {
                    $query->where('submission_code', 'like', "%$request->search%");
                });
        }

        return view('project.index', [
            'projects' => $projects->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = ProjectStatus::orderBy('name')->get();
        $responsibles = ProjectResponsible::orderBy('name')->get();

        return view('project.create', [
            'statuses' => $statuses,
            'responsibles' => $responsibles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        Project::create($request->validated());

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $statuses = ProjectStatus::orderBy('name')->get();
        $responsibles = ProjectResponsible::orderBy('name')->get();

        return view('project.edit', [
            'project' => $project,
            'statuses' => $statuses,
            'responsibles' => $responsibles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()->back()->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->back()->with('success', 'Project deleted successfully');
    }
}
