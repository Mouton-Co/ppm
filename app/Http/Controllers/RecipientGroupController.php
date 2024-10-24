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
     * RecipientGroupController constructor.
     */
    public function __construct()
    {
        $this->model = RecipientGroup::class;
        $this->route = 'recipient-groups';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', RecipientGroup::class)) {
            abort(403);
        }

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
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', RecipientGroup::class)) {
            abort(403);
        }

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
        if ($request->user()->cannot('create', RecipientGroup::class)) {
            abort(403);
        }

        // check to see if field and value combination already exist
        $recipientGroup = RecipientGroup::where('field', $request->field)
            ->where('value', $request->value)
            ->first();

        if ($recipientGroup) {
            return redirect()->back()->with('error', 'Email trigger already exists');
        }

        $recipientGroup = RecipientGroup::create($request->validated());

        if ($request->get('field') == "Order confirmed by supplier") {
            $recipientGroup->update(['value' => null]);
        }

        return redirect()->route('recipient-groups.index')->with('success', 'Email trigger created');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, RecipientGroup $recipientGroup)
    {
        if ($request->user()->cannot('update', $recipientGroup)) {
            abort(403);
        }

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
        if ($request->user()->cannot('update', $recipientGroup)) {
            abort(403);
        }

        // check to see if field and value combination already exist excluding the current record
        $existingGroup = RecipientGroup::where('field', $request->field)
            ->where('value', $request->value)
            ->first();

        if ($existingGroup && $existingGroup->id !== $recipientGroup->id) {
            return redirect()->back()->with('error', 'Email trigger already exists');
        }

        $recipientGroup->update($request->validated());

        if ($request->get('field') == "Order confirmed by supplier") {
            $recipientGroup->update(['value' => null]);
        }

        return redirect()->route('recipient-groups.index')->with('success', 'Email trigger updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, RecipientGroup $recipientGroup)
    {
        if ($request->user()->cannot('delete', $recipientGroup)) {
            abort(403);
        }

        $recipientGroup->delete();

        return redirect()->route('recipient-groups.index')->with('success', 'Email trigger deleted');
    }
}
