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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::query();

        if ($request->has('search')) {
            $suppliers->where('name', 'like', "%$request->search%")
                ->orWhere('average_lead_time', 'like', "%$request->search%");
        }

        return view('supplier.index')->with([
            'suppliers' => $suppliers->orderBy('name')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $supplier = Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with([
            'success' => "$supplier->name created successfully",
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            return redirect()->route('suppliers.index')->with([
                'error' => "Supplier not found",
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
        $supplier = Supplier::find($id);

        if (empty($supplier)) {
            return redirect()->route('suppliers.index')->with([
                'error' => "Supplier not found",
            ]);
        }

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with([
            'success' => "$supplier->name updated successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);
        $name     = $supplier->name;

        if (empty($supplier)) {
            return redirect()->route('suppliers.index')->with([
                'error' => "Supplier not found",
            ]);
        }

        $supplier->representatives()->delete();
        Part::where('supplier_id', $id)->update(['supplier_id' => null]);
        Order::where('supplier_id', $id)->delete();

        $supplier->delete();

        return redirect()->route('suppliers.index')->with([
            'success' => "$name deleted successfully",
        ]);
    }
}
