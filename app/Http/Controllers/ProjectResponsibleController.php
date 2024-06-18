<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectResponsible\StoreRequest;
use App\Http\Requests\ProjectResponsible\UpdateRequest;
use App\Models\ProjectResponsible;
use Illuminate\Http\Request;

class ProjectResponsibleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkTableConfigurations('project-responsibles', ProjectResponsible::class);
        $projectResponsibles = $this->filter(ProjectResponsible::class, ProjectResponsible::query(), $request)->paginate(15);

        if ($projectResponsibles->currentPage() > 1 && $projectResponsibles->lastPage() < $projectResponsibles->currentPage()) {
            return redirect()->route('project-responsibles.index', array_merge(['page' => $projectResponsibles->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Project Responsibles',
            'table' => 'project-responsibles',
            'route' => 'project-responsibles',
            'indexRoute' => 'project-responsibles.index',
            'data' => $projectResponsibles,
            'model' => ProjectResponsible::class,
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
