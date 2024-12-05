<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Models\Order;
use App\Models\Part;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * SupplierController constructor.
     */
    public function __construct()
    {
        $this->model = Supplier::class;
        $this->route = 'suppliers';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', Supplier::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('suppliers', Supplier::class);
        $suppliers = $this->filter(Supplier::class, Supplier::query(), $request)->paginate(15);

        if ($suppliers->currentPage() > 1 && $suppliers->lastPage() < $suppliers->currentPage()) {
            return redirect()->route('suppliers.index', array_merge(['page' => $suppliers->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Suppliers',
            'table' => 'suppliers',
            'route' => 'suppliers',
            'indexRoute' => 'suppliers.index',
            'data' => $suppliers,
            'model' => Supplier::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Supplier::class)) {
            abort(403);
        }

        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', Supplier::class)) {
            abort(403);
        }

        $supplier = Supplier::create($request->all());

        return redirect()
            ->route('suppliers.index')
            ->with([
                'success' => "$supplier->name created successfully",
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->user()->cannot('update', Supplier::class)) {
            abort(403);
        }

        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            return redirect()
                ->route('suppliers.index')
                ->with([
                    'error' => 'Supplier not found',
                ]);
        }

        return view('supplier.edit')->with([
            'supplier' => $supplier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        if ($request->user()->cannot('update', Supplier::class)) {
            abort(403);
        }

        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            return redirect()
                ->route('suppliers.index')
                ->with([
                    'error' => 'Supplier not found',
                ]);
        }

        $supplier->update($request->all());

        return redirect()
            ->route('suppliers.index')
            ->with([
                'success' => "$supplier->name updated successfully",
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->user()->cannot('delete', Supplier::class)) {
            abort(403);
        }

        $supplier = Supplier::find($id);
        $name = $supplier->name;

        if (empty($supplier)) {
            return redirect()
                ->route('suppliers.index')
                ->with([
                    'error' => 'Supplier not found',
                ]);
        }

        $supplier->representatives()->delete();
        Part::where('supplier_id', $id)->update(['supplier_id' => null]);
        Order::where('supplier_id', $id)->delete();

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with([
                'success' => "$name deleted successfully",
            ]);
    }

    /**
     * Update checkbox with ajax
     */
    public function updateCheckbox(Request $request)
    {
        if (! $request->user()->role->hasPermission('update_suppliers')) {
            abort(403);
        }

        $supplier = Supplier::find($request->id);

        if (empty($supplier)) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found',
            ], 404);
        }
        
        $supplier->{$request->get('field')} = $request->get('value');
        $supplier->save();

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
        ]);
    }
}
