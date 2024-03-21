<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStatus\StoreRequest;
use App\Http\Requests\ProjectStatus\UpdateRequest;
use App\Models\ProjectStatus;

class ProjectStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectStatuses = ProjectStatus::query();

        if (request()->has('search')) {
            $projectStatuses->where('name', 'like', '%' . request('search') . '%');
        }

        return view('project-status.index', [
            'projectStatuses' => $projectStatuses->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project-status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        ProjectStatus::create($request->validated());

        return redirect()->route('project-statuses.index')->with('success', 'Status created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectStatus $projectStatus)
    {
        return view('project-status.edit', [
            'projectStatus' => $projectStatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ProjectStatus $projectStatus)
    {
        $projectStatus->update($request->validated());

        return redirect()->route('project-statuses.index')->with('success', 'Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectStatus $projectStatus)
    {
        $projectStatus->delete();

        return redirect()->back()->with('success', 'Status deleted successfully');
    }
}
