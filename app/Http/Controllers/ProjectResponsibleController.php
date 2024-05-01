<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectResponsible\StoreRequest;
use App\Http\Requests\ProjectResponsible\UpdateRequest;
use App\Models\ProjectResponsible;

class ProjectResponsibleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectResponsibles = ProjectResponsible::orderBy('name');

        if (request()->has('search')) {
            $projectResponsibles->where('name', 'like', '%' . request('search') . '%');
        }

        return view('project-responsible.index', [
            'projectResponsibles' => $projectResponsibles->paginate(15)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project-responsible.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        ProjectResponsible::create($request->validated());

        return redirect()->route('project-responsibles.index')->with('success', 'Responsible created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectResponsible $projectResponsible)
    {
        return view('project-responsible.edit', [
            'projectResponsible' => $projectResponsible,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ProjectResponsible $projectResponsible)
    {
        $projectResponsible->update($request->validated());

        return redirect()->route('project-responsibles.index')->with('success', 'Responsible updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectResponsible $projectResponsible)
    {
        $projectResponsible->delete();

        return redirect()->route('project-responsibles.index')->with('success', 'Responsible deleted successfully');
    }
}
