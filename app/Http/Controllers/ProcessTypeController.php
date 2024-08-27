<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessTypes\StoreRequest;
use App\Http\Requests\ProcessTypes\UpdateRequest;
use App\Models\ProcessType;
use Illuminate\Http\Request;

class ProcessTypeController extends Controller
{
    /**
     * ProcessTypeController constructor.
     */
    public function __construct()
    {
        $this->model = ProcessType::class;
        $this->route = 'process-types';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', ProcessType::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('process_types', ProcessType::class);
        $processTypes = $this->filter(ProcessType::class, ProcessType::query(), $request)->paginate(15);

        if ($processTypes->currentPage() > 1 && $processTypes->lastPage() < $processTypes->currentPage()) {
            return redirect()->route('process-types.index', array_merge(['page' => $processTypes->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Process Types',
            'table' => 'process_types',
            'route' => 'process-types',
            'indexRoute' => 'process-types.index',
            'data' => $processTypes,
            'model' => ProcessType::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', ProcessType::class)) {
            abort(403);
        }

        return view('process-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', ProcessType::class)) {
            abort(403);
        }

        $requiredFiles = [];
        foreach ($request->required_files as $key => $value) {
            if ($value === 'on') {
                $requiredFiles[] = str_contains(strtoupper($key), 'OR') ?
                    str_replace('OR', '/', strtoupper($key)) :
                    strtoupper($key);
            }
        }

        $created = ProcessType::create([
            'process_type' => strtoupper($request->process_type),
            'required_files' => implode(',', $requiredFiles),
        ]);

        if (! $created) {
            return redirect()->back()->withInput()->withError('Process type not created.');
        }

        return redirect()->route('process-types.index')->withSuccess('Process type created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, ProcessType $processType)
    {
        if ($request->user()->cannot('update', ProcessType::class)) {
            abort(403);
        }

        return view('process-types.edit')->with([
            'processType' => $processType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ProcessType $processType)
    {
        if ($request->user()->cannot('update', ProcessType::class)) {
            abort(403);
        }

        $requiredFiles = [];
        foreach ($request->required_files as $key => $value) {
            if ($value === 'on') {
                $requiredFiles[] = str_contains($key, 'Or') ?
                    str_replace('OR', '/', strtoupper($key)) :
                    strtoupper($key);
            }
        }

        $updated = $processType->update([
            'process_type' => strtoupper($request->process_type),
            'required_files' => implode(',', $requiredFiles),
        ]);

        if (! $updated) {
            return redirect()->back()->withInput()->withError('Process type not updated.');
        }

        return redirect()->route('process-types.index')->withSuccess('Process type updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ProcessType $processType)
    {
        if ($request->user()->cannot('delete', ProcessType::class)) {
            abort(403);
        }

        $deleted = $processType->delete();

        if (! $deleted) {
            return redirect()->back()->withError('Process type not deleted.');
        }

        return redirect()->route('process-types.index')->withSuccess('Process type deleted.');
    }
}
