<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessTypes\StoreRequest;
use App\Http\Requests\ProcessTypes\UpdateRequest;
use App\Models\ProcessType;

class ProcessTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('process-types.index')->with([
            'processTypes' => ProcessType::orderBy('process_type')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('process-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $requiredFiles = [];
        foreach ($request->required_files as $key => $value) {
            if ($value === 'on') {
                $requiredFiles[] = str_contains($key, 'Or') ?
                    str_replace('OR', '/', strtoupper($key)) :
                    strtoupper($key);
            }
        }

        $created = ProcessType::create([
            'process_type' => strtoupper($request->process_type),
            'required_files' => implode(',', $requiredFiles),
        ]);

        if (!$created) {
            return redirect()->back()->withInput()->withError('Process type not created.');
        }

        return redirect()->route('process-types.index')->withSuccess('Process type created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProcessType $processType)
    {
        return view('process-types.edit')->with([
            'processType' => $processType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ProcessType $processType)
    {
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

        if (!$updated) {
            return redirect()->back()->withInput()->withError('Process type not updated.');
        }

        return redirect()->route('process-types.index')->withSuccess('Process type updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProcessType $processType)
    {
        $deleted = $processType->delete();

        if (!$deleted) {
            return redirect()->back()->withError('Process type not deleted.');
        }

        return redirect()->route('process-types.index')->withSuccess('Process type deleted.');
    }
}
