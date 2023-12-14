<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Requests\Order\IndexRequest;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $orders = Order::
            when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->supplier, function ($query) use ($request) {
                return $query->where('supplier_id', $request->supplier);
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where('po_number', 'like', '%' . $request->search . '%')
                    ->orWhere('submission_code', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        Order::create($request->validated());

        return redirect()->route('orders.index')->withSuccess(
            'Order created.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        $order = Order::find($request->id);

        if (!empty($order)) {
            $order->update($request->validated());
        }

        return redirect()->route('orders.index')->withErrors(
            'Order not found.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
