<?php

namespace App\Http\Controllers;

use App\Exports\AutofillSupplierExport;
use App\Http\Requests\AutofillSuppliers\StoreRequest;
use App\Http\Requests\AutofillSuppliers\UpdateRequest;
use App\Models\AutofillSupplier;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AutofillSupplierController extends Controller
{
    /**
     * AutofillSupplierController constructor.
     */
    public function __construct()
    {
        $this->model = AutofillSupplier::class;
        $this->route = 'autofill-suppliers';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', AutofillSupplier::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('autofill-suppliers', AutofillSupplier::class);
        $autofillSuppliers = $this->filter(AutofillSupplier::class, AutofillSupplier::query(), $request)->paginate(15);

        if ($autofillSuppliers->currentPage() > 1 && $autofillSuppliers->lastPage() < $autofillSuppliers->currentPage()) {
            return redirect()->route('autofill-suppliers.index', array_merge(['page' => $autofillSuppliers->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Autofill Suppliers',
            'table' => 'autofill-suppliers',
            'route' => 'autofill-suppliers',
            'indexRoute' => 'autofill-suppliers.index',
            'data' => $autofillSuppliers,
            'model' => AutofillSupplier::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', AutofillSupplier::class)) {
            abort(403);
        }

        return view('autofill-suppliers.create')->with([
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', AutofillSupplier::class)) {
            abort(403);
        }

        $created = AutofillSupplier::create($request->validated());

        if (! $created) {
            return redirect()->back()->withInput()->withError('Autofill supplier not created.');
        }

        return redirect()->route('autofill-suppliers.index')->withSuccess('Autofill supplier created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, AutofillSupplier $autofillSupplier)
    {
        if ($request->user()->cannot('update', AutofillSupplier::class)) {
            abort(403);
        }

        return view('autofill-suppliers.edit')->with([
            'autofillSupplier' => $autofillSupplier,
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, AutofillSupplier $autofillSupplier)
    {
        if ($request->user()->cannot('update', AutofillSupplier::class)) {
            abort(403);
        }

        $updated = $autofillSupplier->update($request->validated());

        if (! $updated) {
            return redirect()->back()->withInput()->withError('Autofill supplier not updated.');
        }

        return redirect()->route('autofill-suppliers.index')->withSuccess('Autofill supplier updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AutofillSupplier $autofillSupplier)
    {
        if (request()->user()->cannot('delete', AutofillSupplier::class)) {
            abort(403);
        }

        $deleted = $autofillSupplier->delete();

        if (! $deleted) {
            return redirect()->back()->withError('Autofill supplier not deleted.');
        }

        return redirect()->route('autofill-suppliers.index')->withSuccess('Autofill supplier deleted.');
    }

    /**
     * Export autofill suppliers to excel
     *
     * @return \Maatwebsite\Excel\Excel
     */
    public function export(Request $request)
    {
        if ($request->user()->cannot('read', AutofillSupplier::class)) {
            abort(403);
        }

        return (new AutofillSupplierExport($request))->download('autofill-suppliers.xlsx');
    }
}
