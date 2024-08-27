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
     * RepresentativeController constructor.
     */
    public function __construct()
    {
        $this->model = Representative::class;
        $this->route = 'representatives';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', Representative::class)) {
            abort(403);
        }

        $this->checkTableConfigurations('representatives', Representative::class);
        $representatives = $this->filter(Representative::class, Representative::query(), $request)->paginate(15);

        if ($representatives->currentPage() > 1 && $representatives->lastPage() < $representatives->currentPage()) {
            return redirect()->route('representatives.index', array_merge(['page' => $representatives->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Representatives',
            'table' => 'representatives',
            'route' => 'representatives',
            'indexRoute' => 'representatives.index',
            'data' => $representatives,
            'model' => Representative::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Representative::class)) {
            abort(403);
        }

        return view('representative.create')->with([
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', Representative::class)) {
            abort(403);
        }

        $representative = Representative::create($request->all());

        return redirect()->route('representatives.index')->with([
            'success' => "$representative->name created successfully",
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->user()->cannot('update', Representative::class)) {
            abort(403);
        }

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
        if ($request->user()->cannot('update', Representative::class)) {
            abort(403);
        }

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
    public function destroy(Request $request, string $id)
    {
        if ($request->user()->cannot('delete', Representative::class)) {
            abort(403);
        }

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
