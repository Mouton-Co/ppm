<?php

namespace App\Http\Controllers;

use App\Http\Requests\Representative\StoreRequest;
use App\Http\Requests\Representative\UpdateRequest;
use App\Models\Representative;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $representatives = Representative::with('supplier');

        if ($request->has('search')) {
            $representatives->where('name', 'like', "%$request->search%")
                ->orWhere('email', 'like', "%$request->search%")
                ->orWhere('phone_1', 'like', "%$request->search%")
                ->orWhere('phone_2', 'like', "%$request->search%")
                ->orWhereHas('supplier', function ($query) use ($request) {
                    $query->where('name', 'like', "%$request->search%");
                });
        }

        return view('representative.index')->with([
            'representatives' => $representatives->orderBy('name')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('representative.create')->with([
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $representative = Representative::create($request->all());

        return redirect()->route('representatives.index')->with([
            'success' => "$representative->name created successfully",
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $representative = Representative::find($id);

        if (empty($representative)) {
            return redirect()->route('representatives.index')->with([
                'error' => 'Representative not found',
            ]);
        }

        return view('representative.edit')->with([
            'representative' => $representative,
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $representative = Representative::find($id);

        if (empty($representative)) {
            return redirect()->route('representatives.index')->with([
                'error' => 'Representative not found',
            ]);
        }

        $representative->update($request->all());

        return redirect()->route('representatives.index')->with([
            'success' => "$representative->name updated successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $representative = Representative::find($id);
        $name = $representative->name;

        if (empty($representative)) {
            return redirect()->route('representatives.index')->with([
                'error' => 'Representative not found',
            ]);
        }

        $representative->delete();

        return redirect()->route('representatives.index')->with([
            'success' => "$name deleted successfully",
        ]);
    }
}
