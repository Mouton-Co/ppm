<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipientGroup\StoreRequest;
use App\Http\Requests\RecipientGroup\UpdateRequest;
use App\Models\Project;
use App\Models\ProjectResponsible;
use App\Models\ProjectStatus;
use App\Models\RecipientGroup;
use Illuminate\Http\Request;

class RecipientGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkTableConfigurations('recipient-groups', RecipientGroup::class);
        $recipientGroups = $this->filter(RecipientGroup::class, RecipientGroup::query(), $request)->paginate(15);

        if ($recipientGroups->currentPage() > 1 && $recipientGroups->lastPage() < $recipientGroups->currentPage()) {
            return redirect()->route('recipient-groups.index', array_merge(['page' => $recipientGroups->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Email Triggers',
            'table' => 'recipient-groups',
            'route' => 'recipient-groups',
            'indexRoute' => 'recipient-groups.index',
            'data' => $recipientGroups,
            'model' => RecipientGroup::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = ProjectResponsible::orderBy('name')->get();
        $statuses = ProjectStatus::orderBy('name')->get();
        $machineNumbers = array_unique(Project::select('machine_nr')->orderBy('machine_nr')->pluck('machine_nr')->toArray());

        return view('recipient-groups.create', [
            'departments' => $departments,
            'statuses' => $statuses,
            'machineNumbers' => $machineNumbers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        // check to see if field and value combination already exist
        $recipientGroup = RecipientGroup::where('field', $request->field)
            ->where('value', $request->value)
            ->first();

        if ($recipientGroup) {
            return redirect()->back()->with('error', 'Email trigger already exists');
        }

        RecipientGroup::create($request->validated());

        return redirect()->route('recipient-groups.index')->with('success', 'Email trigger created');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecipientGroup $recipientGroup)
    {
        $departments = ProjectResponsible::orderBy('name')->get();
        $statuses = ProjectStatus::orderBy('name')->get();
        $machineNumbers = array_unique(Project::select('machine_nr')->orderBy('machine_nr')->pluck('machine_nr')->toArray());

        return view('recipient-groups.edit', [
            'recipientGroup' => $recipientGroup,
            'departments' => $departments,
            'statuses' => $statuses,
            'machineNumbers' => $machineNumbers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, RecipientGroup $recipientGroup)
    {
        // check to see if field and value combination already exist excluding the current record
        $existingGroup = RecipientGroup::where('field', $request->field)
            ->where('value', $request->value)
            ->first();

        if ($existingGroup && $existingGroup->id !== $recipientGroup->id) {
            return redirect()->back()->with('error', 'Email trigger already exists');
        }

        $recipientGroup->update($request->validated());

        return redirect()->route('recipient-groups.index')->with('success', 'Email trigger updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecipientGroup $recipientGroup)
    {
        $recipientGroup->delete();

        return redirect()->route('recipient-groups.index')->with('success', 'Email trigger deleted');
    }
}
